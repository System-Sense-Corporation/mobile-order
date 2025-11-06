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
        // เราจะ "เพิ่มช่อง" (Add Columns) ในตู้ 'users'
        Schema::table('users', function (Blueprint $table) {
            
            // VVVV พี่ "เอาขีดฆ่า" (Uncomment) ... 2 บรรทัดนี้ "ออก" (Out) VVVV
            // VVVV ...เพื่อให้ "Cloud" (เว็บจริง) ... มัน "สร้าง" (Create) 2 ช่องนี้! VVVV
            $table->string('user_id')->unique()->nullable(); // (พี่ "เอา" (Remove) ... ->after('phone') ... ออกแล้ว!)
            $table->string('department')->nullable(); // (พี่ "เอา" (Remove) ... ->after('user_id') ... ออกแล้ว!)
            // VVVV ^^^^ VVVV

            // (ส่วน 3 บรรทัดนี้... "Cloud" (เว็บจริง) ... ก็ "ยังไม่มี" (Doesn't Have) ... เหมือนกัน)
            $table->string('role')->nullable(); // (พี่ "เอา" (Remove) ... ->after('department') ... ออกแล้ว!)
            $table->boolean('notify_new_orders')->default(false);
            $table->boolean('require_password_change')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // (อันนี้คือ 'คำสั่งยกเลิก'... เผื่อเราทำพัง)
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_id', // (เอา "ขีดฆ่า" (Comment) ... ออก... เพื่อให้มัน 'ยกเลิก' (Drop) ได้)
                'department', // (เอา "ขีดฆ่า" (Comment) ... ออก... เพื่อให้มัน 'ยกเลิก' (Drop) ได้)
                'role',
                'notify_new_orders',
                'require_password_change',
            ]);
        });
    }
};