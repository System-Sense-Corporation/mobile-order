<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @var class-string<Product>
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'unit' => $this->faker->randomElement(['kg', 'pack', 'piece', 'box']),
            'price' => $this->faker->numberBetween(500, 15000),
        ];
    }
}
