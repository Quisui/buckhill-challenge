<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(100)->create(['password' => Hash::make('userpassword')]);
        User::factory()->create([
            'first_name' => 'BuckHillAdmin',
            'email' => 'admin@buckhill.co.uk',
            'password' => Hash::make('admin'),
            'is_admin' => true,
        ]);
    }
}
