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
            
            // VVVV "บอส" ตัวที่ 2 คือบรรทัดนี้ค่ะ VVVV
            // $table->string('user_id')->unique()->nullable()->after('phone'); // ช่อง 'USR-1007'
            // (พี่โดนัท "ลบ" (Comment) มันทิ้ง... เพราะ Error มันฟ้องว่า "มีช่องนี้อยู่แล้ว"!)
            // VVVV ^^^^ VVVV

            // VVVV "บอส" ตัวที่ 1 คือบรรทัดนี้ค่ะ VVVV
            // $table->string('department')->nullable()->after('user_id'); // ช่อง 'แผนก'
            // (พี่โดนัท "ลบ" (Comment) มันทิ้ง... เพราะ Error มันฟ้องว่า "มีช่องนี้อยู่แล้ว"!)
            // VVVV ^^^^ VVVV

            // (ส่วนบรรทัดนี้... มันจะเพิ่ม 'role' ... 'ต่อจาก' (after) ... 'department' (ที่มีอยู่แล้ว)... ถูกต้องค่ะ!)
            $table->string('role')->nullable()->after('department'); // ช่อง 'สิทธิ์' (admin, editor)
            $table->boolean('notify_new_orders')->default(false)->after('role');
            $table->boolean('require_password_change')->default(false)->after('notify_new_orders');
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
                // 'user_id', // (ลบออกจาก 'ยกเลิก' ด้วย... เพราะเราไม่ได้สร้างมัน)
                // 'department', // (ลบออกจาก 'ยกเลิก' ด้วย... เพราะเราไม่ได้สร้างมัน)
                'role',
                'notify_new_orders',
                'require_password_change',
            ]);
        });
    }
};