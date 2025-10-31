<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Order;
use Illuminate\Contracts\View\View;
=======
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
>>>>>>> origin/codex/implement-orders-index-action-and-view-update-tyzbpj

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
<<<<<<< HEAD
        $orders = Order::query()
            ->with(['customer', 'product'])
            ->latest()
            ->get();
=======
        $orders = collect();

        if (Schema::hasTable('orders')) {
            $orders = Order::query()
                ->with(['customer', 'product'])
                ->orderByDesc('order_date')
                ->orderByDesc('created_at')
                ->get();
        }
>>>>>>> origin/codex/implement-orders-index-action-and-view-update-tyzbpj

        return view('orders', [
            'orders' => $orders,
        ]);
    }
<<<<<<< HEAD
=======

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $customers = collect();
        $products = collect();

        if (Schema::hasTable('customers')) {
            $customers = Customer::query()->orderBy('name')->get();
        }

        if (Schema::hasTable('products')) {
            $products = Product::query()->orderBy('name')->get();
        }

        return view('mobile-order', [
            'customers' => $customers,
            'products' => $products,
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
>>>>>>> origin/codex/implement-orders-index-action-and-view-update-tyzbpj
}
