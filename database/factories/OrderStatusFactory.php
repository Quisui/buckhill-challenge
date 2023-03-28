<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderStatus>
 */
class OrderStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $statusTitle = ['open', 'pending_payment', 'paid', 'shipped', 'cancelled'];

        return [
            'title' => $statusTitle[rand(0, 4)],
        ];
    }
}
