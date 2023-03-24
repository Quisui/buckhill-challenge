<?php

namespace Tests\Feature\Api\V1\Middlewares;

use Firebase\JWT\SignatureInvalidException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use UnexpectedValueException;

class AuthApiJWTTest extends TestCase
{
    /* in Api: middleware = ['auth:api'] this is what we're testing*/

    use RefreshDatabase;

    public function testJWTIsRequiredInAuthenticatedEndPoint(): void
    {
        $this->actingAs($this->buckHillAdmin)
            ->postJson('api/v1/admin/register/users', [], [
                'Accept' => "application/json",
                'Authorization' => 'Bearer ' . $this->jwtAdminToken
            ])
            ->assertInvalid(['email', 'password', 'is_admin', 'is_marketing']);
    }

    public function testJwtAuthMiddlewareIsNotValid(): void
    {
        $this->actingAs($this->buckHillAdmin)
            ->postJson('api/v1/admin/register/users', [], [
                'Accept' => "application/json",
                'Authorization' => 'Bearer ' . $this->jwtAdminToken . 'invalid'
            ])
            ->assertJsonPath('message',  "Couldn't Decrypt your Token")
            ->assertStatus(500);
    }
}
