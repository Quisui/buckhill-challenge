<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public User $user;
    public User $admin;
    public User $marketingUser;
    public User $marketingAdminUser;
    public User $buckHillAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = $this->createUser();
        $this->admin = $this->createUser(isAdmin: true);
        $this->marketingUser = $this->createUser(isMarketing: true);
        $this->marketingAdminUser = $this->createUser(isAdmin: true, isMarketing: true);
        $this->buckHillAdmin = User::factory()->create([
            'first_name' => 'BuckHillAdmin',
            'email' => 'admin@buckhill.co.uk',
            'password' => Hash::make('admin'),
            'is_admin' => true,
        ]);
    }

    private function createUser(bool $isAdmin = false, bool $isMarketing = false): User
    {
        $data = [
            'is_admin' => $isAdmin,
            'is_marketing' => $isMarketing
        ];

        return User::factory()->create($data);
    }
}
