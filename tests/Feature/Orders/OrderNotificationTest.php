<?php

namespace Tests\Feature\Orders;

use App\Mail\OrderSubmittedMail;
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
}
