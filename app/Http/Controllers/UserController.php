<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Database;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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
        if ($filter === 'oldest') {
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
}
