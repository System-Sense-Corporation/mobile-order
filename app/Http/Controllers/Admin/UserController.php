<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // <-- VVVV เพิ่ม Hash
use App\Models\User; // <-- VVVV เพิ่ม Model User
use Illuminate\Support\Facades\DB; // <-- VVVV เพิ่ม DB

class UserController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.users.index', [
            'users' => $this->allUsers($request),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', [
            'user' => null,
        ]);
    }

    public function edit(Request $request, string $userId): View
    {
        $user = $this->findUser($request, $userId);

        abort_if(is_null($user), 404);

        return view('admin.users.form', [
            'user' => $user,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // --- VVVV ฟังก์ชันสำหรับสร้าง User ใหม่ VVVV ---

        // 1. ตรวจสอบข้อมูล (Validate)
        $departmentOptions = array_keys((array) trans('messages.admin_users.form.department.options'));
        $roleOptions = array_keys((array) trans('messages.admin_users.roles'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['required', 'string', Rule::in($departmentOptions)],
            'authority' => ['required', 'string', Rule::in($roleOptions)],
            'notify_new_orders' => ['nullable', 'boolean'],
            'require_password_change' => ['nullable', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        // 2. "สร้าง" (Create) User ใหม่... ลงใน "Database จริง" (ตาราง 'users')
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // <-- "เข้ารหัส" (Hash) รหัสผ่าน
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['authority'], 
            'department' => $validated['department'], 
            'notify_new_orders' => (bool) ($validated['notify_new_orders'] ?? false),
            'require_password_change' => (bool) ($validated['require_password_change'] ?? false),
            'user_id' => $this->nextUserId(), // <-- กำหนด user_id อัตโนมัติ
            'email_verified_at' => now(), 
        ]);
        
        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.created'));
    }

    public function update(Request $request, string $userId): RedirectResponse
    {
        // --- VVVV ฟังก์ชันสำหรับแก้ไข User VVVV ---

        $user = User::where('user_id', $userId)->firstOrFail();
        
        $departmentOptions = array_keys((array) trans('messages.admin_users.form.department.options'));
        $roleOptions = array_keys((array) trans('messages.admin_users.roles'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['required', 'string', Rule::in($departmentOptions)],
            'authority' => ['required', 'string', Rule::in($roleOptions)],
            'notify_new_orders' => ['nullable', 'boolean'],
            'require_password_change' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['authority'],
            'department' => $validated['department'],
            'notify_new_orders' => (bool) ($validated['notify_new_orders'] ?? false),
            'require_password_change' => (bool) ($validated['require_password_change'] ?? false),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.updated'));
    }

    public function destroy(Request $request, string $userId): RedirectResponse
    {
        // --- VVVV ฟังก์ชันสำหรับลบ User VVVV ---
        
        $user = User::where('user_id', $userId)->first();

        if ($user) {
            $user->delete();
        } else {
            // (จัดการ User Demo ที่เก็บใน Session)
            $storedUsers = $this->storedUsers($request);
            if (isset($storedUsers[$userId])) {
                unset($storedUsers[$userId]);
                $request->session()->put('admin_users', $storedUsers);
            }
            $deleted = $this->deletedUserIds($request);
            $deleted[] = $userId;
            $request->session()->put('admin_users_deleted', array_values(array_unique($deleted)));
        }

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.deleted'));
    }

    /**
     * คืนค่า User ID ถัดไป (เช่น USR-0008)
     */
    private function nextUserId(): string
    {
        $latestUser = User::orderBy('user_id', 'desc')->first();
        $max = 0;

        if ($latestUser && preg_match('/USR-(\d+)/', $latestUser->user_id, $matches)) {
            $max = (int) $matches[1];
        }

        if ($max === 0) {
            foreach ($this->demoUsers() as $user) {
                if (preg_match('/USR-(\d+)/', $user['user_id'], $matches)) {
                    $max = max($max, (int) $matches[1]);
                }
            }
        }
        
        return sprintf('USR-%04d', $max + 1);
    }

    /**
     * คืนค่า User ทั้งหมด (จาก DB จริง + Demo Users)
     */
    private function allUsers(Request $request): array
    {
        // 1. ดึง User "ตัวจริง" (จาก DB) มาทั้งหมด
        $dbUsers = User::all()->map(function ($user) {
            return [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'department' => trans('messages.admin_users.form.department.options.' . $user->department) ?? $user->department,
                'authority' => $user->role,
                'email' => $user->email,
                'phone' => $user->phone ?? '—',
                'status' => 'active', 
                'last_login' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '—',
                'is_real' => true, 
            ];
        })->toArray();
        
        // 2. ดึง 'Demo User' (จาก Session)
        $stored = $this->storedUsers($request);
        $deleted = array_flip($this->deletedUserIds($request));
        $demoUsers = [];
        
        // กรอง Demo User ที่ซ้ำกับ DB ออก
        foreach ($this->demoUsers() as $user) {
            $isDuplicate = false;
            foreach ($dbUsers as $dbUser) {
                if ($dbUser['email'] === $user['email'] || $dbUser['user_id'] === $user['user_id']) {
                    $isDuplicate = true;
                    break;
                }
            }
            if ($isDuplicate) continue;
            
            if (isset($deleted[$user['user_id']])) {
                continue;
            }
            // FIX 2: แก้ไขตรงนี้
            if (isset($stored[$user['user_id']])) { 
                $demoUsers[] = $stored[$user['user_id']];
                unset($stored[$user['user_id']]);
            } else {
                $demoUsers[] = $user;
            }
        }

        // 3. "รวมร่าง" (Merge) User "ตัวจริง" (DB) กับ "Demo User" (ที่เหลือ)
        return array_merge($dbUsers, $demoUsers);
    }

    // --- VVVV โค้ดส่วน Helper function เดิมของหนิง VVVV ---

    private function storedUsers(Request $request): array
    {
        $stored = $request->session()->get('admin_users', []);
        
        if (! is_array($stored)) {
            return [];
        }

        $normalized = [];

        foreach ($stored as $user) {
            if (is_array($user) && isset($user['user_id'])) {
                $normalized[$user['user_id']] = $user;
            }
        }

        return $normalized;
    }

    private function deletedUserIds(Request $request): array
    {
        $deleted = $request->session()->get('admin_users_deleted', []);
        
        if (! is_array($deleted)) {
            return [];
        }

        return array_values(array_filter($deleted, static fn ($value) => is_string($value) && $value !== ''));
    }

    private function removeFromDeleted(Request $request, string $userId): void
    {
        $deleted = $this->deletedUserIds($request);
        
        if (empty($deleted)) {
            return;
        }

        $filtered = array_values(array_filter($deleted, static fn ($value) => $value !== $userId));

        if (count($filtered) !== count($deleted)) {
            if (empty($filtered)) {
                $request->session()->forget('admin_users_deleted');
            } else {
                $request->session()->put('admin_users_deleted', $filtered);
            }
        }
    }

    private function findUser(Request $request, string $userId): ?array
    {
        // 1. หา User "ตัวจริง" (จาก DB) ก่อน
        $userModel = User::where('user_id', $userId)->first();
        
        if ($userModel) {
            // ถ้า "เจอ"... ก็แปลง User จาก DB ให้เป็น array
            return [
                'user_id' => $userModel->user_id,
                'name' => $userModel->name,
                'department_key' => $userModel->department, // (key)
                'authority' => $userModel->role, // (role)
                'email' => $userModel->email,
                'phone' => $userModel->phone ?? '—',
                'status' => 'active', 
                'last_login' => $userModel->last_login_at ? $userModel->last_login_at->format('Y-m-d H:i') : '—', 
                'notify_new_orders' => $userModel->notify_new_orders ?? true,
                'require_password_change' => $userModel->require_password_change ?? false,
                'is_real' => true, 
            ];
        }

        // 2. ถ้า "ไม่เจอ" (Not Found) ... ค่อยไปหาใน 'Demo User' (Session)
        $stored = $this->storedUsers($request);
        
        if (isset($stored[$userId])) {
            $user = $stored[$userId];

            $user['department_key'] = $user['department_key'] ?? null;
            $user['notify_new_orders'] = $user['notify_new_orders'] ?? true;
            $user['require_password_change'] = $user['require_password_change'] ?? false;
            $user['is_real'] = false; // (บอกว่าคนนี้ 'ตัวปลอม' (Demo))

            return $user;
        }

        $deleted = array_flip($this->deletedUserIds($request));

        if (isset($deleted[$userId])) {
            return null;
        }

        foreach ($this->demoUsers() as $user) {
            if ($user['user_id'] === $userId) {
                $user['department_key'] = null;
                $user['notify_new_orders'] = true;
                $user['require_password_change'] = false;
                $user['is_real'] = false; // (บอกว่าคนนี้ 'ตัวปลอม' (Demo))

                return $user;
            }
        }

        // 3. ถ้า "ไม่เจอ" ทั้งคู่... ก็คือ "ไม่เจอ" (Not Found)
        return null;
    }

    private function demoUsers(): array
    {
        return [
            [
                'user_id' => 'USR-1001',
                'name' => '山田 太郎',
                'department' => '営業統括部',
                'authority' => 'admin',
                'email' => 'taro.yamada@example.com',
                'phone' => '090-1234-5678',
                'status' => 'active',
                'last_login' => '2024-04-18 09:42',
            ],
            [
                'user_id' => 'USR-1002',
                'name' => '佐藤 花',
                'department' => '商品開発部',
                'authority' => 'editor',
                'email' => 'hana.sato@example.com',
                'phone' => '080-9876-5432',
                'status' => 'active',
                'last_login' => '2024-04-18 08:15',
            ],
            [
                'user_id' => 'USR-1003',
                'name' => 'Michael Chen',
                'department' => 'International Sales',
                'authority' => 'viewer',
                'email' => 'michael.chen@example.com',
                'phone' => '070-3456-7890',
                'status' => 'inactive',
                'last_login' => '2024-04-12 17:28',
            ],
            [
                'user_id' => 'USR-1004',
                'name' => '鈴木 健',
                'department' => '物流管理部',
                'authority' => 'editor',
                'email' => 'ken.suzuki@example.com',
                'phone' => '090-2468-1357',
                'status' => 'active',
                'last_login' => '2024-04-17 19:03',
            ],
            [
                'user_id' => 'USR-1005',
                'name' => 'Amanda Reyes',
                'department' => 'Customer Success',
                'authority' => 'viewer',
                'email' => 'amanda.reyes@example.com',
                'phone' => '080-1122-3344',
                'status' => 'suspended',
                'last_login' => '2024-03-29 11:52',
            ],
            [
                'user_id' => 'USR-1006',
                'name' => '高橋 さくら',
                'department' => '経理部',
                'authority' => 'admin',
                'email' => 'sakura.takahashi@example.com',
                'phone' => '070-9988-7766',
                'status' => 'active',
                'last_login' => '2024-04-18 07:55',
            ],
        ];
    }
}