<?php

namespace App\Http\Traits;

use App\Enums as Enums;
use Symfony\Component\HttpFoundation\Response as Response;

trait ResponseTrait {
    public function forbiddenResponse()
    {
        return response()->json(
            ['error' =>
                        [
                            'code' => Enums\ErrorEnum::AUTH0015,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0015)
                        ]
            ], Response::HTTP_FORBIDDEN
        );
    }

    public function invalidRefererResponse()
    {
        return response()->json(
            ['error' =>
                        [
                            'code' => Enums\ErrorEnum::AUTH0016,
                            'message' => Enums\ErrorEnum::getDescription(Enums\ErrorEnum::AUTH0016)
                        ]
            ], Response::HTTP_FORBIDDEN
        );
    }
}
