<?php
use Symfony\Component\HttpFoundation\Response as Response;
use App\Models\User;
use App\Http\Traits\UtilTrait;
use App\Enums\DefaultRoleType;
use App\Enums\PermissionType;

class UserCest
{
    use UtilTrait;
    /**
    * Endpoint: GET /api/users
    **/
    public function index(ApiTester $I)
    {
        
    }
}
