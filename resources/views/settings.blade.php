@extends('layouts.app')

@section('title', __('messages.app.name') . ' - ' . __('messages.settings.title'))

@section('page-title', __('messages.settings.title'))

@section('content')
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Box 1: Notifications (Original) -->
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-accent">{{ __('messages.settings.notifications.title') }}</h2>
            <form class="space-y-4">
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.notifications.order_mail') }}</span>
                    <input type="email" class="form-input" placeholder="{{ __('messages.settings.placeholders.order_mail') }}" value="{{ __('messages.settings.placeholders.order_mail') }}">
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.notifications.alert_mail') }}</span>
                    <input type="email" class="form-input" placeholder="{{ __('messages.settings.placeholders.alert_mail') }}">
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.notifications.slack') }}</span>
                    <input type="url" class="form-input" placeholder="{{ __('messages.settings.placeholders.slack') }}">
                </label>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">{{ __('messages.settings.buttons.save') }}</button>
                </div>
            </form>
        </div>

        <!-- Box 2: System (Original) -->
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-accent">{{ __('messages.settings.system.title') }}</h2>
            <form class="space-y-4">
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.system.timezone') }}</span>
                    <select class="form-input">
                        <option value="Asia/Tokyo">Asia/Tokyo</option>
                        <option value="Asia/Bangkok" selected>Asia/Bangkok</option>
                        <option value="Asia/Singapore">Asia/Singapore</option>
                        <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh</option>
                    </select>
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.system.open_time') }}</span>
                    <input type="time" class="form-input" value="05:00">
                </label>
                <label class="form-field">
                    <span class="form-label">{{ __('messages.settings.system.close_time') }}</span>
                    <input type="time" class="form-input" value="14:00">
                </label>
                <div class="flex justify-end">
                    <button type="submit" class="btn-secondary">{{ __('messages.settings.buttons.draft') }}</button>
                </div>
            </form>
        </div>

        <!-- 
          VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV
          พี่โดนัทเพิ่มกล่องใหม่ให้ตรงนี้นะคะ
          VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV 
        -->

        <!-- Box 3: NEW Company & Email Settings -->
        <!-- เราจะให้กล่องนี้ ขยายเต็ม 2 คอลัมน์เลย ด้วย lg:col-span-2 -->
        <div class="space-y-6 rounded-lg bg-white p-6 shadow-sm ring-1 ring-black/5 lg:col-span-2">
            
            <h2 class="text-lg font-semibold text-accent">Company & Email Settings</h2>

            <!-- แสดงข้อความ "บันทึกสำเร็จ" ถ้ามี -->
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 
              นี่คือฟอร์มใหม่ที่จะใช้บันทึกข้อมูล
              มันจะวิ่งไปหา Route ชื่อ 'settings.store' ที่เราจะสร้างกัน
            -->
            <form class="space-y-6" action="{{ route('settings.store') }}" method="POST">
                @csrf <!-- สำคัญมาก! สำหรับ Laravel -->

                <!-- พี่จะแบ่งฟอร์มเป็น 2 คอลัมน์อีกทีข้างในนี้ จะได้ไม่ยาวเกินไป -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <!-- คอลัมน์ซ้าย: ข้อมูลบริษัท -->
                    <div class="space-y-4">
                        <h3 class="text-base font-semibold text-gray-800">Company Information</h3>
                        
                        <label class="form-field">
                            <span class="form-label">Company Name:</span>
                            <!-- 
                              โค้ด old(..., $settings[...] ?? '') 
                              คือการดึงค่าเก่าจาก DB มาแสดงค่ะ 
                            -->
                            <input type="text" name="company_name" class="form-input" value="{{ old('company_name', $settings['company_name'] ?? '') }}">
                        </label>
                        
                        <label class="form-field">
                            <span class="form-label">Head Office Address:</span>
                            <input type="text" name="head_office_address" class="form-input" value="{{ old('head_office_address', $settings['head_office_address'] ?? '') }}">
                        </label>
                        
                        <label class="form-field">
                            <span class="form-label">Tel.:</span>
                            <input type="text" name="tel" class="form-input" value="{{ old('tel', $settings['tel'] ?? '') }}">
                        </label>

                        <label class="form-field">
                            <span class="form-label">Fax:</span>
                            <input type="text" name="fax" class="form-input" value="{{ old('fax', $settings['fax'] ?? '') }}">
                        </label>

                        <label class="form-field">
                            <span class="form-label">Tax ID:</span>
                            <input type="text" name="tax_id" class="form-input" value="{{ old('tax_id', $settings['tax_id'] ?? '') }}">
                        </label>

                        <label class="form-field">
                            <span class="form-label">Name:</span>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $settings['name'] ?? '') }}">
                        </label>

                        <label class="form-field">
                            <span class="form-label">Email:</span>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $settings['email'] ?? '') }}">
                        </label>
                    </div>

                    <!-- คอลัมน์ขวา: ข้อมูล Mail Server -->
                    <div class="space-y-4">
                        <h3 class="text-base font-semibold text-gray-800">Email Server (SMTP)</h3>

                        <label class="form-field">
                            <span class_label="form-label">MAIL_MAILER:</span>
                            <input type="text" name="mail_mailer" class="form-input" value="{{ old('mail_mailer', $settings['mail_mailer'] ?? '') }}">
                        </label>

                        <label class="form-field">
                            <span class_label="form-label">MAIL_HOST:</span>
                            <input type="text" name="mail_host" class="form-input" value="{{ old('mail_host', $settings['mail_host'] ?? '') }}">
                        </label>

                        <label class="form-field">
                            <span class="form-label">MAIL_PORT:</span>
                            <input type="text" name="mail_port" class="form-input" value="{{ old('mail_port', $settings['mail_port'] ?? '') }}">
                        </label>

                        <label class="form-field">
                            <span class="form-label">MAIL_USERNAME:</span>
                            <input type="text" name="mail_username" class="form-input" value="{{ old('mail_username', $settings['mail_username'] ?? '') }}">
                        </label>

                        <label class="form-field">
                            <span class="form-label">MAIL_PASSWORD:</span>
                            <input type="password" name="mail_password" class="form-input" value="{{ old('mail_password', $settings['mail_password'] ?? '') }}">
                        </label>

                        <label class="form-field">
                            <span class="form-label">MAIL_ENCRYPTION:</span>
                            <input type="text" name="mail_encryption" class="form-input" value="{{ old('mail_encryption', $settings['mail_encryption'] ?? '') }}">
                        </label>
                    </div>
                </div>

                <!-- ปุ่ม Save -->
                <div class="flex justify-end border-t border-gray-200 pt-5">
                    <button type="submit" class="btn-primary">Save Company & Email Settings</button>
                </div>
            </form>
        </div>

    </div>
@endsection