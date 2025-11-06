<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'department',
        'password',
        'role_id',
        'telephone',
        'user_id', // <-- ✨ 1. เพิ่มตัวปัญหาหลัก
        'notify_new_orders', // <-- ✨ 2. เพิ่มตัวที่ขาด
        'require_password_change', // <-- ✨ 3. เพิ่มตัวที่ขาด
    ];

    /**
     * Cache of checked permissions for the current request lifecycle.
     *
     * @var array<string, bool>
     */
    protected array $permissionCache = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The role assigned to the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Permissions granted to the user through their role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'permission_role',
            'role_id',
            'permission_id',
            'role_id',
            'id'
        )->withTimestamps();
    }

    /**
     * Determine if the user has access to a given named route.
     */
    public function hasPermission(?string $routeName): bool
    {
        if (! $routeName) {
            return true;
        }

        if (! $this->role_id) {
            return true;
        }

        if (! array_key_exists($routeName, $this->permissionCache)) {
            $this->permissionCache[$routeName] = $this->permissions()
                ->where('route', $routeName)
                ->exists();
        }

        return $this->permissionCache[$routeName];
    }
}