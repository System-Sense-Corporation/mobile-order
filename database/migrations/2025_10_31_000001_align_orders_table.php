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
        $missingStatus = ! Schema::hasColumn('orders', 'status');
        $missingOrderDate = ! Schema::hasColumn('orders', 'order_date');
        $missingDeliveryDate = ! Schema::hasColumn('orders', 'delivery_date');
        $missingNotes = ! Schema::hasColumn('orders', 'notes');
        $hasCustomerName = Schema::hasColumn('orders', 'customer');
        $hasProductName = Schema::hasColumn('orders', 'product');

        if ($missingCustomerId || $missingProductId || $missingStatus || $missingOrderDate || $missingDeliveryDate || $missingNotes) {
            Schema::table('orders', function (Blueprint $table) use (
                $missingCustomerId,
                $missingProductId,
                $missingStatus,
                $missingOrderDate,
                $missingDeliveryDate,
                $missingNotes
            ) {
                if ($missingCustomerId) {
                    $table->foreignId('customer_id')
                        ->nullable()
                        ->after('id')
                        ->constrained()
                        ->cascadeOnDelete();
                }

                if ($missingProductId) {
                    $table->foreignId('product_id')
                        ->nullable()
                        ->after('customer_id')
                        ->constrained()
                        ->cascadeOnDelete();
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

        if ($hasCustomerName || $hasProductName) {
            Schema::table('orders', function (Blueprint $table) use ($hasCustomerName, $hasProductName) {
                if ($hasCustomerName) {
                    $table->dropColumn('customer');
                }

                if ($hasProductName) {
                    $table->dropColumn('product');
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

        $needsCustomerColumn = ! Schema::hasColumn('orders', 'customer');
        $needsProductColumn = ! Schema::hasColumn('orders', 'product');
        $hasCustomerId = Schema::hasColumn('orders', 'customer_id');
        $hasProductId = Schema::hasColumn('orders', 'product_id');
        $hasStatus = Schema::hasColumn('orders', 'status');
        $hasOrderDate = Schema::hasColumn('orders', 'order_date');
        $hasDeliveryDate = Schema::hasColumn('orders', 'delivery_date');
        $hasNotes = Schema::hasColumn('orders', 'notes');

        if ($needsCustomerColumn || $needsProductColumn) {
            Schema::table('orders', function (Blueprint $table) use ($needsCustomerColumn, $needsProductColumn) {
                if ($needsCustomerColumn) {
                    $table->string('customer')->nullable();
                }

                if ($needsProductColumn) {
                    $table->string('product')->nullable();
                }
            });
        }

        if ($hasCustomerId || $hasProductId || $hasStatus || $hasOrderDate || $hasDeliveryDate || $hasNotes) {
            Schema::table('orders', function (Blueprint $table) use (
                $hasCustomerId,
                $hasProductId,
                $hasStatus,
                $hasOrderDate,
                $hasDeliveryDate,
                $hasNotes
            ) {
                if ($hasCustomerId) {
                    $table->dropConstrainedForeignId('customer_id');
                }

                if ($hasProductId) {
                    $table->dropConstrainedForeignId('product_id');
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
