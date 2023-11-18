<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle an unauthenticated user.
     */
    // protected function unauthenticated($request, array $guards)
    // {
    //     if ($request->expectsJson()) {
    //         abort(401);
    //     }

    //     // dd($request->session()->get('user'));
        
    //     if (!$request->session()->has('user')) {
    //         return redirect('/login');
    //     }
        
    //     return null;
    // }

    // protected function unauthenticated($request, array $guards)
    // {
    //     if ($request->expectsJson()) {
    //         return response()->json(['message' => 'Unauthenticated.'], 401);
    //     }

    //     return redirect()->guest(route('login'));
    // }

    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}

