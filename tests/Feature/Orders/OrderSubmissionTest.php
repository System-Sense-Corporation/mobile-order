<?php

namespace Tests\Feature\Orders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_an_order_through_the_form(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user);

        $payload = [
            'order_date' => now()->toDateString(),
            'delivery_date' => now()->addDay()->toDateString(),
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'notes' => 'Keep refrigerated',
        ];

        $response = $this->post(route('orders.store'), $payload);

        $response->assertRedirect(route('orders'));

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'notes' => 'Keep refrigerated',
        ]);
    }

    public function test_submitted_order_is_visible_on_orders_page(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()
            ->for(Customer::factory()->state(['name' => 'Test Bistro']))
            ->for(Product::factory()->state(['name' => 'Premium Salmon']))
            ->create([
                'quantity' => 3,
                'order_date' => now()->toDateString(),
                'delivery_date' => now()->addDays(2)->toDateString(),
                'notes' => 'Deliver before noon',
            ]);

        $response = $this->actingAs($user)->get(route('orders'));

        $response->assertOk();
        $response->assertSee('Test Bistro');
        $response->assertSee('Premium Salmon');
        $response->assertSee($order->order_date->format('Y-m-d'));
        $response->assertSee('× ' . number_format($order->quantity));
        $response->assertSee('Deliver before noon');
    }
}
