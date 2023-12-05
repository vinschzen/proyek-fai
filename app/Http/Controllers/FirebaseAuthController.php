<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmationMail;
use Illuminate\Support\Str;
// use Kreait\Firebase\Auth;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExistsException;
use Kreait\Firebase\Exception\Auth\InvalidEmail as FirebaseInvalidEmailException;
use Kreait\Firebase\Exception\Auth\InvalidPassword as FirebaseInvalidPasswordException;
use Kreait\Firebase\Exception\Auth\RevokedIdToken as FirebaseRevokedIdTokenException;
use Kreait\Firebase\Exception\Auth\UserNotFound as FirebaseUserNotFoundException;
use Kreait\Firebase\Auth\CreateSessionCookie\FailedToCreateSessionCookie;

class FirebaseAuthController extends Controller
{
    protected $auth;
    protected $database;

    public function __construct(Auth $auth, Database $database)
    {
        $this->middleware('guest')->except('logout');
        $this->auth = app('firebase.auth');
        $this->database = $database;
    }

    public function register(Request $request)
    {
        try {

            $cekConfirm = $this->database->getReference('tconfirmations');
            $query = $cekConfirm->orderByChild('email')->equalTo($request->email);
            $checkDouble = $query->getValue();

            if ($checkDouble) return back()->with(['msg' => 'Confirmation email pending']);

            $user = $request->only(['email', 'username']);
            $user['password'] = Crypt::encryptString($request->password);
            $user['confirmation_token'] = Str::uuid();

            $confirmationsRef = $this->database->getReference('tconfirmations')->push();
            $confirmationsRef->set($user);

            Mail::to($request->input('email'))->send(new ConfirmationMail($user));    
            
            return back()->with('msg', 'Confirmation email has been sent');
        } catch (FirebaseEmailExistsException $e) {
            return back()->with(['msg' => 'Email address already exists']);
        } catch (FirebaseInvalidEmailException $e) {
            return back()->with(['msg' => 'Invalid email address']);
        } catch (FirebaseInvalidPasswordException $e) {
            return back()->with(['msg' => 'Invalid password']);
        } catch (\Throwable $e) {

            return back()->with(['msg' => $e->getMessage()]);
        }
    }

    public function confirm($token)
    {
        $confirmationsRef = $this->database->getReference('tconfirmations');
        $query = $confirmationsRef->orderByChild('confirmation_token')->equalTo($token);
        $confirmation = $query->getValue();

        $array = array_map(function ($value, $key) {
            $value['key'] = $key;
            return $value;
        }, $confirmation, array_keys($confirmation));

        $confirmation = $array[0];

        if ($confirmation) {
            $userProperties = [
                'email' => $confirmation['email'],
                'displayName' => $confirmation['username'],
                'password' => Crypt::decryptString($confirmation['password']),
            ];


            $user = $this->auth->createUser($userProperties);

            $this->auth->setCustomUserClaims($user->uid, ['role' => "0", 'saldo' => 0]);

            $this->database->getReference('tconfirmations')->getChild($confirmation['key'])->remove();

            return redirect()->route('toLogin')->with('msg', 'User registered');
        }

    }

    public function login(Request $request)
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword(
                $request->email,
                $request->password
            );

            $idToken = $signInResult->idToken();
            $uid = $signInResult->firebaseUserId();

            $user = $this->auth->getUser($uid);
            $request->session()->put('user', $user);

            return redirect('user/home')->with(['msg' => 'logged in successfully']);

        } catch (FirebaseInvalidEmailException $e) {
            return back()->with(['msg' => 'Invalid email address']);
        } catch (FirebaseInvalidPasswordException $e) {
            return back()->with(['msg' => 'Invalid password']);
        } catch (FirebaseUserNotFoundException $e) {
            return back()->with(['msg' => 'User not found']);
        } catch (FirebaseRevokedIdTokenException $e) {
            return back()->with(['msg' => 'Revoked ID token']);
        } catch (\Throwable $e) {
            return back()->with(['msg' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');

        return redirect('login')->with(['msg' => 'Logged out']);
    }

    public function topup($id, Request $request)
    {
        $user = $this->auth->getUser($id);

        $currentSaldo = $user->customClaims['saldo'] ?? 0;
        $currentRole = $user->customClaims['role'] ?? 0;

        $newSaldo = $currentSaldo + $request->amount;

        $this->auth->setCustomUserClaims($id, ['role' => $currentRole,'saldo' => $newSaldo]);

        $this->refreshLoggedIn($request);

        return redirect()->route('toHome')->with(['msg' => 'Berhasil topup']);
    }

    public function refreshLoggedIn(Request $request)
    {
        $loggedIn = $request->session()->get('user');

        $user = $this->auth->getUser($loggedIn->uid);

        $request->session()->put('user', $user);;
    }

}