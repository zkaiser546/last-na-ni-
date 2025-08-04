<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $superAdminType = UserType::where('key', 'super_admin')->first();
        $loginEnabled = $superAdminType && User::where('user_type_id', $superAdminType->id)->exists();

        if (!$loginEnabled) {
            return redirect()->route('home')->with('error', 'Login is disabled. A super-admin must be created first.');
        }

        return $next($request);
    }
}
