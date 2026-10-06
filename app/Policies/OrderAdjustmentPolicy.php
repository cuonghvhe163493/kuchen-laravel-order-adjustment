<?php

namespace App\Policies;

use App\Models\OrderAdjustment;
use App\Models\User;

class OrderAdjustmentPolicy
{
    /**
     * Quyền: order.adjustment.view (Được phân quyền qua Database RBAC)
     * SALE: Có | Quản lý kho: Có | Admin: Có
     */
    public function view(User $user, ?OrderAdjustment $adjustment = null): bool
    {
        return $user->hasPermission('order.adjustment.view');
    }

    /**
     * Quyền: order.adjustment.create (Được phân quyền qua Database RBAC)
     * SALE: Có | Quản lý kho: Không | Admin: Có
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('order.adjustment.create');
    }

    /**
     * Quyền: order.adjustment.approve (Được phân quyền qua Database RBAC)
     * SALE: Không | Quản lý kho: Có | Admin: Có
     * Tiêu chí cốt lõi:
     * 1. Phải có quyền `order.adjustment.approve` trong DB.
     * 2. Nguyên tắc Segregation of Duties (SoD) & Four-Eyes:
     *    Người tạo yêu cầu tuyệt đối KHÔNG được tự phê duyệt yêu cầu của chính mình (kể cả Admin).
     */
    public function approve(User $user, ?OrderAdjustment $adjustment = null): bool
    {
        if ($user->role === 'sale') {
            return false;
        }

        if (!$user->hasPermission('order.adjustment.approve')) {
            return false;
        }

        // Nguyên tắc Four-Eyes: Chặn tự phê duyệt yêu cầu do chính mình tạo ra
        if ($adjustment && $adjustment->created_by === $user->id) {
            return false;
        }

        return true;
    }

    /**
     * Quyền: order.adjustment.reject (Được phân quyền qua Database RBAC)
     * SALE: Không | Quản lý kho: Có | Admin: Có
     * Người tạo yêu cầu không được tự từ chối yêu cầu của mình.
     */
    public function reject(User $user, ?OrderAdjustment $adjustment = null): bool
    {
        if ($user->role === 'sale') {
            return false;
        }

        if (!$user->hasPermission('order.adjustment.reject')) {
            return false;
        }

        if ($adjustment && $adjustment->created_by === $user->id) {
            return false;
        }

        return true;
    }
}
