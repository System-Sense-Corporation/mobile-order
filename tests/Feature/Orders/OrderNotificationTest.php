<?php

namespace Tests\Feature\Orders;

use App\Mail\OrderDeletedMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\OrderSubmittedMail;
use App\Mail\OrderUpdatedMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_is_sent_when_setting_contains_valid_email(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        Setting::create([
            'key' => 'order_notification_email',
            'value' => ' notify@example.com ',
        ]);

        $this->actingAs($user);

        $payload = [
            'order_date' => now()->toDateString(),
            'delivery_date' => now()->addDay()->toDateString(),
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 4,
            'notes' => 'Handle with care',
        ];

        $response = $this->post(route('orders.store'), $payload);

        $response->assertRedirect(route('orders.index'));

        $createdOrder = Order::first();
        $this->assertNotNull($createdOrder);

        Mail::assertSent(OrderSubmittedMail::class, function (OrderSubmittedMail $mail) use ($createdOrder) {
            return $mail->hasTo('notify@example.com') && $mail->order->is($createdOrder);
        });

        Mail::assertSentTimes(OrderSubmittedMail::class, 1);
    }

    /**
     * @param string|null $value
     * @dataProvider invalidNotificationEmailProvider
     */
    public function test_notification_is_not_sent_when_setting_is_blank_or_invalid(?string $value): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        if ($value !== null) {
            Setting::create([
                'key' => 'order_notification_email',
                'value' => $value,
            ]);
        }

        $this->actingAs($user);

        $payload = [
            'order_date' => now()->toDateString(),
            'delivery_date' => now()->addDay()->toDateString(),
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'notes' => null,
        ];

        $response = $this->post(route('orders.store'), $payload);

        $response->assertRedirect(route('orders.index'));

        Mail::assertNothingSent();
    }

    public function test_notification_is_sent_when_order_is_updated(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        Setting::create([
            'key' => 'order_notification_email',
            'value' => 'notify@example.com',
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'notes' => 'Initial note',
            'order_date' => now()->subDay(),
            'delivery_date' => now()->addDays(2),
        ]);

        $this->actingAs($user);

        $payload = [
            'order_date' => $order->order_date->copy()->addDay()->toDateString(),
            'delivery_date' => $order->delivery_date->copy()->addDay()->toDateString(),
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'notes' => 'Updated note',
        ];

        $response = $this->put(route('orders.update', $order), $payload);

        $response->assertRedirect(route('orders.index'));

        $order->refresh();

        Mail::assertSent(OrderUpdatedMail::class, function (OrderUpdatedMail $mail) use ($order) {
            return $mail->hasTo('notify@example.com') && $mail->order->is($order);
        });

        Mail::assertSentTimes(OrderUpdatedMail::class, 1);
    }

    /**
     * @param  string  $targetStatus
     * @param  string  $startingStatus
     * @dataProvider statusNotificationProvider
     */
    public function test_notification_is_sent_when_order_status_changes(string $targetStatus, string $startingStatus): void
    {
        Mail::fake();

        $user = User::factory()->create();

        Setting::create([
            'key' => 'order_notification_email',
            'value' => 'notify@example.com',
        ]);

        $order = Order::factory()->create([
            'status' => $startingStatus,
        ]);

        $this->actingAs($user);

        $response = $this->patch(route('orders.status', $order), [
            'status' => $targetStatus,
        ]);

        $response->assertRedirect(route('orders.index'));

        $order->refresh();
        $this->assertSame($targetStatus, $order->status);

        Mail::assertSent(OrderStatusUpdatedMail::class, function (OrderStatusUpdatedMail $mail) use ($order) {
            return $mail->hasTo('notify@example.com') && $mail->order->is($order);
        });

        Mail::assertSentTimes(OrderStatusUpdatedMail::class, 1);
    }

    public function test_notification_is_sent_when_order_is_deleted(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        Setting::create([
            'key' => 'order_notification_email',
            'value' => 'notify@example.com',
        ]);

        $order = Order::factory()->create();

        $this->actingAs($user);

        $response = $this->delete(route('orders.destroy', $order));

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);

        Mail::assertSent(OrderDeletedMail::class, function (OrderDeletedMail $mail) use ($order) {
            return $mail->hasTo('notify@example.com') && $mail->order->getKey() === $order->getKey();
        });

        Mail::assertSentTimes(OrderDeletedMail::class, 1);
    }

    /**
     * @return array<string, array{0: string|null}>
     */
    public static function invalidNotificationEmailProvider(): array
    {
        return [
            'blank string' => ['   '],
            'empty string' => [''],
            'invalid format' => ['invalid-email'],
            'missing setting' => [null],
        ];
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function statusNotificationProvider(): array
    {
        return [
            'to pending' => [Order::STATUS_PENDING, Order::STATUS_PREPARING],
            'to preparing' => [Order::STATUS_PREPARING, Order::STATUS_PENDING],
            'to shipped' => [Order::STATUS_SHIPPED, Order::STATUS_PREPARING],
        ];
    }
}
