<?php

namespace App\Http\Middleware;

use App\Constants;
use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;

class PermissionHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        try {
            if (isset($token))
                $token_response = (array) JWT::decode($token, new Key(config("session.JWT_KEY"), 'HS256'));
            /* if (isset($token_response["user_id"]) == false) {
                return response()->json(['message' => 'Token is invalid!'], 419); //commenting it out a token can exist without a user id
            } */
            //Set auth user id
            if (!empty($token_response['user_id'])) {
                $request->request->add(['auth_user_id' => $token_response['user_id']]);
            }
            //Set auth user id
            if (!empty($token_response['roles'])) {
                $request->request->add(['auth_user_roles' => $token_response['roles']]);
            }
            //Set auth user company id
            if (!empty($token_response['company_id'])) {
                $request->request->add(['auth_user_company_id' => $token_response['company_id']]);
            }
            //Set auth user location id
            if (!empty($token_response['location_id'])) {
                $request->request->add(['auth_user_location_id' => $token_response['location_id']]);
            }
            //Set auth user tenant id
            if (!empty($token_response['tenant_id'])) {
                $request->request->add(['auth_user_tenant_id' => $token_response['tenant_id']]);
            }
            //Set auth user location id
            if (!empty((array) ($token_response['types'] ?? []))) {
                $request->request->add(['auth_user_types' => (array) $token_response['types']]);
            }
            //Get permission constants
            $constant_permissions = Constants::PERMISSIONS;
            $available_permissions = [];
            //Map token permissions to current users available permission
            foreach (($token_response['scopes']?->admin_portal ?? []) as $scope) {
                foreach ($scope as $permission_index) {
                    if (!isset($constant_permissions[$permission_index])) {
                        continue;
                    }
                    $available_permissions[] = $constant_permissions[$permission_index];
                }
            }
            //Set auth user permissions
            if (!empty($available_permissions)) {
                $request->request->add(['auth_user_permissions' => $available_permissions]);
            }
        } catch (Throwable $e) {
            return response()->json(['message' => 'Token is invalid or expired. ' . $e->getMessage()], 419);
        }
        return $next($request);
    }
}
