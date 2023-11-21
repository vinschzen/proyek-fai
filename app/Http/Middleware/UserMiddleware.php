<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->session()->get('user');

        if (!$user)
        {
            return redirect()->route('toLogin');
        }
        
        if ($user && $user->customClaims['role'] >= 0) {
            return $next($request);
        }
        
        return redirect()->route('toHome');
    }

}
