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
        $hasCustomerData = Schema::hasTable('customers') && Customer::query()->exists();
        $customers = $hasCustomerData
            ? Customer::query()->orderBy('name')->get()
            : $this->sampleCustomers();

        $hasProductData = Schema::hasTable('products') && Product::query()->exists();
        $products = $hasProductData
            ? Product::query()->orderBy('name')->get()
            : $this->sampleProducts();

        return view('mobile-order', [
            'customers' => $customers,
            'products' => $products,
            'customersAreDemo' => ! $hasCustomerData,
            'productsAreDemo' => ! $hasProductData,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
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
        return collect([
            ['id' => 1, 'name' => '鮮魚酒場 波しぶき'],
            ['id' => 2, 'name' => 'レストラン 潮彩'],
            ['id' => 3, 'name' => 'ホテル ブルーサンズ'],
            ['id' => 4, 'name' => 'ホテル グリーンズ'],
        ])->map(static fn (array $customer): object => (object) $customer);
    }

    private function sampleProducts(): Collection
    {
        return collect([
            ['id' => 1, 'name' => '本マグロ 柵 500g'],
            ['id' => 2, 'name' => 'サーモンフィレ 1kg'],
            ['id' => 3, 'name' => 'ボタンエビ 20尾'],
            ['id' => 4, 'name' => '真鯛 1尾'],
        ])->map(static fn (array $product): object => (object) $product);
    }
}
