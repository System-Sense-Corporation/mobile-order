<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $roles = Role::all();
        $permissionGroups = Permission::grouped();
        $groupLabels = Permission::groupLabels();

        return view('admin.users.index', [
            'roles' => $roles,
            'permissionGroups' => $permissionGroups,
            'groupLabels' => $groupLabels,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'department' => ['required', 'string', 'max:255'],
            'authority' => ['required', 'string', Rule::in(Role::keys())],
            'notify_new_orders' => ['nullable', 'boolean'],
            'require_password_change' => ['nullable', 'boolean'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin.users.flash.created'));
    }

    public function updatePermissions(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['nullable'],
            'permissions.*.*' => ['string'],
        ]);

        $validator->after(function ($validator) {
            $data = $validator->getData();
            $submitted = $data['permissions'] ?? [];
            $roleKeys = Role::keys();
            $permissionKeys = Permission::keys();

            foreach ($submitted as $roleKey => $values) {
                if (! in_array($roleKey, $roleKeys, true)) {
                    $validator->errors()->add('permissions', __('messages.admin.users.errors.invalid_role', ['role' => $roleKey]));
                    continue;
                }

                if (! is_array($values)) {
                    $values = $values === null || $values === '' ? [] : [$values];
                }

                foreach ($values as $permissionKey) {
                    if (! in_array($permissionKey, $permissionKeys, true)) {
                        $validator->errors()->add('permissions', __('messages.admin.users.errors.invalid_permission', ['permission' => $permissionKey]));
                    }
                }
            }
        });

        $validated = $validator->validate();

        Role::syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('messages.admin.users.flash.permissions_updated'));
    }
}
