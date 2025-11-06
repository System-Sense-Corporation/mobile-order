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
            
            // VVVV "Cloud" (เว็บจริง) ... มัน "มี" (Already Has) ... ช่องนี้ "แล้ว" (Already)...
            // VVVV ...เราเลย "ต้อง" (Must) ... "ขีดฆ่า" (Comment) ... มันทิ้งไปค่ะ! VVVV
            // $table->string('user_id')->unique()->nullable(); 
            
            // VVVV "Cloud" (เว็บจริง) ... มัน "มี" (Already Has) ... ช่องนี้ "แล้ว" (Already)...
            // VVVV ...เราเลย "ต้อง" (Must) ... "ขีดฆ่า" (Comment) ... มันทิ้งไปค่ะ! VVVV
            // $table->string('department')->nullable(); 
            
            // VVVV "Cloud" (เว็บจริง) ... มัน "มี" (Already Has) ... ช่องนี้ "แล้ว" (Already)... (จาก Error ล่าสุด!)
            // VVVV ...เราเลย "ต้อง" (Must) ... "ขีดฆ่า" (Comment) ... มันทิ้งไปค่ะ! VVVV
            // $table->string('role')->nullable(); 
            
            // (ส่วน 2 บรรทัดนี้... "Cloud" (เว็บจริง) ... "ยังไม่มี" (Doesn't Have) ... เราเลย "ต้อง" (Must) ... "สร้าง" (Create) ... มันค่ะ!)
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
                // 'user_id', // (ขีดฆ่า "ที่นี่" ... ด้วย!)
                // 'department', // (ขีดฆ่า "ที่นี่" ... ด้วย!)
                // 'role', // (ขีดฆ่า "ที่นี่" ... ด้วย!)
                'notify_new_orders',
                'require_password_change',
            ]);
        });
    }
};