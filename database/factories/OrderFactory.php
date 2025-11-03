<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * @var class-string<Order>
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $orderDate = Carbon::instance($this->faker->dateTimeBetween('-3 days', 'now'));
        $deliveryDate = (clone $orderDate)->addDays($this->faker->numberBetween(1, 5));

        return [
            'customer_id' => Customer::factory(),
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(1, 20),
            'status' => 'pending',
            'order_date' => $orderDate,
            'delivery_date' => $deliveryDate,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
