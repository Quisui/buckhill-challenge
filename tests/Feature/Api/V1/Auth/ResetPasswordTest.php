<?php

namespace Tests\Feature\Api\V1\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanChangePassword()
    {
        $response = $this->actingAs($this->buckHillAdmin)->putJson('/api/v1/user/forgot-password', [
            'current_password'      => 'admin',
            'password'              => 'testing123',
            'password_confirmation' => 'testing123',
        ], [
            'Accept' => "application/json",
            'Authorization' => 'Bearer ' . $this->jwtAdminToken
        ]);

        $response->assertStatus(202);
    }

    public function testAdminHasInvalidRequiredFieldsOnPasswordReset()
    {
        $this->actingAs($this->buckHillAdmin)->putJson('/api/v1/user/forgot-password', [
            'current_password'      => 'admina',
            'password'              => 'testing123',
            'password_confirmation' => '',
        ], [
            'Accept' => "application/json",
            'Authorization' => 'Bearer ' . $this->jwtAdminToken
        ])->assertInvalid(['current_password', 'password', 'password_confirmation']);
    }

    public function testUserCanChangePassword()
    {
        $response = $this->actingAs($this->user)->putJson('/api/v1/user/forgot-password', [
            'current_password'      => 'userpassword',
            'password'              => 'testing12312',
            'password_confirmation' => 'testing12312',
        ], [
            'Accept' => "application/json",
            'Authorization' => 'Bearer ' . $this->jwtUserToken
        ]);

        $response->assertStatus(202);
    }

    public function testUserHasInvalidRequiredFieldsOnPasswordReset()
    {
        $this->actingAs($this->user)->putJson('/api/v1/user/forgot-password', [
            'current_password'      => 'usernotcorrectpsw',
            'password'              => '',
            'password_confirmation' => '',
        ], [
            'Accept' => "application/json",
            'Authorization' => 'Bearer ' . $this->jwtUserToken
        ])->assertInvalid(['current_password', 'password', 'password_confirmation']);
    }
}
