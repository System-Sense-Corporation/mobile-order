<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $departmentOptions = (array) trans('messages.admin_users.form.department.options');

        // ส่งข้อมูล user ปัจจุบันและตัวเลือกแผนกให้หน้า profile.blade.php ใช้
        return view('profile', [
            'user' => auth()->user(),
            'departmentOptions' => $departmentOptions,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // ✅ กรณี: ฟอร์มแก้ข้อมูลบัญชี (ชื่อ แผนก อีเมล เบอร์โทร)
        if ($request->input('intent') === 'profile') {
            $departmentOptions = array_keys((array) trans('messages.admin_users.form.department.options'));

            $data = $request->validate([
                'name'      => 'required|string|max:255',
                'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
                'department' => ['nullable', 'string', Rule::in($departmentOptions)],
                'telephone' => 'nullable|string|max:20',
            ]);

            $user->update($data);

            return back()->with('success', trans('messages.profile.flash.profile_updated'));
        }

        // ✅ กรณี: ฟอร์มเปลี่ยนรหัสผ่าน
        if ($request->input('intent') === 'password') {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password'         => ['required', 'confirmed', 'min:8'],
            ]);

            $user->password = Hash::make($request->password);
            $user->save();

            return back()->with('success', trans('messages.profile.flash.password_updated'));
        }
        return back();
    }
}
