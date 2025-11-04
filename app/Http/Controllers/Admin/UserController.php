<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => $this->demoUsers(),
            'permissionModules' => $this->permissionModules(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', [
            'permissionModules' => $this->permissionModules(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $departmentOptions = array_keys((array) trans('messages.admin_users.form.department.options'));
        $roleOptions = array_keys((array) trans('messages.admin_users.roles'));
        $permissionOptions = array_keys($this->permissionModules());

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['required', 'string', Rule::in($departmentOptions)],
            'authority' => ['required', 'string', Rule::in($roleOptions)],
            'notify_new_orders' => ['nullable', 'boolean'],
            'require_password_change' => ['nullable', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in($permissionOptions)],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.created'));
    }

    public function updatePermissions(Request $request): RedirectResponse
    {
        $permissionOptions = array_keys($this->permissionModules());

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['array'],
            'permissions.*.*' => ['string', Rule::in($permissionOptions)],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin_users.flash.permissions_saved'));
    }

    /**
     * Demo users displayed on the management screen.
     *
     * @return array<int, array<string, string>>
     */
    private function demoUsers(): array
    {
        $modules = array_keys($this->permissionModules());

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
                'permissions' => $modules,
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
                'permissions' => [
                    'quotation',
                    'tax_invoice_schedule',
                    'billing_note',
                    'purchase_order',
                    'job_file',
                    'delivery',
                    'loading',
                    'pickup',
                    'inspection',
                    'stock',
                ],
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
                'permissions' => [
                    'quotation',
                    'purchase_order',
                    'delivery',
                    'stock',
                ],
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
                'permissions' => [
                    'delivery',
                    'loading',
                    'pickup',
                    'inspection',
                    'stock',
                ],
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
                'permissions' => [
                    'quotation',
                    'billing_note',
                    'job_file',
                    'delivery',
                ],
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
                'permissions' => [
                    'quotation',
                    'tax_invoice_schedule',
                    'billing_note',
                    'purchase_order',
                    'debit_credit_note',
                    'job_file',
                    'delivery',
                    'inspection',
                    'stock',
                ],
            ],
        ];
    }

    /**
     * Available permission modules that can be toggled for users.
     *
     * @return array<string, array<string, string>>
     */
    private function permissionModules(): array
    {
        return (array) trans('messages.admin_users.permissions.modules');
    }
}
