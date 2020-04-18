<?php

namespace App\Http\Traits;

use App\Enums\Error;
use Symfony\Component\HttpFoundation\Response as Response;

trait ResponseTrait {
    public function returnUnauthorizedResponse()
    {
        return response()->json(
            ['error' =>
                        [
                            'code' => Error::AUTH0010,
                            'message' => Error::getDescription(Error::AUTH0010)
                        ]
            ], Response::HTTP_UNAUTHORIZED
        );
    }
}
