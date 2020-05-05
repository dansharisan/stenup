<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Token;
use App\Enums as Enums;

class JWTAuthenticateForTelescope
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

        // Hack: Dedicated hack for Telescope authentication
        if ($request->route()->getName() == \Config::get('telescope.path') && !$rawToken) {
            abort('403');
        }

        $token = new Token($rawToken);
        $payload = JWTAuth::decode($token);
        Auth::loginUsingId($payload['sub']);

        // Hack: Dedicated hack for Telescope authentication
        $user = $request->user();
        if (!$user || !$user->hasPermissionTo(Enums\PermissionEnum::ACCESS_TELESCOPE)) {
            abort('403');
        }

        return $next($request);
    }
}
