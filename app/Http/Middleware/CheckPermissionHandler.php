<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermissionHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$check_permissions)
    {
        //Get request variables
        $available_permissions = $request->get('auth_user_permissions');
        $roles = $request->get('auth_user_roles');
        if ($roles === null || $available_permissions === null) {
            return response()->json(['message' => 'Invalid token provided!'], 403);
        }

        if (in_array("admin", $roles)) {
            return $next($request);
        }

        //Check if the given permission exist in available permission
        $array_diff = array_diff($check_permissions, $available_permissions);

        if (count($array_diff) == 0) {
            return $next($request);
        } else {
            return response()->json(['message' => 'You do not have enough permissions to access this functionality. Missing Permission:' . $check_permissions[0] ?? ''], 403);
        }
    }
}
