<?php

namespace Tests\Unit\Api\V1;

use App\Helpers\TokenHelper;
use Carbon\Carbon;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JWTAppTest extends TestCase
{
    use RefreshDatabase;

    public function testJWTTokenHelperCanEncode()
    {
        $payload = [
            'sub' => '1234567890',
            'name' => 'Test name',
            'iat' => 1516239022,
            'exp' => Carbon::now()->addMinutes(config('app.jwt_max_exp_minutes'))->getTimestamp(),
        ];

        $token = TokenHelper::jwtEncode($payload);
        $decoded_token = TokenHelper::jwtDecode($token);

        $this->assertEquals($decoded_token->sub, '1234567890');
        $this->assertEquals($decoded_token->name, 'Test name');
        $this->assertEquals($decoded_token->iat, 1516239022);
    }

    public function testJWTTokenHelperCanDecode()
    {
        $payload = [
            'sub' => '1234567890',
            'name' => 'Test name',
            'iat' => 1516239022,
            'exp' => Carbon::now()->addMinutes(config('app.jwt_max_exp_minutes'))->getTimestamp(),
        ];

        $token = TokenHelper::jwtEncode($payload);
        $decodedToken = TokenHelper::jwtDecode($token);
        $this->assertEquals($decodedToken, TokenHelper::jwtDecode($token));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Couldn't Decrypt your Token");
        TokenHelper::jwtDecode($token . 'invalid');
    }
}
