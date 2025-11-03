<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Support\DemoCustomerData;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders that have been submitted.
     */
    public function index(): View
    {
        $this->ensureOrdersTableSupportsForm();

        if (! Schema::hasTable('orders')) {
            $orders = collect();
        } else {
            $orders = Order::query()
                ->with(['customer', 'product'])
                ->orderBy('created_at')
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
            DemoCustomerData::ensureInDatabase();
            $customers = Customer::query()->orderBy('name')->get();
            $customersAreDemo = DemoCustomerData::namesMatch($customers->pluck('name'));
        } else {
            $customers = DemoCustomerData::sample();
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
        $this->ensureOrdersTableSupportsForm();

        DemoCustomerData::ensureInDatabase();
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
            ->route('orders.index')
            ->with('status', __('messages.mobile_order.flash.saved'));
    }

    private function ensureOrdersTableSupportsForm(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        $missing = [
            'customer_id' => ! Schema::hasColumn('orders', 'customer_id'),
            'product_id' => ! Schema::hasColumn('orders', 'product_id'),
            'quantity' => ! Schema::hasColumn('orders', 'quantity'),
            'status' => ! Schema::hasColumn('orders', 'status'),
            'order_date' => ! Schema::hasColumn('orders', 'order_date'),
            'delivery_date' => ! Schema::hasColumn('orders', 'delivery_date'),
            'notes' => ! Schema::hasColumn('orders', 'notes'),
        ];

        if (! in_array(true, $missing, true)) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) use ($missing): void {
            if ($missing['customer_id']) {
                $table->unsignedBigInteger('customer_id')->nullable();
            }

            if ($missing['product_id']) {
                $table->unsignedBigInteger('product_id')->nullable();
            }

            if ($missing['quantity']) {
                $table->unsignedInteger('quantity')->default(1);
            }

            if ($missing['status']) {
                $table->string('status')->default('pending');
            }

            if ($missing['order_date']) {
                $table->date('order_date')->nullable();
            }

            if ($missing['delivery_date']) {
                $table->date('delivery_date')->nullable();
            }

            if ($missing['notes']) {
                $table->text('notes')->nullable();
            }
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
