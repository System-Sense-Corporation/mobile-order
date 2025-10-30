<?php

namespace App\Models\Admin;

use Illuminate\Support\Collection;

class Permission
{
    public function __construct(
        public string $key,
        public string $label,
        public string $description,
        public string $group,
        public string $routeName
    ) {
    }

    public static function all(): Collection
    {
        return collect(config('admin_permissions.permissions', []))
            ->map(
                fn (array $config, string $key) => new self(
                    $key,
                    __($config['label'] ?? ''),
                    __($config['description'] ?? ''),
                    $config['group'] ?? 'general',
                    $config['route'] ?? ''
                )
            );
    }

    public static function grouped(): Collection
    {
        return self::all()->groupBy(fn (self $permission) => $permission->group);
    }

    public static function keys(): array
    {
        return array_keys(config('admin_permissions.permissions', []));
    }

    public static function groupLabels(): array
    {
        return collect(config('admin_permissions.groups', []))
            ->map(fn (string $translationKey) => __($translationKey))
            ->all();
    }
}
