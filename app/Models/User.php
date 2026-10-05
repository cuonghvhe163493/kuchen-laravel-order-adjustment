<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function createdAdjustments(): HasMany
    {
        return $this->hasMany(OrderAdjustment::class, 'created_by');
    }

    public function reviewedAdjustments(): HasMany
    {
        return $this->hasMany(OrderAdjustment::class, 'reviewed_by');
    }

    public function isSale(): bool
    {
        return $this->role === 'sale';
    }

    public function isWarehouseManager(): bool
    {
        return $this->role === 'warehouse_manager';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
