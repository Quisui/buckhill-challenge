<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{

    use RefreshDatabase;

    public function testMissingValuesInLoginRequest(): void
    {
        $this->postJson('api/v1/admin/login', [])
            ->assertInvalid(['email', 'password']);
    }


    public function testUserCanLoginWithCorrectCredentials()
    {
        $response = $this->postJson('api/v1/admin/login', [
            'email' => $this->buckHillAdmin->email,
            'password' => 'admin',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['unique_id', 'token', 'token_expiry_text', 'token_expiry_seconds']);

        $this->assertDatabaseHas('jwt_tokens', ['unique_id' => $response->json()['unique_id']]);
    }

    public function testUserCannotLoginWithIncorrectCredentials()
    {
        $response = $this->postJson('api/v1/admin/login', [
            'email' => $this->user->email,
            'password' => 'not_correct_password',
        ]);

        $response->assertStatus(422);
    }
}
