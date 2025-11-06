<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // คอลัมน์ที่ต้องการเพิ่ม
        $wantToAdd = [
            'notify_new_orders'       => fn (Blueprint $t) => $t->boolean('notify_new_orders')->default(false),
            'require_password_change' => fn (Blueprint $t) => $t->boolean('require_password_change')->default(false),
        ];

        // คัดเฉพาะรายการที่ยังไม่มีในตาราง users
        $toAdd = array_keys(array_filter($wantToAdd, function ($_, $col) {
            return ! Schema::hasColumn('users', $col);
        }, ARRAY_FILTER_USE_BOTH));

        if ($toAdd === []) {
            return; // ไม่มีอะไรต้องทำ
        }

        Schema::table('users', function (Blueprint $table) use ($wantToAdd, $toAdd) {
            foreach ($toAdd as $col) {
                // เรียก closure ที่นิยามไว้ด้านบนเพื่อเพิ่มคอลัมน์
                $wantToAdd[$col]($table);
            }
        });
    }

    public function down(): void
    {
        // รายการคอลัมน์ที่ migration นี้เป็นคนเพิ่ม
        $candidates = ['notify_new_orders', 'require_password_change'];

        // ลบเฉพาะคอลัมน์ที่มีอยู่จริง (กันพังเวลาถอยหลัง)
        $toDrop = array_values(array_filter($candidates, fn ($col) => Schema::hasColumn('users', $col)));

        if ($toDrop === []) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($toDrop) {
            $table->dropColumn($toDrop);
        });
    }
};
