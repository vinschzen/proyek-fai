<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

use Kreait\Firebase\Exception\Auth\InvalidPassword as FirebaseInvalidPasswordException;


class UserController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = app('firebase.auth');
    }

    public function index(Request $request)
    {
        $users = [];

        foreach ($this->auth->listUsers() as $user) {

            $role = $user->customClaims['role'];
            $status = $user->disabled;
            
            $displayRole = match($role) {
                "0" => "User",
                "1" => "Staff",
                "2" => "Admin",
                default => null
            };

            $displayStatus = match($status) {
                true => "Disabled",
                false => "Enabled",
                default => null
            };

            $users[] = [
                'uid' => $user->uid,
                'displayName' => $user->displayName,
                'email' => $user->email,
                'role' => $displayRole,
                'status' => $displayStatus,
            ];
        }

        $search = $request->input('search');
        if ($search) {
            $users = array_filter($users, function ($user) use ($search) {
                return strpos(strtolower($user['displayName']), strtolower($search)) !== false;
            });
        }

        $filter = $request->input('filter', 'newest');
        if ($filter === 'newest') {
            $users = array_reverse($users);
        }
    
        $perPage = 5;
        $currentPage = Paginator::resolveCurrentPage('page');

        $currentItems = array_slice($users, ($currentPage - 1) * $perPage, $perPage);

        $users = new LengthAwarePaginator(
            $currentItems,
            count($users),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    

        return view('admin/users/master', compact('users'));

    }

    public function toggle($id)
    {
        $user = $this->auth->getUser($id);
        if ($user->disabled)
        {
            $user = $this->auth->enableUser($id);
        }
        else $user = $this->auth->disableUser($id);

        return redirect()->route('toMasterUser')->with('success', 'User toggled');
    }

    public function changepassworduser($id, Request $request)
    {
        $rules = [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6|different:old_password|confirmed',
        ];
        
        $messages = [
            'old_password.required' => 'The old password field is required.',
            'old_password.min' => 'Password length must be 6 or more characters.',
            'new_password.required' => 'The new password field is required.',
            'new_password.min' => 'Password length must be 6 or more characters.',
            'new_password.different' => 'New password must be different from the old password.',
            'new_password.confirmed' => 'Confirmation does not match the new password.',
        ];
        $request->validate($rules, $messages);

        try {
            $signInResult = $this->auth->signInWithEmailAndPassword(
                $request->session()->get("user")->email,
                $request->old_password
            );

        } catch (\Throwable $e) {
            return back()->with(['error' => $e->getMessage()]);
        }

        $updatedUser = $this->auth->changeUserPassword($id, $request->new_password);

        return redirect()->route('toProfile')->with('msg', 'User password changed');
    }

    public function changepassword($id, Request $request)
    {
        $rules = [
            'new_password' => 'required|min:6',
        ];
        
        $messages = [
            'new_password.required' => 'The password field is required.',
            'new_password.min:6' => 'Password length must be 6 or more',
        ];
        
        $request->validate($rules, $messages);

        $updatedUser = $this->auth->changeUserPassword($id, $request->new_password);

        return redirect()->route('toMasterUser')->with('success', 'User password changed');
    }

    public function changeusername($id, Request $request)
    {
        $rules = [
            'username' => 'required',
        ];
        
        $messages = [
            'username.required' => 'The username field is required.',
        ];
        
        $request->validate($rules, $messages);

        $properties = [
            'displayName' => $request->username
        ];

        $updatedUser = $this->auth->updateUser($id, $properties);

        return redirect()->route('toMasterUser')->with('success', 'Username changed');
    }

    public function changerole($id, Request $request)
    {
        $user = $this->auth->getUser($id);

        $currentSaldo = $user->customClaims['saldo'] ?? 0;

        $this->auth->setCustomUserClaims($id, ['role' => $request->new_role,'saldo' => $currentSaldo]);

        return redirect()->route('toMasterUser')->with('success', 'Role changed');
    }

    public function viewedit($id) 
    {
        $user = $this->auth->getUser($id);

        if (!$user) {
            abort(404); 
        }
        
        return view('admin/users/edit', compact('user'));
    }

    public function viewadd() 
    {

        return view('admin/users/add');
    }

    public function store(Request $request)
    {
        try {

            $rules = [
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'password' => 'required|min:6',
            ];
            $messages = [
                'username.required' => 'The username field is required.',
                'username.string' => 'The username must be a string.',
                'username.max' => 'The username may not be greater than :max characters.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.max' => 'The email may not be greater than :max characters.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least :min characters.',
            ];

            $request->validate($rules, $messages);

            $user = $request->only(['email', 'username', 'password']);
            $userProperties = [
                'email' => $user['email'],
                'displayName' => $user['username'],
                'password' => $user['password'],
            ];

            $user = $this->auth->createUser($userProperties);
            $this->auth->setCustomUserClaims($user->uid, ['role' => $request->role, 'saldo' => 0]);

            return redirect()->route('toMasterUser')->with('success', 'User added');

        } catch (FirebaseEmailExistsException $e) {
            return back()->with(['error' => 'Email address already exists']);
        } catch (FirebaseInvalidEmailException $e) {
            return back()->with(['error' => 'Invalid email address']);
        } catch (FirebaseInvalidPasswordException $e) {
            return back()->with(['error' => 'Invalid password']);
        } catch (\Throwable $e) {

            return back()->with(['error' => $e->getMessage()]);
        }
    }
}
