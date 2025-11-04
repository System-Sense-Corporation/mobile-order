<?php

namespace App\Http\Controllers;

use App\Mail\OrdersExportMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Support\DemoCustomerData;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

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
            'statusStyles' => $this->statusStyleMap(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->ensureOrdersTableSupportsForm();

        $editingOrder = null;
        $orderId = $request->query('order');

        if ($orderId && Schema::hasTable('orders')) {
            $editingOrder = Order::query()
                ->with(['customer', 'product'])
                ->find($orderId);
        }

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
            'order' => $editingOrder,
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
            'status' => Order::STATUS_PENDING,
        ]);

        return redirect()
            ->route('orders.index')
            ->with('status', __('messages.mobile_order.flash.saved'));
    }

    public function update(Request $request, Order $order): RedirectResponse
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

        $order->update([
            'customer_id' => $data['customer_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'order_date' => $data['order_date'],
            'delivery_date' => $data['delivery_date'],
            'notes' => $data['notes'] ?? null,
        ]);

        return redirect()
            ->route('orders.index')
            ->with('status', __('messages.mobile_order.flash.updated'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        $this->ensureOrdersTableSupportsForm();

        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::allowedStatuses())],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        if ($request->wantsJson()) {
            $styles = $this->statusStyleMap();

            return response()->json([
                'status' => $order->status,
                'label' => __('messages.orders.statuses.' . $order->status),
                'style' => $styles[$order->status] ?? $styles['default'],
                'message' => __('messages.orders.flash.status_updated'),
            ]);
        }

        return redirect()
            ->route('orders.index')
            ->with('status', __('messages.orders.flash.status_updated'));
    }

    public function destroy(Order $order): RedirectResponse
    {
        $this->ensureOrdersTableSupportsForm();

        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('status', __('messages.orders.flash.deleted'));
    }

    public function export(): StreamedResponse
    {
        [$orders, $statusLabels, $filename] = $this->prepareExportData();

        $table = $this->buildExportTable($orders, $statusLabels);

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ];

        return response()->streamDownload(function () use ($table) {
            echo "\xEF\xBB\xBF";
            echo $table;
        }, $filename, $headers);
    }

    public function emailExport(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        [$orders, $statusLabels, $filename] = $this->prepareExportData();

        $content = "\xEF\xBB\xBF" . $this->buildExportTable($orders, $statusLabels);

        try {
            Mail::mailer('failover')->to($validated['email'])->send(new OrdersExportMail($filename, $content));
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('orders.index')
                ->withInput()
                ->withErrors([
                    'email' => __('messages.orders.flash.email_failed'),
                ]);
        }

        return redirect()
            ->route('orders.index')
            ->with('status', __('messages.orders.flash.emailed'));
    }

    /**
     * @return array{Collection<int, Order>, array<string, string>, string}
     */
    protected function prepareExportData(): array
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

        $statusLabels = [
            Order::STATUS_PENDING => __('messages.orders.statuses.pending'),
            Order::STATUS_PREPARING => __('messages.orders.statuses.preparing'),
            Order::STATUS_SHIPPED => __('messages.orders.statuses.shipped'),
        ];

        $filename = 'orders-' . now()->timezone(config('app.timezone'))->format('Ymd-His') . '.xls';

        return [$orders, $statusLabels, $filename];
    }

    /**
     * @param  Collection<int, Order>  $orders
     * @param  array<string, string>  $statusLabels
     */
    protected function buildExportTable(Collection $orders, array $statusLabels): string
    {
        $columns = [
            __('messages.orders.table.time'),
            __('messages.orders.table.customer'),
            __('messages.orders.table.items'),
            __('messages.orders.table.status'),
        ];

        $table = '<table border="1">';
        $table .= '<thead><tr>';

        foreach ($columns as $column) {
            $table .= '<th>' . htmlspecialchars($column, ENT_QUOTES, 'UTF-8') . '</th>';
        }

        $table .= '</tr></thead>';
        $table .= '<tbody>';

        if ($orders->isEmpty()) {
            $table .= '<tr><td colspan="' . count($columns) . '">' . htmlspecialchars(__('messages.orders.empty'), ENT_QUOTES, 'UTF-8') . '</td></tr>';
        } else {
            foreach ($orders as $order) {
                $timestamp = $order->created_at ?? ($order->received_at ? \Illuminate\Support\Carbon::parse($order->received_at) : null);
                $receivedAt = optional($timestamp)?->timezone(config('app.timezone'))->format('H:i');
                $customer = $order->customer?->name ?? $order->customer_name ?? '—';
                $quantity = $order->quantity ?? 1;
                $product = $order->product?->name;
                $items = $product ? $product . ' × ' . number_format($quantity) : ($order->items ?: '—');
                $delivery = optional($order->delivery_date)?->format('Y/m/d');
                $notes = $order->notes;
                $statusKey = $order->status ?? Order::STATUS_PENDING;
                $status = $statusLabels[$statusKey] ?? ucfirst($statusKey);

                $table .= '<tr>';
                $table .= '<td>' . htmlspecialchars($receivedAt ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
                $table .= '<td>' . htmlspecialchars($customer, ENT_QUOTES, 'UTF-8') . '</td>';

                $details = $items;

                if ($delivery) {
                    $details .= "\n" . __('messages.orders.labels.delivery') . ': ' . $delivery;
                }

                if (! empty($notes)) {
                    $details .= "\n" . __('messages.orders.labels.notes') . ': ' . $notes;
                }

                $table .= '<td style="white-space: pre-line">' . htmlspecialchars($details, ENT_QUOTES, 'UTF-8') . '</td>';
                $table .= '<td>' . htmlspecialchars($status, ENT_QUOTES, 'UTF-8') . '</td>';
                $table .= '</tr>';
            }
        }

        $table .= '</tbody>';
        $table .= '</table>';

        return $table;
    }

    private function ensureOrdersTableSupportsForm(): void
    {
        if (! Schema::hasTable('orders')) {
            $this->createOrdersTable();

            return;
        }

        if (Schema::hasColumn('orders', 'customer_name') || Schema::hasColumn('orders', 'items')) {
            $this->rebuildLegacyOrdersTable();

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
                $table->unsignedBigInteger('customer_id')->nullable()->index();
            }

            if ($missing['product_id']) {
                $table->unsignedBigInteger('product_id')->nullable()->index();
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

    /**
     * @return array<string, string>
     */
    private function statusStyleMap(): array
    {
        return [
            Order::STATUS_PENDING => 'bg-amber-100 text-amber-800 ring-amber-200',
            Order::STATUS_PREPARING => 'bg-sky-100 text-sky-800 ring-sky-200',
            Order::STATUS_SHIPPED => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'default' => 'bg-slate-100 text-slate-800 ring-slate-200',
        ];
    }

    private function createOrdersTable(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedInteger('quantity');
            $table->string('status')->default('pending');
            $table->date('order_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    private function rebuildLegacyOrdersTable(): void
    {
        $backupTable = 'orders_legacy_runtime';

        if (Schema::hasTable($backupTable)) {
            Schema::drop($backupTable);
        }

        Schema::rename('orders', $backupTable);

        $this->createOrdersTable();

        $legacyOrders = DB::table($backupTable)->get();

        foreach ($legacyOrders as $legacyOrder) {
            $customerId = property_exists($legacyOrder, 'customer_id') ? $legacyOrder->customer_id : null;

            if ($customerId === null) {
                $customerId = $this->matchCustomerId($legacyOrder->customer_name ?? null);
            }

            $productId = property_exists($legacyOrder, 'product_id') ? $legacyOrder->product_id : null;
            $quantity = property_exists($legacyOrder, 'quantity') ? $legacyOrder->quantity : null;

            if ($productId === null && property_exists($legacyOrder, 'items')) {
                [$matchedProductId, $matchedQuantity] = $this->matchProduct($legacyOrder->items ?? null);
                $productId = $productId ?? $matchedProductId;

                if ($quantity === null && $matchedQuantity !== null) {
                    $quantity = $matchedQuantity;
                }
            }

            DB::table('orders')->insert([
                'customer_id' => $customerId,
                'product_id' => $productId,
                'quantity' => (int) ($quantity ?? 1),
                'status' => $legacyOrder->status ?? 'pending',
                'order_date' => $this->normalizeDate($legacyOrder->order_date ?? $legacyOrder->received_at ?? $legacyOrder->created_at ?? null),
                'delivery_date' => $this->normalizeDate($legacyOrder->delivery_date ?? null),
                'notes' => property_exists($legacyOrder, 'notes') ? $legacyOrder->notes : null,
                'created_at' => $legacyOrder->created_at ?? now(),
                'updated_at' => $legacyOrder->updated_at ?? ($legacyOrder->created_at ?? now()),
            ]);
        }

        Schema::drop($backupTable);
    }

    private function matchCustomerId(?string $customerName): ?int
    {
        if ($customerName === null || $customerName === '' || ! Schema::hasTable('customers')) {
            return null;
        }

        return DB::table('customers')->where('name', $customerName)->value('id');
    }

    /**
     * @return array{0: int|null, 1: int|null}
     */
    private function matchProduct(?string $items): array
    {
        if ($items === null || $items === '' || ! Schema::hasTable('products')) {
            return [null, null];
        }

        $productName = $items;
        $quantity = null;
        $trimmedItems = trim($items);

        if ($trimmedItems !== '' && preg_match('/^(.+?)[\s\x{00D7}xX]+(\d+)$/u', $trimmedItems, $matches) === 1) {
            $productName = trim($matches[1]);
            $quantity = (int) $matches[2];
        }

        $productId = DB::table('products')->where('name', $productName)->value('id');

        return [$productId ?: null, $quantity];
    }

    private function normalizeDate(mixed $value): ?string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            $timestamp = (int) $value;
        } else {
            $timestamp = strtotime((string) $value);
        }

        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d', $timestamp);
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
