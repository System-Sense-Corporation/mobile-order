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
            
            // VVVV "Cloud" (เว็บจริง) ... มัน "ยังไม่มี" (Doesn't Have) ... ช่องนี้ ...
            // VVVV ...เราเลย "ต้อง" (Must) ... "สร้าง" (Create) ... มันค่ะ! VVVV
            $table->string('user_id')->unique()->nullable(); // (พี่ "เอา" (Remove) ... ->after('phone') ... ออกแล้ว!)
            
            // VVVV "Cloud" (เว็บจริง) ... มัน "มี" (Already Has) ... ช่องนี้ "แล้ว" (Already)...
            // VVVV ...เราเลย "ต้อง" (Must) ... "ขีดฆ่า" (Comment) ... มันทิ้งไปค่ะ! VVVV
            // $table->string('department')->nullable(); // (พี่ "เอา" (Remove) ... ->after('user_id') ... ออกแล้ว!)
            
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
                'user_id',
                // 'department', // (ขีดฆ่า "ที่นี่" ... ด้วย!)
                'role',
                'notify_new_orders',
                'require_password_change',
            ]);
        });
    }
};