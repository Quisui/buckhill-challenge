<?php

namespace Tests;

use App\Helpers\TokenHelper;
use App\Models\User;
use Carbon\Carbon;
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
    public $jwtAdminToken;
    public $jwtUserToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->user = $this->createUser(false, false, ['password' => Hash::make('userpassword')]);
        $this->admin = $this->createUser(isAdmin: true);
        $this->marketingUser = $this->createUser(isMarketing: true);
        $this->marketingAdminUser = $this->createUser(isAdmin: true, isMarketing: true);
        $this->buckHillAdmin = User::factory()->create([
            'first_name' => 'BuckHillAdmin',
            'email' => 'admin@buckhill.co.uk',
            'password' => Hash::make('admin'),
            'is_admin' => true,
        ]);

        $payload = [
            'user_id' => $this->buckHillAdmin->uuid,
            'exp' => Carbon::now()->addMinutes(config('app.jwt_max_exp_minutes'))->getTimestamp(),
        ];
        $this->jwtAdminToken = $this->createJWTToken($payload);

        $payload = [
            'user_id' => $this->user->uuid,
            'exp' => Carbon::now()->addMinutes(config('app.jwt_max_exp_minutes'))->getTimestamp(),
        ];
        $this->jwtUserToken = $this->createJWTToken($payload);
    }

    private function createUser(bool $isAdmin = false, bool $isMarketing = false, ...$options): User
    {
        $data = [
            'is_admin' => $isAdmin,
            'is_marketing' => $isMarketing
        ];

        if (!empty($options)) {
            foreach ($options as $key => $optValue) {
                foreach ($optValue as $actKey => $realVal) {
                    $data[$actKey] = $realVal;
                }
            }
        }
        return User::factory()->create($data);
    }

    private function createJWTToken(array $user)
    {
        return TokenHelper::jwtEncode($user);
    }
}
