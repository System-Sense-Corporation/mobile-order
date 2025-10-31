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
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->unsignedInteger('quantity');
                $table->string('status')->default('pending');
                $table->date('order_date');
                $table->date('delivery_date');
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            return;
        }

        $missingCustomerId = ! Schema::hasColumn('orders', 'customer_id');
        $missingProductId = ! Schema::hasColumn('orders', 'product_id');
        $missingQuantity = ! Schema::hasColumn('orders', 'quantity');
        $missingStatus = ! Schema::hasColumn('orders', 'status');
        $missingOrderDate = ! Schema::hasColumn('orders', 'order_date');
        $missingDeliveryDate = ! Schema::hasColumn('orders', 'delivery_date');
        $missingNotes = ! Schema::hasColumn('orders', 'notes');
        $hasCustomerName = Schema::hasColumn('orders', 'customer');
        $hasProductName = Schema::hasColumn('orders', 'product');
        $hasLegacyCustomerName = Schema::hasColumn('orders', 'customer_name');
        $hasLegacyItems = Schema::hasColumn('orders', 'items');
        $hasLegacyReceivedAt = Schema::hasColumn('orders', 'received_at');

        if (
            $missingCustomerId
            || $missingProductId
            || $missingQuantity
            || $missingStatus
            || $missingOrderDate
            || $missingDeliveryDate
            || $missingNotes
        ) {
            $driver = Schema::getConnection()->getDriverName();
            $supportsInlineForeignKeys = $driver !== 'sqlite';

            Schema::table('orders', function (Blueprint $table) use (
                $missingCustomerId,
                $missingProductId,
                $missingQuantity,
                $missingStatus,
                $missingOrderDate,
                $missingDeliveryDate,
                $missingNotes,
                $supportsInlineForeignKeys
            ) {
                if ($missingCustomerId) {
                    $table->unsignedBigInteger('customer_id')
                        ->nullable()
                        ->after('id');

                    if ($supportsInlineForeignKeys) {
                        $table->foreign('customer_id')
                            ->references('id')
                            ->on('customers')
                            ->cascadeOnDelete();
                    }
                }

                if ($missingProductId) {
                    $table->unsignedBigInteger('product_id')
                        ->nullable()
                        ->after('customer_id');

                    if ($supportsInlineForeignKeys) {
                        $table->foreign('product_id')
                            ->references('id')
                            ->on('products')
                            ->cascadeOnDelete();
                    }
                }

                if ($missingQuantity) {
                    $table->unsignedInteger('quantity')
                        ->default(1)
                        ->after('product_id');
                }

                if ($missingStatus) {
                    $table->string('status')
                        ->default('pending')
                        ->after('quantity');
                }

                if ($missingOrderDate) {
                    $table->date('order_date')
                        ->nullable()
                        ->after('status');
                }

                if ($missingDeliveryDate) {
                    $table->date('delivery_date')
                        ->nullable()
                        ->after('order_date');
                }

                if ($missingNotes) {
                    $table->text('notes')
                        ->nullable()
                        ->after('delivery_date');
                }
            });
        }

        if ($hasCustomerName || $hasProductName || $hasLegacyCustomerName || $hasLegacyItems || $hasLegacyReceivedAt) {
            Schema::table('orders', function (Blueprint $table) use (
                $hasCustomerName,
                $hasProductName,
                $hasLegacyCustomerName,
                $hasLegacyItems,
                $hasLegacyReceivedAt
            ) {
                if ($hasCustomerName) {
                    $table->dropColumn('customer');
                }

                if ($hasProductName) {
                    $table->dropColumn('product');
                }

                if ($hasLegacyCustomerName) {
                    $table->dropColumn('customer_name');
                }

                if ($hasLegacyItems) {
                    $table->dropColumn('items');
                }

                if ($hasLegacyReceivedAt) {
                    $table->dropColumn('received_at');
                }
            });
        }

        if ($missingStatus) {
            DB::table('orders')->whereNull('status')->update(['status' => 'pending']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('orders')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();
        $usesSimpleForeignColumns = $driver === 'sqlite';

        $needsCustomerColumn = ! Schema::hasColumn('orders', 'customer');
        $needsProductColumn = ! Schema::hasColumn('orders', 'product');
        $hasCustomerId = Schema::hasColumn('orders', 'customer_id');
        $hasProductId = Schema::hasColumn('orders', 'product_id');
        $hasQuantity = Schema::hasColumn('orders', 'quantity');
        $hasStatus = Schema::hasColumn('orders', 'status');
        $hasOrderDate = Schema::hasColumn('orders', 'order_date');
        $hasDeliveryDate = Schema::hasColumn('orders', 'delivery_date');
        $hasNotes = Schema::hasColumn('orders', 'notes');
        $needsLegacyReceivedAt = ! Schema::hasColumn('orders', 'received_at');
        $needsLegacyCustomerName = ! Schema::hasColumn('orders', 'customer_name');
        $needsLegacyItems = ! Schema::hasColumn('orders', 'items');

        if ($needsCustomerColumn || $needsProductColumn || $needsLegacyCustomerName || $needsLegacyItems || $needsLegacyReceivedAt) {
            Schema::table('orders', function (Blueprint $table) use (
                $needsCustomerColumn,
                $needsProductColumn,
                $needsLegacyCustomerName,
                $needsLegacyItems,
                $needsLegacyReceivedAt
            ) {
                if ($needsCustomerColumn) {
                    $table->string('customer')->nullable();
                }

                if ($needsProductColumn) {
                    $table->string('product')->nullable();
                }

                if ($needsLegacyCustomerName) {
                    $table->string('customer_name')->nullable();
                }

                if ($needsLegacyItems) {
                    $table->string('items')->nullable();
                }

                if ($needsLegacyReceivedAt) {
                    $table->timestamp('received_at')->nullable();
                }
            });
        }

        if ($hasCustomerId || $hasProductId || $hasQuantity || $hasStatus || $hasOrderDate || $hasDeliveryDate || $hasNotes) {
            Schema::table('orders', function (Blueprint $table) use (
                $hasCustomerId,
                $hasProductId,
                $hasQuantity,
                $hasStatus,
                $hasOrderDate,
                $hasDeliveryDate,
                $hasNotes
            ) {
                if ($hasCustomerId) {
                    if ($usesSimpleForeignColumns) {
                        $table->dropColumn('customer_id');
                    } else {
                        $table->dropConstrainedForeignId('customer_id');
                    }
                }

                if ($hasProductId) {
                    if ($usesSimpleForeignColumns) {
                        $table->dropColumn('product_id');
                    } else {
                        $table->dropConstrainedForeignId('product_id');
                    }
                }

                if ($hasQuantity) {
                    $table->dropColumn('quantity');
                }

                if ($hasStatus) {
                    $table->dropColumn('status');
                }

                if ($hasOrderDate) {
                    $table->dropColumn('order_date');
                }

                if ($hasDeliveryDate) {
                    $table->dropColumn('delivery_date');
                }

                if ($hasNotes) {
                    $table->dropColumn('notes');
                }
            });
        }
    }
};
