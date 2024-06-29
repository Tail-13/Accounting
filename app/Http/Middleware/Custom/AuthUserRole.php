<?php

namespace App\Http\Middleware\Custom;

use App\Models\AccessToken;
use App\Models\Role;
use App\Models\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roleName): Response
    {
        if($request->user_id) {
            $userRole = UserRole::where('user_id', $request->user_id)->first();
            $role = Role::where('name', 'ilike', $roleName)->first();
            if($userRole->role_code == $role->code) {
                return $next($request);
            }
        }
        return response(['errors' => 'unauthorized access'], HttpResponse::HTTP_FORBIDDEN);
    }
}
