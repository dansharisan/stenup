<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Traits as Traits;

class BlockThirdPartyReferers
{
    use Traits\ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $acceptedClients = explode(',', \Config::get('app.accepted_clients'));
        $isFromAnAcceptedClient = false;
        if (isset($_SERVER['HTTP_REFERER'])) {
            $refererUrlParts = parse_url($_SERVER['HTTP_REFERER']);
            $baseRefererUrlWithoutPort = $refererUrlParts["scheme"] . "://" . $refererUrlParts["host"];
            $baseRefererUrl = isset($refererUrlParts["port"]) ? $baseRefererUrlWithoutPort . ":" . $refererUrlParts["port"] : $baseRefererUrlWithoutPort;

            foreach ($acceptedClients as $acceptedClient) {
                if ($baseRefererUrl == $acceptedClient || $baseRefererUrl . '/' == $acceptedClient) {
                    $isFromAnAcceptedClient = true;
                }
            }
        }

        // Let the request gone through if it is not coming from an accepted client
		if ($isFromAnAcceptedClient)
		{
			return $next($request);
		}

        // Otherwise show error
        return $this->invalidRefererResponse();
    }
}
