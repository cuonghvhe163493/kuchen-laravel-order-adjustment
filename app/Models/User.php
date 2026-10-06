<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

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

    /**
     * Tự động đồng bộ liên kết bảng role_user khi User được tạo hoặc cập nhật
     */
    protected static function booted(): void
    {
        static::saved(function (User $user) {
            if (!empty($user->role)) {
                $roleModel = Role::where('name', $user->role)->first();
                if ($roleModel) {
                    $user->roles()->syncWithoutDetaching([$roleModel->id]);
                }
            }
        });
    }

    /**
     * Quan hệ Nhiều - Nhiều với Bảng Roles trong Database (RBAC)
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Lấy danh sách tất cả các Permission thuộc về User từ Database
     */
    public function allPermissions(): Collection
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->unique('name');
    }

    protected ?array $cachedPermissionNames = null;

    /**
     * Kiểm tra User có Quyền hạn (Permission) được cấp trong Database hay không
     */
    public function hasPermission(string $permissionName): bool
    {
        // Quản trị viên hệ thống có toàn quyền
        if ($this->isAdmin()) {
            return true;
        }

        // 1. Kiểm tra trong bộ nhớ đệm phân quyền DB của User (Chỉ 1 truy vấn nhẹ)
        if ($this->cachedPermissionNames === null) {
            $this->cachedPermissionNames = \Illuminate\Support\Facades\DB::table('permissions')
                ->join('permission_role', 'permissions.id', '=', 'permission_role.permission_id')
                ->join('role_user', 'permission_role.role_id', '=', 'role_user.role_id')
                ->where('role_user.user_id', $this->id)
                ->pluck('permissions.name')
                ->all();
        }

        if (in_array($permissionName, $this->cachedPermissionNames, true)) {
            return true;
        }

        // 2. Fallback dự phòng theo vai trò cứng (bảo toàn 100% tương thích ngược)
        $fallbackPermissions = [
            'warehouse_manager' => ['order.view', 'order.adjustment.view', 'order.adjustment.approve', 'order.adjustment.reject', 'role.view'],
            'sale' => ['order.view', 'order.adjustment.view', 'order.adjustment.create', 'role.view'],
        ];

        return in_array($permissionName, $fallbackPermissions[$this->role] ?? [], true);
    }

    protected ?array $cachedRoleNames = null;

    /**
     * Kiểm tra User có vai trò tương ứng hay không
     */
    public function hasRole(string|array $roles): bool
    {
        $rolesArray = is_array($roles) ? $roles : [$roles];

        if (!empty($this->role) && in_array($this->role, $rolesArray, true)) {
            return true;
        }

        if ($this->cachedRoleNames === null) {
            $this->cachedRoleNames = \Illuminate\Support\Facades\DB::table('roles')
                ->join('role_user', 'roles.id', '=', 'role_user.role_id')
                ->where('role_user.user_id', $this->id)
                ->pluck('roles.name')
                ->all();
            if (!empty($this->role) && !in_array($this->role, $this->cachedRoleNames, true)) {
                $this->cachedRoleNames[] = $this->role;
            }
        }

        foreach ($rolesArray as $r) {
            if (in_array($r, $this->cachedRoleNames, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tên hiển thị tiếng Việt của Vai trò
     */
    public function getRoleDisplayNameAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Quản trị viên Hệ thống',
            'warehouse_manager' => 'Quản lý Kho vận',
            'sale' => 'Nhân viên Sale (Kinh doanh)',
            default => strtoupper($this->role),
        };
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
        return $this->role === 'sale' || $this->hasRole('sale');
    }

    public function isWarehouseManager(): bool
    {
        return $this->role === 'warehouse_manager' || $this->hasRole('warehouse_manager');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }
}
