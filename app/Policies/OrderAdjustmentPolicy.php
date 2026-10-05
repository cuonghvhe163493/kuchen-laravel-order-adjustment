<?php

namespace App\Policies;

use App\Models\OrderAdjustment;
use App\Models\User;

class OrderAdjustmentPolicy
{
    /**
     * Quyền: order.adjustment.view
     * SALE: Có | Quản lý kho: Có | Admin: Có
     */
    public function view(User $user, ?OrderAdjustment $adjustment = null): bool
    {
        return in_array($user->role, ['sale', 'warehouse_manager', 'admin']);
    }

    /**
     * Quyền: order.adjustment.create
     * SALE: Có | Quản lý kho: Không | Admin: Có
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['sale', 'admin']);
    }

    /**
     * Quyền: order.adjustment.approve
     * SALE: Không | Quản lý kho: Có | Admin: Có
     * Đề bài: "SALE không thể tự phê duyệt"
     */
    public function approve(User $user, ?OrderAdjustment $adjustment = null): bool
    {
        if ($user->role === 'sale') {
            return false;
        }

        return in_array($user->role, ['warehouse_manager', 'admin']);
    }

    /**
     * Quyền: order.adjustment.reject
     * SALE: Không | Quản lý kho: Có | Admin: Có
     */
    public function reject(User $user, ?OrderAdjustment $adjustment = null): bool
    {
        if ($user->role === 'sale') {
            return false;
        }

        return in_array($user->role, ['warehouse_manager', 'admin']);
    }
}
