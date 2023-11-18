<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Kreait\Firebase\Auth;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Exception\Auth\EmailExists as FirebaseEmailExistsException;
use Kreait\Firebase\Exception\Auth\InvalidEmail as FirebaseInvalidEmailException;
use Kreait\Firebase\Exception\Auth\InvalidPassword as FirebaseInvalidPasswordException;
use Kreait\Firebase\Exception\Auth\RevokedIdToken as FirebaseRevokedIdTokenException;
use Kreait\Firebase\Exception\Auth\UserNotFound as FirebaseUserNotFoundException;
use Kreait\Firebase\Auth\CreateSessionCookie\FailedToCreateSessionCookie;

class FirebaseAuthController extends Controller
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->middleware('guest')->except('logout');
        $this->auth = app('firebase.auth');
    }

    public function register(Request $request)
    {
        try {
            $userProperties = [
                'email' => $request->input('email'),
                'displayName' => $request->input('username'),
                'password' => $request->input('password'),
            ];

            $user = $this->auth->createUser($userProperties);

            $this->auth->setCustomUserClaims($user->uid, ['role' => "0", 'saldo' => 0]);

            return back()->with(['msg' => 'Registered']);
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

    public function login(Request $request)
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword(
                $request->input('email'),
                $request->input('password')
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