<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB; // <-- พี่โดนัทเพิ่มบรรทัดนี้จากโค้ดของน้องหนิงนะ

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
        // 1) Validate
        $departmentOptions = array_keys((array) trans('messages.admin_users.form.department.options'));
        $roleOptions = array_keys((array) trans('messages.admin_users.roles'));
        $hasUserId = Schema::hasColumn('users', 'user_id');

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

        // 2) role name -> id
        $role = Role::where('name', $validated['authority'])->firstOrFail();

        // 3) payload
        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telephone' => $validated['phone'] ?? null,
            'role_id' => $role->id,
            'department' => $validated['department'],
            'notify_new_orders' => (bool) ($validated['notify_new_orders'] ?? false),
            'require_password_change' => (bool) ($validated['require_password_change'] ?? false),
            'email_verified_at' => now(),
        ];

        if ($hasUserId) {
            $data['user_id'] = $this->nextUserId();
        }

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.created'));
    }

    public function update(Request $request, string $userId): RedirectResponse
    {
        $hasUserId = Schema::hasColumn('users', 'user_id');

        $user = $hasUserId
            ? User::where('user_id', $userId)->firstOrFail()
            : User::where('id', (int) filter_var($userId, FILTER_SANITIZE_NUMBER_INT))->firstOrFail();

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

        $role = Role::where('name', $validated['authority'])->firstOrFail();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['phone'] ?? null,
            'role_id' => $role->id,
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
        $hasUserId = Schema::hasColumn('users', 'user_id');

        $user = $hasUserId
            ? User::where('user_id', $userId)->first()
            : User::where('id', (int) filter_var($userId, FILTER_SANITIZE_NUMBER_INT))->first();

        if ($user) {
            $user->delete();
        } else {
            // fallback สำหรับ demo/stored users ใน session
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

    private function nextUserId(): string
    {
        if (! Schema::hasColumn('users', 'user_id')) {
            return 'USR-0001';
        }

        $latestUser = User::whereNotNull('user_id')->orderBy('user_id', 'desc')->first();
        $max = 0;

        if ($latestUser && preg_match('/USR-(\d+)/', $latestUser->user_id, $m)) {
            $max = (int) $m[1];
        }

        if ($max === 0) {
            foreach ($this->demoUsers() as $u) {
                if (preg_match('/USR-(\d+)/', $u['user_id'], $m)) {
                    $max = max($max, (int) $m[1]);
                }
            }
        }

        return sprintf('USR-%04d', $max + 1);
    }

    /** รวม Users จาก DB + Demo/Stored */
    private function allUsers(Request $request): array
    {
        // VVVV ✨ นี่คือโค้ดใหม่ของน้องหนิงที่พี่เลือกมา VVVV

        // 1. ดึง "ค่า" ที่ส่งมาจากฟอร์ม (Search และ Authority)
        $search = $request->query('search');
        $authority = $request->query('authority');

        // --- 2. กรองข้อมูล "User จริง" (จาก DB) ---
        $dbQuery = User::whereNotNull('user_id')->with('role');

        // (เพิ่มโค้ดกรอง... ถ้ามี ?search=... ส่งมา)
        if ($search) {
            $dbQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                // (เราไม่ค้นหา 'department' ที่เป็นภาษาไทย... เพราะใน DB มันเก็บเป็น 'key' (sales))
            });
        }

        // (เพิ่มโค้ดกรอง... ถ้ามี ?authority=... ส่งมา)
        if ($authority) {
            // (เราจะค้นหา "ชื่อ" (name) ...ในตาราง 'roles' ที่เราเชื่อม (join) ไว้)
            $dbQuery->whereHas('role', function ($query) use ($authority) {
                $query->where('name', $authority);
            });
        }

        // (รัน DB Query... แล้วแปลงผลลัพธ์)
        $dbUsers = $dbQuery->get()->map(function ($user) {
            return [
                'user_id' => $user->user_id,
                'name' => $user->name,
                'department' => trans('messages.admin_users.form.department.options.' . $user->department) ?? $user->department,
                'authority' => $user->role?->name ?? $user->role_id, 
                'email' => $user->email,
                'phone' => $user->telephone ?? '—',
                'status' => 'active', 
                'last_login' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : '—',
                'is_real'    => true,
            ];
        })->toArray();
        
        // --- 3. กรองข้อมูล "Demo User" (จาก Session) ---
        $stored = $this->storedUsers($request);
        $deleted = array_flip($this->deletedUserIds($request));
        $demo    = []; // <-- ใช้ $demo (จากโค้ดของน้องหนิง)

        foreach ($this->demoUsers() as $u) { // <-- ใช้ $u (จากโค้ดของน้องหนิง)
            $dup = false; // <-- ใช้ $dup (จากโค้ดของน้องหนิง)
            foreach ($dbUsers as $d) {
                if ($d['email'] === $u['email'] || $d['user_id'] === $u['user_id']) {
                    $dup = true; break;
                }
            }
            if ($dup) continue; // <-- ใช้ $dup
            
            if (isset($deleted[$u['user_id']])) { // <-- ใช้ $u
                continue;
            }

            // (เพิ่มโค้ดกรอง... ถ้ามี ?search=... ส่งมา)
            if ($search) {
                $searchLower = strtolower($search);
                $nameMatch = str_contains(strtolower($u['name']), $searchLower); // <-- ใช้ $u
                $emailMatch = str_contains(strtolower($u['email']), $searchLower); // <-- ใช้ $u
                if (!$nameMatch && !$emailMatch) {
                    continue; // (ถ้าไม่เจอ... ข้าม user นี้ไป)
                }
            }

            // (เพิ่มโค้ดกรอง... ถ้ามี ?authority=... ส่งมา)
            if ($authority) {
                if (!isset($u['authority']) || $u['authority'] !== $authority) { // <-- ใช้ $u
                    continue; // (ถ้าสิทธิ์ไม่ตรง... ข้าม user นี้ไป)
                }
            }

            // (ถ้าผ่านหมด... ก็เก็บ user นี้ไว้)
            if (isset($stored[$u['user_id']])) { // <-- ใช้ $u
                $demo[] = $stored[$u['user_id']]; // <-- ใช้ $demo
                unset($stored[$u['user_id']]); // <-- ใช้ $u
            } else {
                $demo[] = $u; // <-- ใช้ $demo และ $u
            }
        }

        // 4. "รวมร่าง" (Merge) ... (ตอนนี้ user ทั้ง 2 กอง... ถูก "กรอง" (Filter) มาเรียบร้อยแล้ว)
        return array_merge($dbUsers, $demo); // <-- ใช้ $demo
    }

    private function storedUsers(Request $request): array
    {
        $stored = $request->session()->get('admin_users', []);
        if (! is_array($stored)) return [];

        $normalized = [];
        foreach ($stored as $u) {
            if (is_array($u) && isset($u['user_id'])) {
                $normalized[$u['user_id']] = $u;
            }
        }
        return $normalized;
    }

    private function deletedUserIds(Request $request): array
    {
        $deleted = $request->session()->get('admin_users_deleted', []);
        if (! is_array($deleted)) return [];
        return array_values(array_filter($deleted, fn ($v) => is_string($v) && $v !== ''));
    }

    private function removeFromDeleted(Request $request, string $userId): void
    {
        $deleted = $this->deletedUserIds($request);
        if (empty($deleted)) return;

        $filtered = array_values(array_filter($deleted, fn ($v) => $v !== $userId));
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
        $hasUserId = Schema::hasColumn('users', 'user_id');

        $q = User::with('role');
        $model = $hasUserId
            ? $q->where('user_id', $userId)->first()
            : $q->where('id', (int) filter_var($userId, FILTER_SANITIZE_NUMBER_INT))->first();

        if ($model) {
            return [
                // VVVV ✨ นี่คือโค้ดส่วนบน (HEAD) ที่พี่เลือกมา เพราะมันใช้ `$model` ถูกต้อง VVVV
                'user_id'                => $hasUserId ? $model->user_id : (string) $model->id,
                'name'                   => $model->name,
                'department_key'         => $model->department,
                'authority'              => $model->role?->name ?? $model->role_id,
                'email'                  => $model->email,
                'phone'                  => $model->telephone ?? '—',
                'status'                 => 'active',
                'last_login'             => $model->last_login_at ? $model->last_login_at->format('Y-m-d H:i') : '—',
                'notify_new_orders'      => $model->notify_new_orders ?? true,
                'require_password_change'=> $model->require_password_change ?? false,
                'is_real'                => true,
            ];
        }

        $stored = $this->storedUsers($request);
        if (isset($stored[$userId])) {
            $u = $stored[$userId];
            $u['department_key']         = $u['department_key']         ?? null;
            $u['notify_new_orders']      = $u['notify_new_orders']      ?? true;
            $u['require_password_change']= $u['require_password_change']?? false;
            $u['is_real']                = false;
            return $u;
        }

        $deleted = array_flip($this->deletedUserIds($request));
        if (isset($deleted[$userId])) return null;

        foreach ($this->demoUsers() as $u) {
            if ($u['user_id'] === $userId) {
                $u['department_key']          = null;
                $u['notify_new_orders']       = true;
                $u['require_password_change'] = false;
                $u['is_real']                 = false;
                return $u;
            }
        }

        return null;
    }

    private function demoUsers(): array
    {
        return [
            ['user_id'=>'USR-1001','name'=>'山田 太郎','department'=>'営業統括部','authority'=>'admin','email'=>'taro.yamada@example.com','phone'=>'090-1234-5678','status'=>'active','last_login'=>'2024-04-18 09:42'],
            ['user_id'=>'USR-1002','name'=>'佐藤 花','department'=>'商品開発部','authority'=>'editor','email'=>'hana.sato@example.com','phone'=>'080-9876-5432','status'=>'active','last_login'=>'2024-04-18 08:15'],
            ['user_id'=>'USR-1003','name'=>'Michael Chen','department'=>'International Sales','authority'=>'viewer','email'=>'michael.chen@example.com','phone'=>'070-3456-7890','status'=>'inactive','last_login'=>'2024-04-12 17:28'],
            ['user_id'=>'USR-1004','name'=>'鈴木 健','department'=>'物流管理部','authority'=>'editor','email'=>'ken.suzuki@example.com','phone'=>'090-2468-1357','status'=>'active','last_login'=>'2024-04-17 19:03'],
            ['user_id'=>'USR-1005','name'=>'Amanda Reyes','department'=>'Customer Success','authority'=>'viewer','email'=>'amanda.reyes@example.com','phone'=>'080-1122-3344','status'=>'suspended','last_login'=>'2024-03-29 11:52'],
            ['user_id'=>'USR-1006','name'=>'高橋 さくら','department'=>'経理部','authority'=>'admin','email'=>'sakura.takahashi@example.com','phone'=>'070-9988-7766','status'=>'active','last_login'=>'2024-04-18 07:55'],
        ];
    }
}