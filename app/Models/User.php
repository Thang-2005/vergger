<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Collection;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone_number',
        'avatar',
        'address',
        'activation_token',
        'role_id',
        'google_id',
    ];


    /* ================== RELATIONS ================== */

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getRolesAttribute(): Collection
    {
        return collect([$this->role])->filter();
    }

    public function isAdminOrStaff(): bool
    {
        $roleName = strtolower($this->role?->name ?? '');
        return in_array($roleName, ['admin', 'staff'], true);
    }

    public function hasPermission(string $permissionName): bool
    {
        if (!$this->role) {
            return false;
        }

        if (strtolower($this->role->name) === 'admin') {
            return true;
        }

        return $this->role->hasPermission($permissionName);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /* ================== ACCESSORS ================== */

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&color=fff';
    }

    /* ================== STATUS CHECK ================== */

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isBanned()
    {
        return $this->status === 'banned';
    }

    public function isDeleted()
    {
        return $this->status === 'deleted';
    }

    /* ================== PASSWORD RESET ================== */

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
