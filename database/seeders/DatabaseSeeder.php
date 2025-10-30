<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
        ], [
            'description' => 'Administrator with access to every route',
        ]);

        $normalRole = Role::firstOrCreate([
            'name' => 'normal',
        ], [
            'description' => 'Standard user with limited access',
        ]);

        $allPermission = Permission::firstOrCreate([
            'route' => '*',
        ], [
            'name' => 'all',
        ]);

        $normalRoutes = [
            '/profile',
            '/forgot-password',
            '/order',
            '/orderlist',
            '/order/edit',
            '/master/customer',
            '/master/customer/edit',
            '/master/product/edit',
        ];

        $normalPermissions = collect($normalRoutes)->map(function (string $route) {
            $name = Str::slug($route, '.');

            if ($name === '') {
                $name = 'root';
            }

            return Permission::firstOrCreate([
                'route' => $route,
            ], [
                'name' => $name,
            ]);
        });

        $normalRole->permissions()->sync($normalPermissions->pluck('id')->all());

        $adminRole->permissions()->sync(Permission::pluck('id')->all());

        User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        User::updateOrCreate([
            'email' => 'user@example.com',
        ], [
            'name' => 'Normal User',
            'password' => Hash::make('password'),
            'role_id' => $normalRole->id,
        ]);
    }
}
