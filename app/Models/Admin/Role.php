<?php

namespace App\Models\Admin;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Role
{
    public function __construct(
        public string $key,
        public string $label,
        public string $description,
        public array $permissionKeys
    ) {
    }

    public static function all(): Collection
    {
        $assignments = self::loadAssignments();

        return collect(config('admin_permissions.roles', []))
            ->map(
                function (array $config, string $key) use ($assignments) {
                    $permissions = $assignments[$key] ?? ($config['permissions'] ?? []);

                    return new self(
                        $key,
                        __($config['label'] ?? $key),
                        __($config['description'] ?? ''),
                        array_values($permissions)
                    );
                }
            );
    }

    public static function keys(): array
    {
        return array_keys(config('admin_permissions.roles', []));
    }

    public function allows(string $permissionKey): bool
    {
        return in_array($permissionKey, $this->permissionKeys, true);
    }

    public static function syncPermissions(array $input): void
    {
        $permissionKeys = Permission::keys();

        $assignments = [];
        foreach (self::keys() as $roleKey) {
            $values = $input[$roleKey] ?? [];
            if (! is_array($values)) {
                $values = $values === null || $values === '' ? [] : [$values];
            }

            $values = array_values(array_unique(array_intersect($values, $permissionKeys)));
            sort($values);
            $assignments[$roleKey] = $values;
        }

        Storage::disk('local')->put(
            'admin_permission_assignments.json',
            json_encode($assignments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    protected static function loadAssignments(): array
    {
        $path = 'admin_permission_assignments.json';

        if (! Storage::disk('local')->exists($path)) {
            return collect(config('admin_permissions.roles', []))
                ->map(fn (array $config) => array_values($config['permissions'] ?? []))
                ->all();
        }

        $decoded = json_decode(Storage::disk('local')->get($path), true);

        if (! is_array($decoded)) {
            return [];
        }

        return collect($decoded)
            ->only(self::keys())
            ->map(fn ($values) => is_array($values) ? array_values($values) : [])
            ->all();
    }
}
