<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        // ---- Derive $roleName (robust) ----
        $roleName = null;

        // 1) ถ้าเป็น relation object
        if (is_object($user->role) && method_exists($user->role, 'getAttribute')) {
            $roleName = $user->role->name ?? null;
        }

        // 2) ถ้าเป็นสตริง เช่น column ชื่อ 'role'
        if (! $roleName && is_string($user->role ?? null)) {
            $roleName = $user->role;
        }

        // 3) ถ้าเป็นตัวเลข role_id
        if (! $roleName && is_numeric($user->role_id ?? null)) {
            $roleName = optional(Role::find($user->role_id))->name;
        }

        // 4) Fallback
        $roleName = $roleName ?: 'viewer';

        $routeName = Route::currentRouteName();

        // ---- กฎพื้นฐาน (ปรับได้ตามต้องการ) ----
        $rules = [
            'admin'  => ['*'], // admin เข้าทุก route
            'editor' => [
                'home',
                'orders.*',
                'products.*',
                'customers.*',
                'profile', 'profile.update',
                // ถ้าอยากให้ editor เห็นรายชื่อ user ให้เปิดคอมเมนต์:
                // 'admin.users.index', 'admin.users.edit',
            ],
            'viewer' => [
                'home',
                'orders.index',
                'products',
                'customers',
                'profile', 'profile.update',
            ],
        ];

        if ($this->allowed($rules, $roleName, $routeName)) {
            return $next($request);
        }

        // ปิดไว้เงียบ ๆ ตาม requirement เดิม
        abort(404);
    }

    private function allowed(array $rules, string $role, string $routeName): bool
    {
        $list = $rules[$role] ?? [];
        if (in_array('*', $list, true)) {
            return true;
        }

        foreach ($list as $pattern) {
            if ($pattern === $routeName) {
                return true;
            }
            if (str_ends_with($pattern, '.*')) {
                $prefix = substr($pattern, 0, -2);
                if (str_starts_with($routeName, $prefix)) {
                    return true;
                }
            }
        }

        return false;
    }
}
