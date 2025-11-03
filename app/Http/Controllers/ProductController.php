<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display the product listing.
     */
    public function index(): View
    {
        $this->ensureCodeColumnExists();

        $query = Product::query();

        if (Schema::hasColumn($query->getModel()->getTable(), 'code')) {
            $query->orderBy('code');
        } else {
            $query->orderBy('name');
        }

        $products = $query->get();

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
        $this->ensureCodeColumnExists();

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

    /**
     * Ensure the products table contains the code column for legacy databases.
     */
    protected function ensureCodeColumnExists(): void
    {
        if (! Schema::hasTable('products') || Schema::hasColumn('products', 'code')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
        });
    }
}
