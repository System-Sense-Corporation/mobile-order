<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // <-- VVVV พี่โดนัทเพิ่ม "เครื่องปั่นรหัส" (Hash) มาค่ะ VVVV
use App\Models\User; // <-- VVVV พี่โดนัทขอเพิ่ม Model "User" มาด้วยนะคะ VVVV

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
        $departmentOptions = array_keys((array) trans('messages.admin_users.form.department.options'));
        $roleOptions = array_keys((array) trans('messages.admin_users.roles'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // เราจะเช็กว่า 'email' นี้... "ห้ามซ้ำ" (unique) กับในตาราง 'users' (Database จริง)
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['required', 'string', Rule::in($departmentOptions)],
            'authority' => ['required', 'string', Rule::in($roleOptions)],
            'notify_new_orders' => ['nullable', 'boolean'],
            'require_password_change' => ['nullable', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        // 2. "สร้าง" (Create) User ใหม่... ลงใน "Database จริง" (ตาราง 'users')
        // (เราจะไม่เก็บ User ไว้ใน Session (ที่จำแป๊บเดียว) แล้วค่ะ!)
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // <-- "เข้ารหัส" (Hash) รหัสผ่าน
            'phone' => $validated['phone'] ?? null,
            
            // (พี่โดนัทเดาว่า... 'authority' (สิทธิ์) มันควรจะเป็น 'role' (ยศ) ใน DB)
            'role' => $validated['authority'], 
            
            // (พี่โดนัทไม่รู้ว่า 'department' (ใน DB) มันเก็บ 'key' หรือ 'value' ... เลยใส่ 'key' (ที่มาจาก form) ไปก่อน)
            'department' => $validated['department'], 
            
            'notify_new_orders' => (bool) ($validated['notify_new_orders'] ?? false),
            'require_password_change' => (bool) ($validated['require_password_change'] ?? false),
            
            'user_id' => $this->nextUserId(), // (พี่แก้ฟังก์ชัน nextUserId ให้ด้วยน้า)
            'email_verified_at' => now(), // (ยืนยันอีเมลให้เลย)
        ]);
        
        // (เราไม่ต้องยุ่งกับ Session (admin_users) แล้ว... เพราะเราเก็บลง DB จริงแล้วค่ะ)

        // --- ^^^^ สิ้นสุดตรงนี้ค่ะ ^^^^ ---

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.created'));
    }

    public function update(Request $request, string $userId): RedirectResponse
    {
        // --- VVVV พี่โดนัทแก้โค้ดตรงนี้ (Update) VVVV ---

        // 1. หา User "ตัวจริง" (จาก DB)
        // (เราจะเลิกใช้ 'demo user' (findUser) ที่อยู่ใน Session แล้วค่ะ)
        $user = User::where('user_id', $userId)->firstOrFail();
        
        // 2. ตรวจสอบข้อมูล (Validate)
        $departmentOptions = array_keys((array) trans('messages.admin_users.form.department.options'));
        $roleOptions = array_keys((array) trans('messages.admin_users.roles'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // เราจะเช็กว่า 'email' นี้... "ห้ามซ้ำ" (unique) ... ยกเว้น "User คนนี้เอง" (ignore)
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['required', 'string', Rule::in($departmentOptions)],
            'authority' => ['required', 'string', Rule::in($roleOptions)],
            'notify_new_orders' => ['nullable', 'boolean'],
            'require_password_change' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // (ช่องรหัสผ่าน... 'ว่าง' (nullable) ได้... ถ้าไม่อยากเปลี่ยน)
        ]);

        // 3. เตรียมข้อมูล (Data) ที่จะอัปเดต
        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['authority'], // (เหมือนเดิม)
            'department' => $validated['department'], // (เหมือนเดิม)
            'notify_new_orders' => (bool) ($validated['notify_new_orders'] ?? false),
            'require_password_change' => (bool) ($validated['require_password_change'] ?? false),
        ];

        // 4. เช็กว่ามีการ "กรอกรหัสผ่านใหม่" (ไม่ว่าง) หรือเปล่า
        if (!empty($validated['password'])) {
            // ถ้า "กรอก"... ก็ให้ 'เข้ารหัส' (Hash) อันใหม่
            $data['password'] = Hash::make($validated['password']);
        }
        // (ถ้า "ไม่กรอก" (ว่าง)... เราก็ไม่ต้องทำอะไร... มันก็จะใช้ "รหัสผ่านเดิม" (ใน DB) ของมันไปค่ะ)

        // 5. "อัปเดต" (Update) ข้อมูลลง DB
        $user->update($data);

        // (เราไม่ต้องยุ่งกับ Session (admin_users) แล้ว... เพราะเราแก้ใน DB จริงแล้วค่ะ)
        
        // --- ^^^^ สิ้นสุดตรงนี้ค่ะ ^^^^ ---

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.updated'));
    }

    public function destroy(Request $request, string $userId): RedirectResponse
    {
        // --- VVVV พี่โดนัทแก้โค้ดตรงนี้ (Delete) VVVV ---
        
        // (เราจะเลิกใช้ 'demo user' (findUser) ที่อยู่ใน Session แล้วค่ะ)
        $user = User::where('user_id', $userId)->first();

        if ($user) {
            // ถ้าเจอ User "ตัวจริง" (ใน DB) ... ก็ "ลบ" (Delete) เลย
            $user->delete();
        } else {
            // (กันเหนียว... ถ้า User นั้นยังอยู่ใน Session (demo user) ... ก็ลบจาก Session)
            $storedUsers = $this->storedUsers($request);
            if (isset($storedUsers[$userId])) {
                unset($storedUsers[$userId]);
                $request->session()->put('admin_users', $storedUsers);
            }
            $deleted = $this->deletedUserIds($request);
            $deleted[] = $userId;
            $request->session()->put('admin_users_deleted', array_values(array_unique($deleted)));
        }

        // --- ^^^^ สิ้นสุดตรงนี้ค่ะ ^^^^ ---

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.deleted'));
    }

    /**
     * Demo users displayed on the management screen.
     *
     * @return array<int, array<string, string>>
     */
    private function demoUsers(): array
    {
        // (โค้ด Demo Users นี้... พี่โดนัทขอ 'เก็บไว้' เหมือนเดิมนะคะ)
        // (เผื่อระบบ Login มันยังต้องใช้ User 'admin@example.com' จาก Seeder)
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

    private function nextUserId(): string
    {
        // --- VVVV พี่โดนัทแก้โค้ดตรงนี้ VVVV ---
        // (ให้มันไปหา 'เลขที่' (ID) ล่าสุด... จาก "DB จริง" ... ไม่ใช่จาก Session)
        $latestUser = User::orderBy('user_id', 'desc')->first();
        $max = 0;

        if ($latestUser && preg_match('/USR-(\d+)/', $latestUser->user_id, $matches)) {
            $max = (int) $matches[1];
        }

        // (ถ้า DB ว่างเปล่า... ก็ไปหาจาก 'Demo User' (เผื่อ Seeder ยังไม่รัน))
        if ($max === 0) {
            foreach ($this->demoUsers() as $user) {
                if (preg_match('/USR-(\d+)/', $user['user_id'], $matches)) {
                    $max = max($max, (int) $matches[1]);
                }
            }
        }
        
        // --- ^^^^ สิ้นสุดตรงนี้ค่ะ ^^^^ ---

        return sprintf('USR-%04d', $max + 1);
    }

    private function allUsers(Request $request): array
    {
        // --- VVVV พี่โดนัทแก้โค้ดตรงนี้ VVVV ---
        
        // 1. ดึง User "ตัวจริง" (จาก DB) มาทั้งหมด
        $dbUsers = User::all()->map(function ($user) {
            // (แปลง User 'ตัวจริง' (ใน DB) ... ให้หน้าตา 'เหมือน' (Similar) ... 'Demo User' (ใน Session))
            return [
                'user_id' => $user->user_id,
                'name' => $user->name,
                // (พี่โดนัทไม่รู้ว่า 'department' ของหนิงเก็บ 'key' หรือ 'value' ... เลยต้องเดา!)
                // (พี่แก้... ให้มันไป 'แปล' (trans) ... 'key' (ที่เก็บใน DB) ... กลับมาเป็น 'value' (ชื่อแผนก) ค่ะ)
                'department' => trans('messages.admin_users.form.department.options.' . $user->department) ?? $user->department,
                'authority' => $user->role, // (พี่เดาว่า 'authority' คือ 'role')
                'email' => $user->email,
                'phone' => $user->phone ?? '—',
                // (พี่เดาว่าใน DB ไม่มี 'status' ... เลยใส่ 'active' ไปก่อน)
                'status' => 'active', 
                // (พี่เดาว่าใน DB ไม่มี 'last_login' ... เลยใส่ '—' ไปก่อน)
                'last_login' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '—',
                
                // (พี่โดนัท 'แอบเพิ่ม' field นี้... เพื่อบอกว่าคนนี้ 'ตัวจริง' (Real))
                'is_real' => true, 
            ];
        })->toArray();
        
        // 2. ดึง 'Demo User' (จาก Session)
        $stored = $this->storedUsers($request);
        $deleted = array_flip($this->deletedUserIds($request));
        $demoUsers = [];
        
        foreach ($this->demoUsers() as $user) {
            // (เช็กว่า 'Demo User' คนนี้... 'ซ้ำ' (Duplicate) กับ 'User จริง' (ใน DB) มั้ย)
            $isDuplicate = false;
            foreach ($dbUsers as $dbUser) {
                if ($dbUser['email'] === $user['email'] || $dbUser['user_id'] === $user['user_id']) {
                    $isDuplicate = true;
                    break;
                }
            }
            if ($isDuplicate) continue; // (ถ้าซ้ำ... ก็ 'ข้าม' (Skip) Demo User คนนี้ไป)
            
            // (โค้ดเดิม... สำหรับ 'ลบ' (Delete) Demo User)
            if (isset($deleted[$user['user_id']])) {
                continue;
            }
            if (isset($stored[$user['user_id']])) {
                $demoUsers[] = $stored[$user['user_id']];
                unset($stored[$user['user_id']]);
            } else {
                $demoUsers[] = $user;
            }
        }
        
        // (โค้ดเดิม... สำหรับ 'สร้าง' (Create) Demo User... (ซึ่งเราไม่ใช้แล้ว...))
        // foreach ($stored as $user) {
        //     if (isset($deleted[$user['user_id']])) {
        //         continue;
        //     }
        //     $demoUsers[] = $user;
        // }

        // 3. "รวมร่าง" (Merge) ... User 'ตัวจริง' (DB) ... กับ 'Demo User' (ที่เหลือ)
        return array_merge($dbUsers, $demoUsers);
        
        // --- ^^^^ สิ้นสุดตรงนี้ค่ะ ^^^^ ---
    }

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
        // --- VVVV พี่โดนัทแก้โค้ดตรงนี้ VVVV ---
        
        // 1. หา User "ตัวจริง" (จาก DB) ก่อน
        $userModel = User::where('user_id', $userId)->first();
        
        if ($userModel) {
            // ถ้า "เจอ"... ก็แปลง 'ตัวจริง' (DB) ... ให้หน้าตา 'เหมือน' (Similar) ... 'Demo User' (Session)
            return [
                'user_id' => $userModel->user_id,
                'name' => $userModel->name,
                //'department' => $userModel->department, // (อันนี้... หนิงอาจจะต้องไปแก้หน้า 'form' (view) ให้มัน 'อ่าน' key แทน value)
                'department_key' => $userModel->department, // (พี่แก้... ให้มันส่ง 'key' (เช่น 'sales') กลับไปที่ form)
                'authority' => $userModel->role, // (เดาว่า 'authority' คือ 'role')
                'email' => $userModel->email,
                'phone' => $userModel->phone ?? '—',
                'status' => 'active', // (เดา)
                'last_login' => $userModel->last_login_at ? $userModel->last_login_at->format('Y-m-d H:i') : '—', // (เดา)
                'notify_new_orders' => $userModel->notify_new_orders ?? true,
                'require_password_change' => $userModel->require_password_change ?? false,
                'is_real' => true, // (บอกว่าคนนี้ 'ตัวจริง')
                //'password' => '********', // (เราไม่ส่งรหัสผ่าน 'จริง' (Hash) กลับไปที่หน้า form!)
            ];
        }

        // 2. ถ้า "ไม่เจอ" (Not Found) ... ค่อยไปหาใน 'Demo User' (Session) (เผื่อเป็น 'admin@example.com')
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
        
        // --- ^^^^ สิ้นสุดตรงนี้ค่ะ ^^^^ ---
    }
}