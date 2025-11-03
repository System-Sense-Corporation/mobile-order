<?php

namespace Tests\Feature\Orders;

use App\Models\Customer;
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

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('status', __('messages.mobile_order.flash.saved'));

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'notes' => 'Keep refrigerated',
        ]);
    }

}
