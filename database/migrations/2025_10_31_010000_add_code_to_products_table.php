<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        if (! Schema::hasColumn('products', 'code')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('code')->nullable()->after('id');
            });
        }

        if (! $this->hasCodeIndex()) {
            Schema::table('products', function (Blueprint $table) {
                $table->unique('code');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('products') || ! Schema::hasColumn('products', 'code')) {
            return;
        }

        if ($this->hasCodeIndex()) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }

    /**
     * Determine whether the unique index for the product code exists.
     */
    private function hasCodeIndex(): bool
    {
        if (! Schema::hasTable('products')) {
            return false;
        }

        $connection = Schema::getConnection();

        if ($connection->getDriverName() === 'sqlite') {
            $indexes = $connection->select("PRAGMA index_list('products')");

            return collect($indexes)->contains(function ($index) {
                return ($index->name ?? '') === 'products_code_unique';
            });
        }

        if (method_exists(Schema::class, 'hasIndex')) {
            return Schema::hasIndex('products', 'products_code_unique');
        }

        $driver = $connection->getDriverName();

        return match ($driver) {
            'mysql' => ! empty($connection->select(
                "SHOW INDEX FROM products WHERE Key_name = ?",
                ['products_code_unique']
            )),
            'pgsql' => ! empty($connection->select(
                "SELECT 1 FROM pg_indexes WHERE tablename = 'products' AND indexname = ?",
                ['products_code_unique']
            )),
            default => false,
        };
    }
};
