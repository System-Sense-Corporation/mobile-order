<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display the product listing.
     */
    public function index(): View
    {
        $products = Product::query()
            ->orderBy('code')
            ->get();

        return view('products', [
            'products' => $products,
        ]);
    }

    /**
     * Show the product creation form.
     */
    public function create(): View
    {
        return view('products.form', [
            'product' => new Product(),
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'code' => ['required', 'string', 'max:50', 'unique:products,code'],
                'name' => ['required', 'string', 'max:255'],
                'unit' => ['nullable', 'string', 'max:100'],
                'price' => ['required', 'integer', 'min:0'],
            ],
            [
                'code.required' => __('messages.products.validation.code.required'),
                'code.unique' => __('messages.products.validation.code.unique'),
                'name.required' => __('messages.products.validation.name.required'),
                'price.required' => __('messages.products.validation.price.required'),
                'price.integer' => __('messages.products.validation.price.integer'),
                'price.min' => __('messages.products.validation.price.min'),
            ]
        );

        $product = Product::create($validated);

        return redirect()
            ->route('products')
            ->with('status', __('messages.products.flash.saved', [
                'code' => $product->code,
                'name' => $product->name,
            ]));
    }
}
