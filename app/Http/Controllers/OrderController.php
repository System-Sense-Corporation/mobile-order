<?php

namespace App\Http\Controllers;

use App\Models\Order;
<<<<<<< HEAD
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_date' => ['required', 'date'],
            'delivery_date' => ['required', 'date', 'after_or_equal:order_date'],
            'customer' => ['required', 'string', 'max:255'],
            'product' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        Order::create($validated);

        return redirect()
            ->route('orders')
            ->with('status', __('messages.mobile_order.flash.success'));
=======
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        /** @var \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection $orders */
        $orders = Order::query()
            ->latest('received_at')
            ->latest()
            ->paginate(10);

        return view('orders', [
            'orders' => $orders,
        ]);
>>>>>>> origin/codex/replace-closure-route-with-controller-action
    }
}
