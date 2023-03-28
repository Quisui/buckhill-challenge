<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::factory()->create(
            ['title' => 'open']
        );
        OrderStatus::factory()->create(
            ['title' => 'pending_payment']
        );
        OrderStatus::factory()->create(
            ['title' => 'paid'],
        );
        OrderStatus::factory()->create(
            ['title' => 'shipped'],
        );
        OrderStatus::factory()->create(
            ['title' => 'cancelled']
        );
    }
}
