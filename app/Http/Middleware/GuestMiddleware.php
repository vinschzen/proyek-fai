<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    // public function handle(Request $request, Closure $next): Response
    // {

    //     if ($request->session()->get('user')) {
    //         return $next($request);
    //     }

    //     return redirect('/login');
    // }

    public function handle(Request $request, Closure $next)
    {
        if($request->session()->get('user')) {
            return redirect()->route('toHome');
        }

        return $next($request);
    }
}
