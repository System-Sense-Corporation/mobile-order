<?php

namespace Database\Seeders;

use App\Models\Customer;
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
        $this->seedUsers();
        $this->seedCustomers();
        $this->seedProducts();
    }

    private function seedUsers(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    private function seedCustomers(): void
    {
        $customers = collect([
            [
                'name' => '鮮魚酒場 波しぶき',
                'contact_person' => '山田様',
                'contact_phone' => '03-1234-5678',
                'note' => '毎朝8時納品',
            ],
            [
                'name' => 'レストラン 潮彩',
                'contact_person' => '佐藤シェフ',
                'contact_phone' => '045-432-1111',
                'note' => '高級白身魚を希望',
            ],
            [
                'name' => 'ホテル ブルーサンズ',
                'contact_person' => '購買部 三浦様',
                'contact_phone' => '0467-222-0099',
                'note' => '大量注文あり',
            ],
            [
                'name' => '旬彩料理 こはる',
                'contact_person' => '小春店主',
                'contact_phone' => '03-9988-7766',
                'note' => '土曜は臨時休業あり',
            ],
        ]);

        $customers->each(function (array $attributes) {
            Customer::query()->updateOrCreate(
                ['name' => $attributes['name']],
                $attributes
            );
        });
    }

    private function seedProducts(): void
    {
        $products = collect([
            [
                'code' => 'P-1001',
                'name' => '本マグロ 柵 500g',
                'unit' => '柵',
                'price' => 4500,
            ],
            [
                'code' => 'P-1002',
                'name' => 'サーモン フィレ 1kg',
                'unit' => 'パック',
                'price' => 3200,
            ],
            [
                'code' => 'P-1003',
                'name' => 'ボタンエビ 20尾',
                'unit' => 'ケース',
                'price' => 5800,
            ],
            [
                'code' => 'P-1004',
                'name' => '真鯛 1尾 (約1.5kg)',
                'unit' => '尾',
                'price' => 2400,
            ],
            [
                'code' => 'P-1005',
                'name' => 'アジ 開き 10枚',
                'unit' => 'セット',
                'price' => 1200,
            ],
        ]);

        $products->each(function (array $attributes) {
            Product::query()->updateOrCreate(
                ['code' => $attributes['code']],
                $attributes
            );
        });
    }
}
