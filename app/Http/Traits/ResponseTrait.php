<?php

namespace App\Http\Traits;

use App\Enums\ErrorEnum;
use Symfony\Component\HttpFoundation\Response as Response;

trait ResponseTrait {
    public function returnUnauthorizedResponse()
    {
        return response()->json(
            ['error' =>
                        [
                            'code' => ErrorEnum::AUTH0010,
                            'message' => ErrorEnum::getDescription(ErrorEnum::AUTH0010)
                        ]
            ], Response::HTTP_UNAUTHORIZED
        );
    }
}
