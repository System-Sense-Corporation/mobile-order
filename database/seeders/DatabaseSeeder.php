<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $customers = collect([
            [
                'name' => '鮮魚酒場 波しぶき',
                'contact' => '03-1234-5678',
                'contact_person' => '山田様',
                'notes' => 'Deliver every morning at 8:00',
            ],
            [
                'name' => 'レストラン 潮彩',
                'contact' => '045-432-1111',
                'contact_person' => '佐藤シェフ',
                'notes' => 'Prefers premium white fish',
            ],
            [
                'name' => 'ホテル ブルーサンズ',
                'contact' => '0467-222-0099',
                'contact_person' => '購買部 三浦様',
                'notes' => 'Places bulk orders regularly',
            ],
            [
                'name' => '旬彩料理 こはる',
                'contact' => '03-9988-7766',
                'contact_person' => '小春店主',
                'notes' => 'Occasionally closed on Saturdays',
            ],
        ])->mapWithKeys(function (array $customer) {
            $model = Customer::create($customer);

            return [$model->name => $model];
        });

        $products = collect([
            [
                'name' => '本マグロ 柵 500g',
                'unit' => 'block',
                'price' => 7800,
            ],
            [
                'name' => 'サーモン フィレ 1kg',
                'unit' => 'fillet',
                'price' => 4200,
            ],
            [
                'name' => 'ボタンエビ 20尾',
                'unit' => 'pack',
                'price' => 5600,
            ],
            [
                'name' => '真鯛 1尾',
                'unit' => 'whole fish',
                'price' => 3200,
            ],
        ])->mapWithKeys(function (array $product) {
            $model = Product::create($product);

            return [$model->name => $model];
        });

<<<<<<< HEAD
        $orders = [
            ['customer' => '鮮魚酒場 波しぶき', 'product' => '本マグロ 柵 500g', 'quantity' => 2, 'status' => 'pending'],
            ['customer' => 'レストラン 潮彩', 'product' => 'サーモン フィレ 1kg', 'quantity' => 5, 'status' => 'preparing'],
            ['customer' => 'ホテル ブルーサンズ', 'product' => 'ボタンエビ 20尾', 'quantity' => 3, 'status' => 'shipped'],
            ['customer' => '旬彩料理 こはる', 'product' => '真鯛 1尾', 'quantity' => 4, 'status' => 'pending'],
=======
        $today = now();

        $orders = [
            [
                'customer' => '鮮魚酒場 波しぶき',
                'product' => '本マグロ 柵 500g',
                'quantity' => 2,
                'status' => 'pending',
                'order_date' => $today->copy()->subDay()->toDateString(),
                'delivery_date' => $today->copy()->addDay()->toDateString(),
                'notes' => 'Deliver before noon – sashimi grade required.',
            ],
            [
                'customer' => 'レストラン 潮彩',
                'product' => 'サーモン フィレ 1kg',
                'quantity' => 5,
                'status' => 'preparing',
                'order_date' => $today->copy()->subDays(2)->toDateString(),
                'delivery_date' => $today->copy()->addDays(2)->toDateString(),
                'notes' => 'Slice into 200g portions before delivery.',
            ],
            [
                'customer' => 'ホテル ブルーサンズ',
                'product' => 'ボタンエビ 20尾',
                'quantity' => 3,
                'status' => 'shipped',
                'order_date' => $today->copy()->subDays(3)->toDateString(),
                'delivery_date' => $today->copy()->addDay()->toDateString(),
                'notes' => 'Pack with extra ice packs.',
            ],
            [
                'customer' => '旬彩料理 こはる',
                'product' => '真鯛 1尾',
                'quantity' => 4,
                'status' => 'pending',
                'order_date' => $today->toDateString(),
                'delivery_date' => $today->copy()->addDays(3)->toDateString(),
                'notes' => 'Whole fish, scales removed.',
            ],
>>>>>>> origin/codex/implement-orders-index-action-and-view-update-tyzbpj
        ];

        foreach ($orders as $order) {
            $customer = $customers[$order['customer']] ?? null;
            $product = $products[$order['product']] ?? null;

            if (! $customer || ! $product) {
                continue;
            }

            Order::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'quantity' => $order['quantity'],
                'status' => $order['status'],
<<<<<<< HEAD
=======
                'order_date' => $order['order_date'],
                'delivery_date' => $order['delivery_date'],
                'notes' => $order['notes'],
>>>>>>> origin/codex/implement-orders-index-action-and-view-update-tyzbpj
            ]);
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
