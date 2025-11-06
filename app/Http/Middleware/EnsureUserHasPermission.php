<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // ✅ Admin ผ่านหมด
        if ($user && ($user->role?->name === 'admin' || $user->role_id === 1)) {
            return $next($request);
        }

        // ✅ อนุญาตเส้นทางสาธารณะ/ทุกคนใช้ได้ (ถ้ามี)
        $publicRoutes = [
            'home',
            'orders.create',
            // …เติมตามต้องการ
        ];
        if ($request->routeIs($publicRoutes)) {
            return $next($request);
        }

        // ✅ map อนุญาตราย role (ถ้าต้องการแยก)
        $roleAllowMap = [
            'editor' => [
                'orders.*',
                'products.*',
                'customers.*',
                // ถ้าจะให้เข้าหน้า list ผู้ใช้ได้เฉย ๆ ก็เพิ่ม 'admin.users.index'
            ],
            'viewer' => [
                'orders.index',
                // …
            ],
        ];

        $roleName = $user?->role?->name;
        if ($roleName && isset($roleAllowMap[$roleName])) {
            if ($request->routeIs($roleAllowMap[$roleName])) {
                return $next($request);
            }
        }
// วางไว้ก่อน abort(404);
$alwaysAllow = [
    'admin.users.index',
    'admin.users.create',
    'admin.users.store',
    'admin.users.edit',
    'admin.users.update',
    'admin.users.destroy',
];

if ($request->routeIs($alwaysAllow)) {
    // หรือจะเช็คเพิ่มเติมว่าเฉพาะ admin/editor ก็ได้
    return $next($request);
}

        // ❌ ไม่อนุญาต -> ทำให้เป็น 404 (เดิม)
        abort(404);
    }
}
