<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    /**
     * แสดงหน้า Settings (GET)
     */
    public function index()
    {
        // ดึงค่า Setting ทั้งหมดจาก DB มาทำเป็น array
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('settings', compact('settings'));
    }

    /**
     * ✅ บันทึก: Notification Settings (ฝั่งซ้าย)
     */
    public function updateNotification(Request $request)
    {
        // เฉพาะคีย์ที่ต้องการบันทึก
        $inputs = $request->only([
            'order_notification_email',
            'emergency_contact_email',
            'slack_webhook_url',
        ]);

        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return back()->with('success', 'Notification settings saved successfully.');
    }

    /**
     * ✅ บันทึก: System Settings (ฝั่งขวา)
     */
    public function updateSystem(Request $request)
    {
        $inputs = $request->only([
            'timezone',
            'business_open_time',
            'business_close_time',
        ]);

        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return back()->with('success', 'System settings saved successfully.');
    }

    /**
     * ✅ บันทึก: Company & Email Settings (กล่องใหญ่ล่าง)
     */
    public function store(Request $request)
    {
        // ดึงข้อมูลทั้งหมด ยกเว้น token
        $inputs = $request->except('_token');

        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return redirect()->route('settings')
            ->with('success', __('messages.settings.alerts.saved_success'));
    }
}
