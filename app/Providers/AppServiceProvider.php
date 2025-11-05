<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// เราไม่ต้องใช้อะไรพวกนั้นแล้ว! (Schema, Config, Setting, Cache)

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // "ว่างเปล่า" (Clean) แล้ว!
        // ตอนนี้มันจะ "เลิก" พยายามอ่านจาก Database (ตาราง settings)
        // แล้วมันจะหันไป "ฟัง" (Listen) ค่าจาก "กล่องดำๆ" (Environment Variables)
        // ที่เรากรอก Mailtrap ไว้... 100% แล้วค่ะ!
        //
    }
}