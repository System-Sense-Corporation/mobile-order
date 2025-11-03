<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            $this->createOrdersTable();

            return;
        }

        $requiredColumns = [
            'customer_id',
            'product_id',
            'quantity',
            'status',
            'order_date',
            'delivery_date',
            'notes',
        ];

        $missingColumns = array_values(array_filter(
            $requiredColumns,
            static fn (string $column): bool => ! Schema::hasColumn('orders', $column)
        ));

        if ($missingColumns === []) {
            return;
        }

        $missingLookup = array_flip($missingColumns);

        if (! isset($missingLookup['customer_id']) && ! isset($missingLookup['product_id'])) {
            Schema::table('orders', function (Blueprint $table) use ($missingLookup): void {
                if (isset($missingLookup['quantity'])) {
                    $table->unsignedInteger('quantity')->default(1)->after('product_id');
                }

                if (isset($missingLookup['status'])) {
                    $table->string('status')->default('pending')->after('quantity');
                }

                if (isset($missingLookup['order_date'])) {
                    $table->date('order_date')->nullable()->after('status');
                }

                if (isset($missingLookup['delivery_date'])) {
                    $table->date('delivery_date')->nullable()->after('order_date');
                }

                if (isset($missingLookup['notes'])) {
                    $table->text('notes')->nullable()->after('delivery_date');
                }
            });

            return;
        }

        if (Schema::hasTable('orders_legacy')) {
            Schema::drop('orders_legacy');
        }

        Schema::rename('orders', 'orders_legacy');

        $this->createOrdersTable();

        $legacyOrders = DB::table('orders_legacy')->get();

        foreach ($legacyOrders as $legacyOrder) {
            $customerId = $this->matchCustomerId($legacyOrder->customer_name ?? null);
            [$productId, $quantity] = $this->matchProduct($legacyOrder->items ?? null);

            DB::table('orders')->insert([
                'customer_id' => $customerId,
                'product_id' => $productId,
                'quantity' => $quantity ?? 1,
                'status' => $legacyOrder->status ?? 'pending',
                'order_date' => $this->normalizeDate($legacyOrder->received_at ?? $legacyOrder->created_at ?? null),
                'delivery_date' => null,
                'notes' => null,
                'created_at' => $legacyOrder->created_at,
                'updated_at' => $legacyOrder->updated_at,
            ]);
        }

        Schema::drop('orders_legacy');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        Schema::drop('orders');

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->timestamp('received_at')->nullable()->index();
            $table->string('customer_name');
            $table->string('items');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    private function createOrdersTable(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('status')->default('pending');
            $table->date('order_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    private function matchCustomerId(?string $customerName): ?int
    {
        if ($customerName === null || $customerName === '') {
            return null;
        }

        return DB::table('customers')->where('name', $customerName)->value('id');
    }

    /**
     * @return array{0: int|null, 1: int|null}
     */
    private function matchProduct(?string $items): array
    {
        if ($items === null || $items === '') {
            return [null, null];
        }

        $productName = $items;
        $quantity = null;

        if (preg_match('/^(.+?)[\s\x{00D7}xX]+(\d+)$/u', trim($items), $matches) === 1) {
            $productName = trim($matches[1]);
            $quantity = (int) $matches[2];
        }

        $productId = DB::table('products')->where('name', $productName)->value('id');

        return [$productId, $quantity];
    }

    private function normalizeDate(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $timestamp = strtotime($value);

        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d', $timestamp);
    }
};
