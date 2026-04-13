<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    private const PERMISSION_ALIASES = [
        'manage_users' => [
            'users.view',
            'users.manage',
        ],
        'manage_categories' => [
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
            'categories.toggle_status',
        ],
        'manage_products' => [
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
        ],
        'manage_orders' => [
            'orders.view',
            'orders.manage',
        ],
        'manage_contacts' => [
            'contacts.view',
            'contacts.manage',
        ],
        'manage_permissions' => [
            'permissions.view',
            'permissions.manage',
            'permissions.assign',
            'permissions.create',
        ],
    ];

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function hasPermission(string $permissionName): bool
    {
        $permissionName = strtolower($permissionName);

        if ($this->permissions()->whereRaw('LOWER(name) = ?', [$permissionName])->exists()) {
            return true;
        }

        foreach (self::PERMISSION_ALIASES as $legacyPermission => $impliedPermissions) {
            $legacyPermission = strtolower($legacyPermission);
            $impliedPermissions = array_map('strtolower', $impliedPermissions);

            if ($permissionName === $legacyPermission) {
                return $this->permissions()
                    ->whereRaw('LOWER(name) = ?', [$legacyPermission])
                    ->orWhereIn('name', $impliedPermissions)
                    ->exists();
            }

            if (in_array($permissionName, $impliedPermissions, true)) {
                return $this->permissions()
                    ->whereRaw('LOWER(name) = ?', [$legacyPermission])
                    ->exists();
            }
        }

        return false;
    }
}
