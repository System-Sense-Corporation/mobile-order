<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
    }
}
