<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Intern
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //If user is not logged in
        if(!Auth::check()) {
            return redirect()->route('login');
        }

        $role = Auth::user()->role;

        //Super Admin
        if($role == 'super_admin') {
            return redirect()->route('super-admin.dashboard');
        }

        //Admin
        elseif($role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        //Intern
        elseif($role == 'intern') {
            return $next($request);
        }
    }
}
