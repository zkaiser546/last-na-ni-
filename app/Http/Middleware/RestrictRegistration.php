<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $superAdminType = UserType::where('key', 'super_admin')->first();

        if ($superAdminType && User::where('user_type_id', $superAdminType->id)->exists()) {
            return redirect()->route('home')->with('error', 'Registration is disabled as a super admin already exists.');
        }

        return $next($request);
    }
}
