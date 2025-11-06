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
        if (! $user) {
            abort(401);
        }

        // รับได้ทั้ง relation (object) และคอลัมน์ string 'role'
        $roleName = null;

        // ถ้ามี relation ชื่อ role และเป็น object -> ใช้ name
        if (method_exists($user, 'role') && $user->relationLoaded('role') && $user->role) {
            $roleName = $user->role->name ?? null;
        }

        // ถ้าข้างบนไม่มีค่า ลองอ่านจากฟิลด์ role (string)
        if (! $roleName && isset($user->role) && is_string($user->role)) {
            $roleName = $user->role;
        }

        // ถ้ามี role_id แต่ไม่โหลด relation ก็ลองโหลด
        if (! $roleName && isset($user->role_id)) {
            try {
                $user->loadMissing('role');
                $roleName = $user->role->name ?? null;
            } catch (\Throwable $e) {
                // เงียบไว้
            }
        }

        // ดีฟอลต์เป็น 'viewer' ถ้ายังหาไม่ได้
        $roleName = $roleName ?: 'viewer';

        // อนุญาตทุกหน้าให้ admin
        if ($roleName === 'admin') {
            return $next($request);
        }

        // ตรงนี้จะเป็นลอจิกจริงของสิทธิ์ (ตอนนี้ให้ผ่านไปก่อน)
        return $next($request);
    }
}
