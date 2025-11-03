<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
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
    public function create(Request $request): View
    {
        $this->ensureCodeColumnExists();

        $product = new Product();

        $productId = $request->query('product');

        if ($productId !== null && $productId !== '') {
            $product = Product::query()->findOrFail($productId);
        }

        return view('products.form', [
            'product' => $product,
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

        $code = $product->code ?? '—';

        return redirect()
            ->route('products')
            ->with('status', __('messages.products.flash.saved', [
                'code' => $code,
                'name' => $product->name,
            ]));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->ensureCodeColumnExists();

        $validated = $request->validate(
            [
                'code' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('products', 'code')->ignore($product->getKey()),
                ],
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

        $product->update($validated);

        $code = $product->code ?? '—';

        return redirect()
            ->route('products')
            ->with('status', __('messages.products.flash.updated', [
                'code' => $code,
                'name' => $product->name,
            ]));
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $this->ensureCodeColumnExists();

        $code = $product->code ?? '—';
        $name = $product->name;

        $product->delete();

        return redirect()
            ->route('products')
            ->with('status', __('messages.products.flash.deleted', [
                'code' => $code,
                'name' => $name,
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
