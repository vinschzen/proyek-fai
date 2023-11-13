<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     if (!$request->session()->has('user') || $request->session()->get('user.role') !== 'admin') {
    //         // abort(403); 
    //         return redirect()->route('login'); 

    //     }

    //     return $next($request);
    // }
}
