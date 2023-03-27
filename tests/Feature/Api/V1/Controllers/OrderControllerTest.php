<?php

namespace Tests\Feature\Api\V1\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Database\Factories\PaymentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertJsonStringNotEqualsJsonString;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    public Order $userOrder;
    public Order $johnOrder;
    public User $john;
    public OrderStatus $orderStatus;
    public Product $product;

    public function orderControllerTestDefaultFactorySetup(): void
    {
        $orderStatus = OrderStatus::factory()->create(['title' => 'created']);
        $payment = Payment::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create();
        $this->product = $product;

        $this->userOrder = Order::factory()->create([
            'user_id' => $this->user->uuid,
            'order_status_id' => $orderStatus->uuid,
            'payment_id' => null,
            'products' => json_encode([$product]),
            'address' => null,
            'delivery_fee' => $this->faker->randomFloat(2, 0, 50),
            'amount' => $this->faker->randomFloat(2, 10, 100),
        ]);

        $john = User::factory()->create();
        $product = Product::factory()->create();
        $this->johnOrder = Order::factory()->create([
            'user_id' => $john->uuid,
            'order_status_id' => $orderStatus->uuid,
            'payment_id' => null,
            'products' => json_encode([$product]),
            'address' => json_encode([]),
            'delivery_fee' => $this->faker->randomFloat(2, 0, 50),
            'amount' => $this->faker->randomFloat(2, 10, 100),
        ]);

        $this->orderStatus = $orderStatus;
    }

    public function testUserCanGetTheirOwnOrders()
    {
        $this->orderControllerTestDefaultFactorySetup();
        $response = $this->actingAs($this->user)->getJson('/api/v1/order',  [
            'Accept' => "application/json",
            'Authorization' => 'Bearer ' . $this->jwtUserToken
        ])
            ->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.user_id', $this->userOrder->user_id);
        $this->assertNotEquals($this->johnOrder->toArray(), $response->json()['data'][0]);
    }

    public function testUserCanGetSpecificOrder()
    {
        $this->orderControllerTestDefaultFactorySetup();
        $this->actingAs($this->user)->getJson('/api/v1/order/' . $this->userOrder->uuid,  [
            'Accept' => "application/json",
            'Authorization' => 'Bearer ' . $this->jwtUserToken
        ])
            ->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonPath('data.user_id', $this->userOrder->user_id);
    }

    public function testUserCannotGetOtherUserOrder()
    {
        $this->orderControllerTestDefaultFactorySetup();
        $this->actingAs($this->user)->getJson('/api/v1/order/' . $this->johnOrder->uuid,  [
            'Accept' => "application/json",
            'Authorization' => 'Bearer ' . $this->jwtUserToken
        ])
            ->assertStatus(404);
    }

    public function testUserCanCreateNewOrder()
    {
        $this->orderControllerTestDefaultFactorySetup();
        $data = [
            "order_status_id"  =>  $this->orderStatus->uuid,
            'user_id' => $this->user->uuid,
            "products"  =>  [
                $this->product->toArray()
            ],
            'delivery_fee' => $this->faker->randomFloat(2, 0, 50),
            'amount' => $this->faker->randomFloat(2, 10, 100),
        ];
        $this->actingAs($this->user)->postJson('/api/v1/order', $data, [
            'Accept' => "application/json",
            'Authorization' => 'Bearer ' . $this->jwtUserToken
        ])
            ->assertStatus(201)
            ->assertJsonStructure(['data'])
            ->assertJsonFragment(['user_id' => $data['user_id']])
            ->assertJsonFragment(['order_status' => $this->orderStatus->title]);
    }
}
