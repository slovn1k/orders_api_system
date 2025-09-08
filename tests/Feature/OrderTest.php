<?php

namespace Tests\Feature;

use App\Events\OrderStatusChange;
use App\Models\Order;
use App\Models\Tag;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrderTest extends TestCase
{

    public function test_create_order()
    {
        $response = $this->postJson('/api/orders', [
            'order_number' => 'Order1',
            'status' => 'pending',
            'total_amount' => 12.23,
            'tags' => ['vip'],
            'items' => [
                ['product_name' => 'Product 1', 'quantity' => 1, 'price' => 10.10],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['order_number', 'status', 'total_amount', 'tags', 'items']);
        $this->assertDatabaseHas('orders', ['order_number' => 'Order1', 'status' => 'pending']);
    }

    public function test_create_order_invalid()
    {
        $response = $this->postJson('/api/orders', [
            'order_number' => '',
            'total_amount' => -1,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['order_number', 'total_amount']);
    }

    public function test_update_order()
    {
        $order = Order::create(['order_number' => 'Order1', 'status' => 'pending', 'total_amount' => 12.12]);
        $this->expectsEvents(OrderStatusChange::class);

        $response = $this->putJson("/api/orders/{$order->order_number}", [
            'status' => 'canceled',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['order_number' => 'Order1', 'status' => 'canceled']);
    }

    public function api_test()
    {
        Http::fake([
            'https://fake.com/*' => Http::response(['status' => 'success'], 200),
        ]);

        $order = Order::create(['order_number' => 'Order5', 'status' => 'pending', 'total_amount' => 100.00]);
        $response = $this->putJson("/api/orders/{$order->order_number}", ['status' => 'shipped']);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', ['status' => 'shipped']);
    }

    public function api_failure()
    {
        Http::fake([
            'https://fake.com/*' => Http::response(['error' => 'API failure'], 500),
        ]);

        $order = Order::create(['order_number' => 'Order5', 'status' => 'pending', 'total_amount' => 1000.00]);
        $response = $this->putJson("/api/orders/{$order->order_number}", ['status' => 'shipped']);

        $response->assertStatus(500);
        $this->assertDatabaseMissing('orders', ['status' => 'shipped']);
    }
}
