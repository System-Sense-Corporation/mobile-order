<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        // ส่งข้อมูล user ปัจจุบันให้หน้า profile.blade.php ใช้
        return view('profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // ✅ กรณี: ฟอร์มแก้ข้อมูลบัญชี (ชื่อ อีเมล เบอร์โทร)
        if ($request->input('intent') === 'profile') {
            $data = $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
                'telephone' => 'nullable|string|max:20',
            ]);

            $user->update($data);

            return back()->with('success', 'อัปเดตข้อมูลเรียบร้อยแล้ว');
        }

        // ✅ กรณี: ฟอร์มเปลี่ยนรหัสผ่าน
        if ($request->input('intent') === 'password') {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password'         => ['required', 'confirmed', 'min:8'],
            ]);

            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
        }

        // ถ้าไม่มี intent ตรงเงื่อนไข
        return back();
    }
}
