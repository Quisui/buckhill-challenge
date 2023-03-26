<?php

namespace App\Http\Controllers;

use App\Helpers\TokenHelper;
use App\Models\JwtToken;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Carlos BuckHill Api",
 *      description="L5 Swagger Api documentation for Buckhill Challenge",
 *      @OA\Contact(
 *          email="quiqueal.19@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API/V1 Server for challenge"
 * )
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function storeJWT(string $token)
    {
        $tokenExpiry = Carbon::createFromTimestamp(TokenHelper::jwtDecode($token)->exp);
        $userId = auth()->user()->uuid;

        JwtToken::where('user_id', $userId)->delete();
        return JwtToken::create([
            'user_id' => $userId,
            'token_title' => 'auth',
            'restrictions' => '{"restrictions": "*"}',
            'permissions' => '{"permissions": "*"}',
            'expires_at' => $tokenExpiry,
            'last_used_at' => Carbon::now(),
            'refreshed_at' => Carbon::now(),
        ]);
    }
}
