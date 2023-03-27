<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = User::where('id', rand(1, 100))->first();
        $orderStatus = OrderStatus::all();
        $orderStatusPicked = $orderStatus->random();
        $payment = Payment::where('id', rand(1, 50))->first();
        $product = Product::where('id', rand(1, 500))->first();
        $statusTitle = ['open' => 'open', 'pending_payment' => 'pending_payment', 'cancelled' => 'cancelled'];

        return [
            'user_id' => $users->uuid,
            'order_status_id' => $orderStatusPicked->uuid,
            'payment_id' => isset($statusTitle[$orderStatusPicked->title]) ? null : $payment->uuid,
            'products' => json_encode([
                'product' => $product->uuid,
                'quantity' => rand(1, 5)
            ]),
            'address' => null,
            'delivery_fee' => $this->faker->randomFloat(2, 0, 50),
            'amount' => $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
