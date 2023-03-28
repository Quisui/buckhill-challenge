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

        if (!app()->environment('testing')) {
            $users = User::where('id', rand(1, 100))->first();
            $orderStatus = OrderStatus::all();
            $orderStatusPicked = $orderStatus->random();
            $payment = Payment::where('id', rand(1, 50))->first();

            $statusTitle = ['open' => 'open', 'pending_payment' => 'pending_payment', 'cancelled' => 'cancelled'];
            $products = [];
            for ($i = 0; $i < rand(1, 2); $i++) {
                $product = Product::where('id', rand(1, 500))->first();
                $products[] = [
                    'product' => $product->uuid,
                    'quantity' => rand(1, 5)
                ];
            }
            return [
                'user_id' => $users->uuid,
                'order_status_id' => $orderStatusPicked->uuid,
                'payment_id' => isset($statusTitle[$orderStatusPicked->title]) ? null : $payment->uuid,
                'products' => json_encode($products),
                'address' => null,
                'delivery_fee' => $this->faker->randomFloat(2, 0, 50),
                'amount' => $this->faker->randomFloat(2, 10, 100),
            ];
        }

        return [];
    }
}
