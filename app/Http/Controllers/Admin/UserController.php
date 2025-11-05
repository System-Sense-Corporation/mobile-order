<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash; // <-- VVVV พี่โดนัทเพิ่ม "เครื่องปั่นรหัส" (Hash) มาค่ะ VVVV

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
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['required', 'string', Rule::in($departmentOptions)],
            'authority' => ['required', 'string', Rule::in($roleOptions)],
            'notify_new_orders' => ['nullable', 'boolean'],
            'require_password_change' => ['nullable', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $storedUsers = $this->storedUsers($request);
        $existingUsers = $this->allUsers($request);

        $department = trans('messages.admin_users.form.department.options.' . $validated['department']);

        $userId = $this->nextUserId($existingUsers);

        $storedUsers[$userId] = [
            'user_id' => $userId,
            'name' => $validated['name'],
            'department' => $department,
            'department_key' => $validated['department'],
            'authority' => $validated['authority'],
            'email' => $validated['email'],
            'phone' => ($validated['phone'] ?? null) ?: '—',
            'status' => 'active',
            'last_login' => '—',
            'notify_new_orders' => (bool) ($validated['notify_new_orders'] ?? false),
            'require_password_change' => (bool) ($validated['require_password_change'] ?? false),
            
            // --- VVVV พี่โดนัทเพิ่มบรรทัดนี้ค่ะ VVVV ---
            // 'เข้ารหัส' (Hash) รหัสผ่านก่อน 'บันทึก' (Save)
            'password' => Hash::make($validated['password']),
            // --- ^^^^ สิ้นสุดตรงนี้ค่ะ ^^^^ ---
        ];

        $request->session()->put('admin_users', $storedUsers);
        $this->removeFromDeleted($request, $userId);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.created'));
    }

    public function update(Request $request, string $userId): RedirectResponse
    {
        $user = $this->findUser($request, $userId);

        abort_if(is_null($user), 404);

        $departmentOptions = array_keys((array) trans('messages.admin_users.form.department.options'));
        $roleOptions = array_keys((array) trans('messages.admin_users.roles'));

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['required', 'string', Rule::in($departmentOptions)],
            'authority' => ['required', 'string', Rule::in($roleOptions)],
            'notify_new_orders' => ['nullable', 'boolean'],
            'require_password_change' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // (ช่องรหัสผ่าน... 'ว่าง' (nullable) ได้... ถ้าไม่อยากเปลี่ยน)
        ]);

        $storedUsers = $this->storedUsers($request);
        $department = trans('messages.admin_users.form.department.options.' . $validated['department']);

        // --- VVVV พี่โดนัทแก้ตรรกะตรงนี้ใหม่หมดเลยค่ะ VVVV ---

        // 1. เตรียมข้อมูล User (ยกเว้นรหัสผ่าน)
        $userData = [
            'user_id' => $userId,
            'name' => $validated['name'],
            'department' => $department,
            'department_key' => $validated['department'],
            'authority' => $validated['authority'],
            'email' => $validated['email'],
            'phone' => ($validated['phone'] ?? null) ?: '—',
            'status' => $user['status'] ?? 'active',
            'last_login' => $user['last_login'] ?? '—',
            'notify_new_orders' => (bool) ($validated['notify_new_orders'] ?? false),
            'require_password_change' => (bool) ($validated['require_password_change'] ?? false),
        ];

        // 2. เช็กว่ามีการ "กรอกรหัสผ่านใหม่" (ไม่ว่าง) หรือเปล่า
        if (!empty($validated['password'])) {
            // ถ้า "กรอก"... ก็ให้ 'เข้ารหัส' (Hash) อันใหม่
            $userData['password'] = Hash::make($validated['password']);
        } else {
            // ถ้า "ไม่กรอก" (ว่าง)... ก็ให้ใช้ "รหัสผ่านเดิม" (ที่เคยบันทึกไว้ใน Session)
            $userData['password'] = $user['password'] ?? null; // (กันเหนียว เผื่อ user เก่าไม่มีรหัส)
        }

        // 3. บันทึกข้อมูลที่สมบูรณ์แล้ว
        $storedUsers[$userId] = $userData;
        
        // --- ^^^^ สิ้นสุดตรงนี้ค่ะ ^^^^ ---

        $request->session()->put('admin_users', $storedUsers);
        $this->removeFromDeleted($request, $userId);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.updated'));
    }

    public function destroy(Request $request, string $userId): RedirectResponse
    {
        abort_if(is_null($this->findUser($request, $userId)), 404);

        $storedUsers = $this->storedUsers($request);

        if (isset($storedUsers[$userId])) {
            unset($storedUsers[$userId]);
            $request->session()->put('admin_users', $storedUsers);
            $this->removeFromDeleted($request, $userId);
        } else {
            $deleted = $this->deletedUserIds($request);
            $deleted[] = $userId;
            $request->session()->put('admin_users_deleted', array_values(array_unique($deleted)));
        }

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

    private function nextUserId(array $users): string
    {
        $max = 0;

        foreach ($users as $user) {
            if (preg_match('/USR-(\d+)/', $user['user_id'], $matches)) {
                $max = max($max, (int) $matches[1]);
            }
        }

        return sprintf('USR-%04d', $max + 1);
    }

    private function allUsers(Request $request): array
    {
        $stored = $this->storedUsers($request);
        $deleted = array_flip($this->deletedUserIds($request));
        $users = [];

        foreach ($this->demoUsers() as $user) {
            if (isset($deleted[$user['user_id']])) {
                continue;
            }

            if (isset($stored[$user['user_id']])) {
                $users[] = $stored[$user['user_id']];
                unset($stored[$user['user_id']]);
            } else {
                $users[] = $user;
            }
        }

        foreach ($stored as $user) {
            if (isset($deleted[$user['user_id']])) {
                continue;
            }

            $users[] = $user;
        }

        return $users;
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
        $stored = $this->storedUsers($request);

        if (isset($stored[$userId])) {
            $user = $stored[$userId];

            $user['department_key'] = $user['department_key'] ?? null;
            $user['notify_new_orders'] = $user['notify_new_orders'] ?? true;
            $user['require_password_change'] = $user['require_password_change'] ?? false;

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

                return $user;
            }
        }

        return null;
    }
}