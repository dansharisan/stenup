<?php

namespace App\Http\Traits;

use App\Enums as Enums;
use Symfony\Component\HttpFoundation\Response as Response;

trait ResponseTrait {
    public function returnUnauthorizedResponse()
    {
        return response()->json(
            ['error' =>
                        [
                            'code' => Enums\ErrorEnum::AUTH0010,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0010)
                        ]
            ], Response::HTTP_UNAUTHORIZED
        );
    }
}
