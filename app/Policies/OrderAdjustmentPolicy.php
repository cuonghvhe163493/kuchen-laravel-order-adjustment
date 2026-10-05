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
     * Tiêu chí cốt lõi:
     * 1. SALE vĩnh viễn không thể phê duyệt.
     * 2. Nguyên tắc Segregation of Duties (SoD) & Four-Eyes:
     *    Người tạo yêu cầu tuyệt đối KHÔNG được tự phê duyệt yêu cầu của chính mình.
     */
    public function approve(User $user, ?OrderAdjustment $adjustment = null): bool
    {
        if ($user->role === 'sale') {
            return false;
        }

        if (!in_array($user->role, ['warehouse_manager', 'admin'], true)) {
            return false;
        }

        // Chặn tự phê duyệt yêu cầu do chính mình tạo ra (kể cả Admin)
        if ($adjustment && $adjustment->created_by === $user->id) {
            return false;
        }

        return true;
    }

    /**
     * Quyền: order.adjustment.reject
     * SALE: Không | Quản lý kho: Có | Admin: Có
     * Người tạo yêu cầu không được tự từ chối yêu cầu của mình.
     */
    public function reject(User $user, ?OrderAdjustment $adjustment = null): bool
    {
        if ($user->role === 'sale') {
            return false;
        }

        if (!in_array($user->role, ['warehouse_manager', 'admin'], true)) {
            return false;
        }

        if ($adjustment && $adjustment->created_by === $user->id) {
            return false;
        }

        return true;
    }
}
