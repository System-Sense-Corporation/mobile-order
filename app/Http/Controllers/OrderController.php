<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $orders = collect();

        if (Schema::hasTable('orders')) {
            $orders = Order::query()
                ->with(['customer', 'product'])
                ->orderByDesc('order_date')
                ->orderByDesc('created_at')
                ->get();
        }

        return view('orders', [
            'orders' => $orders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        if (Schema::hasTable('customers')) {
            $this->ensureDemoCustomersInDatabase();
            $customers = Customer::query()->orderBy('name')->get();
            $customersAreDemo = $this->listMatchesDemo(
                $customers->pluck('name'),
                collect($this->demoCustomerDefinitions())->pluck('name')->all()
            );
        } else {
            $customers = $this->sampleCustomers();
            $customersAreDemo = true;
        }

        if (Schema::hasTable('products')) {
            $this->ensureDemoProductsInDatabase();
            $products = Product::query()->orderBy('name')->get();
            $productsAreDemo = $this->listMatchesDemo(
                $products->pluck('name'),
                collect($this->demoProductDefinitions())->pluck('name')->all()
            );
        } else {
            $products = $this->sampleProducts();
            $productsAreDemo = true;
        }

        return view('mobile-order', [
            'customers' => $customers,
            'products' => $products,
            'customersAreDemo' => $customersAreDemo ?? false,
            'productsAreDemo' => $productsAreDemo ?? false,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureDemoCustomersInDatabase();
        $this->ensureDemoProductsInDatabase();

        $data = $request->validate([
            'order_date' => ['required', 'date'],
            'delivery_date' => ['required', 'date', 'after_or_equal:order_date'],
            'customer_id' => ['required', 'exists:customers,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        Order::create([
            'customer_id' => $data['customer_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'order_date' => $data['order_date'],
            'delivery_date' => $data['delivery_date'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('orders')
            ->with('status', __('messages.mobile_order.flash.saved'));
    }

    private function sampleCustomers(): Collection
    {
        return collect($this->demoCustomerDefinitions())
            ->values()
            ->map(static function (array $customer, int $index): object {
                return (object) [
                    'id' => $index + 1,
                    'name' => $customer['name'],
                ];
            });
    }

    private function sampleProducts(): Collection
    {
        return collect($this->demoProductDefinitions())
            ->values()
            ->map(static function (array $product, int $index): object {
                return (object) [
                    'id' => $index + 1,
                    'name' => $product['name'],
                ];
            });
    }

    /**
     * @return array<int, array<string, string|int|null>>
     */
    private function demoCustomerDefinitions(): array
    {
        return [
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
        ];
    }

    /**
     * @return array<int, array<string, string|int|null>>
     */
    private function demoProductDefinitions(): array
    {
        return [
            [
                'name' => '本マグロ 柵 500g',
                'unit' => 'block',
                'price' => 7800,
            ],
            [
                'name' => 'サーモンフィレ 1kg',
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
        ];
    }

    private function ensureDemoCustomersInDatabase(): void
    {
        if (! Schema::hasTable('customers') || Customer::query()->exists()) {
            return;
        }

        foreach ($this->demoCustomerDefinitions() as $customer) {
            Customer::create([
                'name' => $customer['name'],
                'contact' => $customer['contact'] ?? null,
                'contact_person' => $customer['contact_person'] ?? null,
                'notes' => $customer['notes'] ?? null,
            ]);
        }
    }

    private function ensureDemoProductsInDatabase(): void
    {
        if (! Schema::hasTable('products') || Product::query()->exists()) {
            return;
        }

        foreach ($this->demoProductDefinitions() as $product) {
            Product::create([
                'name' => $product['name'],
                'unit' => $product['unit'] ?? null,
                'price' => $product['price'] ?? null,
            ]);
        }
    }

    /**
     * @param  array<int, string>  $demoNames
     */
    private function listMatchesDemo(Collection $names, array $demoNames): bool
    {
        if ($names->isEmpty()) {
            return false;
        }

        return $names
            ->sort()
            ->values()
            ->all() === collect($demoNames)
            ->sort()
            ->values()
            ->all();
    }
}
