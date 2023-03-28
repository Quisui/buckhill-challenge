<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = [
            'credit_card',
            'cash_on_delivery',
            'bank_transfer',
        ];
        return [
            'type' => $type[rand(0, 2)],
            'details' => null
        ];
    }
}
