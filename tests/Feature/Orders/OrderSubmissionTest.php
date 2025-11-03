<?php

namespace Tests\Feature\Orders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

    public function test_legacy_orders_table_is_rebuilt_before_submission(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['name' => 'Legacy Compatible Customer']);
        $product = Product::factory()->create(['name' => 'Legacy Product']);

        Schema::drop('orders');

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->timestamp('received_at')->nullable();
            $table->string('customer_name');
            $table->string('items');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        $legacyCreatedAt = now()->subDay();

        DB::table('orders')->insert([
            'received_at' => $legacyCreatedAt,
            'customer_name' => 'Legacy Compatible Customer',
            'items' => 'Legacy Product × 3',
            'status' => 'pending',
            'created_at' => $legacyCreatedAt,
            'updated_at' => $legacyCreatedAt,
        ]);

        $this->actingAs($user);

        $payload = [
            'order_date' => now()->toDateString(),
            'delivery_date' => now()->addDay()->toDateString(),
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'notes' => 'Modern order',
        ];

        $response = $this->post(route('orders.store'), $payload);

        $response->assertRedirect(route('orders.index'));

        $this->assertFalse(Schema::hasColumn('orders', 'customer_name'));
        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'notes' => 'Modern order',
        ]);

        $this->assertDatabaseHas('orders', [
            'status' => 'pending',
            'created_at' => $legacyCreatedAt,
        ]);
    }

    public function test_user_can_edit_an_existing_order(): void
    {
        $user = User::factory()->create();
        $originalOrder = Order::factory()->create([
            'status' => Order::STATUS_PREPARING,
            'quantity' => 3,
            'notes' => 'Leave at back entrance',
        ]);
        $newCustomer = Customer::factory()->create();
        $newProduct = Product::factory()->create();

        $this->actingAs($user);

        $formResponse = $this->get(route('orders.create', ['order' => $originalOrder->id]));

        $formResponse->assertOk();
        $formResponse->assertSee('value="' . $originalOrder->order_date->format('Y-m-d') . '"', false);
        $formResponse->assertSee('value="' . $originalOrder->customer_id . '" selected', false);

        $updatedOrderDate = now()->addDays(2)->toDateString();
        $updatedDeliveryDate = now()->addDays(5)->toDateString();

        $payload = [
            'order_date' => $updatedOrderDate,
            'delivery_date' => $updatedDeliveryDate,
            'customer_id' => $newCustomer->id,
            'product_id' => $newProduct->id,
            'quantity' => 7,
            'notes' => 'Updated delivery window',
        ];

        $response = $this->put(route('orders.update', $originalOrder), $payload);

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('status', __('messages.mobile_order.flash.updated'));

        $this->assertDatabaseHas('orders', [
            'id' => $originalOrder->id,
            'customer_id' => $newCustomer->id,
            'product_id' => $newProduct->id,
            'quantity' => 7,
            'notes' => 'Updated delivery window',
            'order_date' => $updatedOrderDate,
            'delivery_date' => $updatedDeliveryDate,
            'status' => Order::STATUS_PREPARING,
        ]);
    }
}
