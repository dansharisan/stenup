<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Token;
use App\Enums as Enums;

class JWTAuthenticate
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @param  string|null  $guard
    * @return mixed
    */
    public function handle($request, Closure $next, $guard = null)
    {
        $rawToken = $request->cookie('token');

        /* Start Hack: Check authentication for extra tools */
        $toolPathPermissionMappings = [
            \Config::get('telescope.path') => Enums\PermissionEnum::ACCESS_TELESCOPE
            , 'l5-swagger.docs' => Enums\PermissionEnum::ACCESS_API
        ];
        $currentRoute = $request->route()->getName();

        // Throw error page if route of request is among the above paths but does not attach jwt token
        if (in_array($currentRoute, array_keys($toolPathPermissionMappings)) && !$rawToken) {
            return redirect('/403');
        }
        /* End Hack: Check authentication for extra tools */

        $token = new Token($rawToken);
        $payload = JWTAuth::decode($token);
        Auth::loginUsingId($payload['sub']);

        /* Start Hack: Check authorization for extra tools */
        $user = $request->user();
        if (!$user) {
            return redirect('/403');
        }
        foreach ($toolPathPermissionMappings as $path => $permission) {
            if ($currentRoute == $path && !$user->hasPermissionTo($permission)) {
                return redirect('/403');
            }
        }
        /* End Hack: Check authorization for extra tools */

        return $next($request);
    }
}
