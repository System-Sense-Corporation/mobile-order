<?php

    namespace App\Providers;

    use Illuminate\Support\ServiceProvider;
    use Illuminate\Support\Facades\Schema; // <-- 1. พี่เพิ่มตัวนี้
    use App\Models\Setting; // <-- 2. พี่เพิ่ม 'ล่าม' (Model) ของเรา
    use Illuminate\Support\Facades\Config; // <-- 3. พี่เพิ่มตัว Config

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
            // VVVVVV พี่เพิ่มโค้ดส่วนนี้เข้าไปค่ะ VVVVVV

            // เราจะเช็กก่อนว่า ตาราง 'settings' มันถูกสร้างหรือยัง
            // (ไม่งั้นตอนรัน migrate:fresh มันจะ Error ค่ะ)
            if (Schema::hasTable('settings')) {
                
                // ดึงค่า Setting ทั้งหมดจาก Database
                $settings = Setting::all()->pluck('value', 'key')->toArray();

                // เช็กว่ามีค่า mail_host อยู่ใน DB หรือไม่ (กัน Error)
                if (!empty($settings['mail_host'])) {
                    
                    // นี่คือ "การยัดไส้" Config ค่ะ!
                    // เราจะสั่งให้ Laravel เปลี่ยนค่า Config ของ Mail กลางอากาศเลย
                    Config::set([
                        'mail.mailers.smtp.host' => $settings['mail_host'] ?? null,
                        'mail.mailers.smtp.port' => $settings['mail_port'] ?? null,
                        'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? null,
                        'mail.mailers.smtp.username' => $settings['mail_username'] ?? null,
                        'mail.mailers.smtp.password' => $settings['mail_password'] ?? null,
                        
                        // อันนี้สำหรับตั้งชื่อ "ผู้ส่ง" (เช่น "BizFlow")
                        'mail.from.address' => $settings['email'] ?? 'noreply@example.com',
                        'mail.from.name' => $settings['name'] ?? 'Mobile Order',
                    ]);
                }
            }
            
            // ^^^^^^ สิ้นสุดโค้ดที่พี่เพิ่มค่ะ ^^^^^^
        }
    }