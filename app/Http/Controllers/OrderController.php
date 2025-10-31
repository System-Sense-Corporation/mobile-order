<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $orders = Order::query()
            ->with(['customer', 'product'])
            ->latest()
            ->get();

        return view('orders', [
            'orders' => $orders,
        ]);
    }
}
