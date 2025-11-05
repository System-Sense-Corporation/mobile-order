<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // <-- เพิ่มตัวนี้
use Illuminate\Support\Facades\Config; // <-- เพิ่มตัวนี้
use App\Models\Setting; // <-- เพิ่ม 'ล่าม' (Model) ของเราเข้ามา
use Illuminate\Support\Facades\Cache; // <-- เพิ่ม Cache มาช่วย

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
        // VVVVVV นี่คือโค้ดที่เราจะเพิ่มเข้าไป VVVVVV
        
        // เราต้องเช็กก่อนว่า ตาราง 'settings' มันมีอยู่จริงมั้ย
        // (ไม่งั้นตอนรัน migrate:fresh ครั้งแรก มันจะพัง)
        if (Schema::hasTable('settings')) {
            
            // เราจะใช้ Cache (ที่เก็บข้อมูลชั่วคราว) มาช่วย
            // เพื่อจะได้ไม่ต้องดึงข้อมูลจาก Database (DB) ทุกครั้งที่เว็บโหลด (มันช้า!)
            // มันจะดึงจาก DB แค่ครั้งแรก... แล้วจำไว้ 10 นาที (600 วินาที)
            $settings = Cache::remember('app_settings', 600, function () {
                // เราใช้ try...catch ดักไว้ เผื่อ Database ยังไม่พร้อม
                try {
                    // ตรวจสอบว่า Model Setting มีอยู่จริงก่อน
                    if (class_exists(Setting::class)) {
                        return Setting::all()->pluck('value', 'key')->toArray();
                    }
                    return null;
                } catch (\Exception $e) {
                    return null; // ถ้าเจ๊ง ให้ส่งค่าว่าง
                }
            });

            // ตรวจสอบว่ามีค่า settings (ที่ดึงจาก Cache/DB) และค่า mail ที่จำเป็นหรือไม่
            if (!empty($settings) && isset($settings['mail_host'])) {
                
                // "บังคับ" ให้ Laravel เปลี่ยนค่า config mail เดี๋ยวนี้!
                Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? '');
                Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? 587);
                Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? 'tls');
                Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? '');
                Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? '');
                
                // (อันนี้แถม... ตั้งค่า "ชื่อผู้ส่ง" (From Name) ด้วย)
                Config::set('mail.from.address', $settings['email'] ?? 'noreply@example.com');
                Config::set('mail.from.name', $settings['name'] ?? 'Mobile Order');
            }
        }
        
        // ^^^^^^ สิ้นสุดโค้dที่เราเพิ่ม ^^^^^^
    }
}