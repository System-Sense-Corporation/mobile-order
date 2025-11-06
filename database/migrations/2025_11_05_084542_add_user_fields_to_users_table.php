<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; 
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            // --- ✨ 2. พี่โดนัทแก้ตรงนี้ (หุ้มด้วย if) ---
            // เช็กก่อนว่า "ยังไม่มี" คอลัมน์นี้... ค่อยสร้าง
            if (!Schema::hasColumn('users', 'notify_new_orders')) {
                $table->boolean('notify_new_orders')->default(false);
            }

            // เช็กเผื่อไว้เลย (ถ้ามีคอลัมน์นี้ในไฟล์เดียวกัน)
            if (!Schema::hasColumn('users', 'require_password_change')) {
                $table->boolean('require_password_change')->default(false);
            }
            // --- ✨ จบส่วนที่แก้ ✨ ---

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            // --- ✨ 3. พี่โดนัทแก้เผื่อ (เผื่อต้อง Rollback) ---
            // เช็กก่อนว่า "มี" คอลัมน์นี้... ค่อยลบ
            if (Schema::hasColumn('users', 'notify_new_orders')) {
                $table->dropColumn('notify_new_orders');
            }

            if (Schema::hasColumn('users', 'require_password_change')) {
                $table->dropColumn('require_password_change');
            }
            // --- ✨ จบส่วนที่แก้ ✨ ---

        });
    }
};