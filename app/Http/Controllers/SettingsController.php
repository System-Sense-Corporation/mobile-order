<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting; // <-- เพิ่ม 'ล่าม' (Model) ของเราเข้ามา

class SettingsController extends Controller
{
    /**
     * ฟังก์ชันสำหรับแสดงหน้า Settings (GET)
     */
    public function index()
    {
        // ดึงค่า Setting ทั้งหมดจาก DB มาทำเป็น array
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        // เปิดหน้า view (settings.blade.php) และส่งตัวแปร $settings เข้าไปด้วย
        return view('settings', compact('settings'));
    }

    /**
     * ฟังก์ชันสำหรับบันทึกข้อมูล (POST) (ตอนกดปุ่ม Save)
     */
    public function store(Request $request)
    {
        // ดึงข้อมูลทั้งหมดที่ส่งมาจากฟอร์ม ยกเว้นตัว _token
        $inputs = $request->except('_token');

        // วนลูปบันทึกค่าทีละตัว
        foreach ($inputs as $key => $value) {
            // คำสั่งนี้คือ "ถ้ามี key นี้อยู่แล้ว ให้อัปเดต, ถ้ายังไม่มี ให้สร้างใหม่"
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? ''] // ถ้าค่าเป็น null ให้บันทึกเป็นค่าว่าง
            );
        }

        // บันทึกเสร็จแล้ว ให้เด้งกลับไปหน้าเดิม พร้อมข้อความว่า "บันทึกสำเร็จ"
return redirect()->route('settings')
                 ->with('success', __('messages.settings.alerts.saved_success'));
   }
}
