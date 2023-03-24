<?php

namespace Tests\Feature\Api\V1\Middlewares;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IsAdminTest extends TestCase
{
    use RefreshDatabase;

    public function testIsAdminMiddleWareWorks()
    {
        $this->actingAs($this->buckHillAdmin)
            ->postJson('api/v1/admin/register/users', [], [
                'Accept' => "application/json",
                'Authorization' => 'Bearer ' . $this->jwtAdminToken
            ])
            ->assertInvalid(['email', 'password', 'is_admin', 'is_marketing']); //this ensure that you accessed the route
    }

    public function testIsAdminMiddleWareNotWorkingWithNormalUser()
    {
        $this->actingAs($this->user)
            ->postJson('api/v1/admin/register/users', [], [
                'Accept' => "application/json",
                'Authorization' => 'Bearer ' . $this->jwtUserToken
            ])
            ->assertJsonPath('message', "Action only available for admins.")
            ->assertStatus(401);
    }
}
