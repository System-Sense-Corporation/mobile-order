<?php

namespace App\Support;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class DemoCustomerData
{
    /**
     * @return array<int, array<string, string|null>>
     */
    public static function definitions(): array
    {
        return [
            [
                'name' => '鮮魚酒場 波しぶき',
                'contact' => '03-1234-5678',
                'contact_person' => '山田様',
                'notes' => 'Deliver every morning at 8:00',
                'notes_translation_key' => 'messages.customers.notes.wave',
            ],
            [
                'name' => 'レストラン 潮彩',
                'contact' => '045-432-1111',
                'contact_person' => '佐藤シェフ',
                'notes' => 'Prefers premium white fish',
                'notes_translation_key' => 'messages.customers.notes.shiosai',
            ],
            [
                'name' => 'ホテル ブルーサンズ',
                'contact' => '0467-222-0099',
                'contact_person' => '購買部 三浦様',
                'notes' => 'Places bulk orders regularly',
                'notes_translation_key' => 'messages.customers.notes.blue_sands',
            ],
        ];
    }

    public static function ensureInDatabase(): void
    {
        if (! Schema::hasTable('customers') || Customer::query()->exists()) {
            return;
        }

        foreach (self::definitions() as $customer) {
            Customer::create([
                'name' => $customer['name'],
                'contact' => $customer['contact'] ?? null,
                'contact_person' => $customer['contact_person'] ?? null,
                'notes' => $customer['notes'] ?? null,
            ]);
        }
    }

    public static function sample(): Collection
    {
        return collect(self::definitions())
            ->values()
            ->map(static function (array $customer, int $index): object {
                $customerData = $customer;
                $customerData['notes'] = isset($customer['notes_translation_key'])
                    ? __($customer['notes_translation_key'])
                    : ($customer['notes'] ?? null);

                unset($customerData['notes_translation_key']);

                return (object) array_merge($customerData, [
                    'id' => $index + 1,
                ]);
            });
    }

    public static function namesMatch(Collection $names): bool
    {
        if ($names->isEmpty()) {
            return false;
        }

        return $names
            ->sort()
            ->values()
            ->all() === collect(self::definitions())
            ->pluck('name')
            ->sort()
            ->values()
            ->all();
    }
}
