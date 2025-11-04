<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the application's roles and permissions.
     */
    public function run(): void
    {
        $permissions = [
            'home' => 'View dashboard',
            'orders.index' => 'View order list',
            'orders.export' => 'Export orders',
            'orders.create' => 'Create orders',
            'orders.store' => 'Save orders',
            'orders.update' => 'Update orders',
            'orders.status' => 'Update order status',
            'orders.destroy' => 'Delete orders',
            'products' => 'View products',
            'products.form' => 'Create products',
            'products.store' => 'Save products',
            'products.update' => 'Update products',
            'products.destroy' => 'Delete products',
            'customers' => 'View customers',
            'customers.form' => 'Create customers',
            'customers.store' => 'Save customers',
            'customers.edit' => 'Edit customers',
            'customers.update' => 'Update customers',
            'customers.destroy' => 'Delete customers',
            'admin.users.index' => 'View user management',
            'admin.users.form' => 'Create users',
            'admin.users.store' => 'Store users',
            'settings' => 'View settings',
            'profile' => 'View profile',
            'profile.update' => 'Update profile',
        ];

        $permissionIds = collect($permissions)->mapWithKeys(function (string $name, string $route) {
            $permission = Permission::query()->updateOrCreate(
                ['route' => $route],
                ['name' => $name]
            );

            return [$route => $permission->id];
        });

        $roles = [
            'admin' => [
                'description' => 'Full access to all areas.',
                'permissions' => array_keys($permissions),
            ],
            'editor' => [
                'description' => 'Manage day-to-day operations.',
                'permissions' => [
                    'home',
                    'orders.index',
                    'orders.export',
                    'orders.create',
                    'orders.store',
                    'orders.update',
                    'orders.status',
                    'products',
                    'products.form',
                    'products.store',
                    'products.update',
                    'customers',
                    'customers.form',
                    'customers.store',
                    'customers.edit',
                    'customers.update',
                    'settings',
                    'profile',
                    'profile.update',
                ],
            ],
            'viewer' => [
                'description' => 'Read-only access to data.',
                'permissions' => [
                    'home',
                    'orders.index',
                    'orders.export',
                    'products',
                    'customers',
                    'profile',
                    'profile.update',
                ],
            ],
        ];

        foreach ($roles as $name => $config) {
            $role = Role::query()->updateOrCreate(
                ['name' => $name],
                ['description' => $config['description']]
            );

            $role->permissions()->sync(array_values(Arr::only($permissionIds, $config['permissions'])));
        }
    }
}
