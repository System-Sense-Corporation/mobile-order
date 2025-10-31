<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class OrderController extends Controller
{
    public function create(): View
    {
        $customers = Customer::query()->orderBy('name')->get();
        $products = Product::query()->orderBy('code')->get();

        return view('orders.create', compact('customers', 'products'));
    }
}
