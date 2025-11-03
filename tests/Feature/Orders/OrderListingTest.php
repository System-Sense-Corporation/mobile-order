<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class OrderListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_listing_displays_recent_orders(): void
    {
        $user = User::factory()->create();

        $firstCreatedAt = Carbon::parse('2025-11-03 06:30:00');
        $secondCreatedAt = Carbon::parse('2025-11-03 08:20:00');

        $firstOrder = Order::factory()->create([
            'quantity' => 2,
            'status' => 'pending',
            'order_date' => $firstCreatedAt->toDateString(),
            'delivery_date' => $firstCreatedAt->copy()->addDay()->toDateString(),
            'notes' => 'Handle with care',
            'created_at' => $firstCreatedAt,
            'updated_at' => $firstCreatedAt,
        ]);

        $secondOrder = Order::factory()->create([
            'quantity' => 4,
            'status' => 'shipped',
            'order_date' => $secondCreatedAt->toDateString(),
            'delivery_date' => $secondCreatedAt->copy()->addDay()->toDateString(),
            'notes' => null,
            'created_at' => $secondCreatedAt,
            'updated_at' => $secondCreatedAt,
        ]);

        $response = $this->actingAs($user)->get(route('orders.index'));

        $response->assertOk();
        $response->assertSee($firstCreatedAt->format('H:i'));
        $response->assertSee($firstOrder->customer->name);
        $response->assertSee($firstOrder->product->name);
        $response->assertSee((string) $firstOrder->quantity);
        $response->assertSee(__('messages.orders.statuses.pending'));
        $response->assertSee(__('messages.orders.labels.delivery'));
        $response->assertSee($firstOrder->delivery_date->format('Y/m/d'));
        $response->assertSee('Handle with care');

        $response->assertSee($secondCreatedAt->format('H:i'));
        $response->assertSee($secondOrder->customer->name);
        $response->assertSee($secondOrder->product->name);
        $response->assertSee((string) $secondOrder->quantity);
        $response->assertSee(__('messages.orders.statuses.shipped'));
        $response->assertSee($secondOrder->delivery_date->format('Y/m/d'));
        $response->assertSee('name="status"', false);
        $response->assertSee('value="' . Order::STATUS_PREPARING . '"', false);
        $response->assertSee(route('orders.status', $firstOrder), false);
    }

    public function test_user_can_update_order_status_from_listing(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'status' => Order::STATUS_PENDING,
        ]);

        $response = $this->actingAs($user)->patch(route('orders.status', $order), [
            'status' => Order::STATUS_PREPARING,
        ]);

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('status', __('messages.orders.flash.status_updated'));

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PREPARING,
        ]);
    }
}
