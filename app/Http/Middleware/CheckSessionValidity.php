<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionValidity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        $sessionKey = config('app.session_key');   
        if(config('app.force_logout_on_restart') && Session::get('session_key') !== $sessionKey){
            if (auth()->check()) {
                auth()->logout();
                Session::flush();
                Session::put('session_key', $sessionKey);
                return redirect()->route('login')->with('status', 'You have been logged out due to server restart.');
            } else {
                Session::flush();
                Session::put('session_key', $sessionKey);
            }
        }
        return $next($request);
    }
}

