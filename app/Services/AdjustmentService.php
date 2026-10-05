<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\OrderAdjustmentItem;
use App\Models\OrderItem;
use DomainException;
use Illuminate\Support\Facades\DB;

class AdjustmentService
{
    /**
     * Tạo yêu cầu điều chỉnh đơn hàng (Bài 3)
     * 
     * @throws DomainException khi vi phạm quy tắc nghiệp vụ
     */
    public function createAdjustment(int $orderId, array $data, int $creatorId): OrderAdjustment
    {
        return DB::transaction(function () use ($orderId, $data, $creatorId) {
            // Khóa bi quan (Pessimistic Locking) bản ghi đơn hàng để chống Race Condition
            $order = Order::where('id', $orderId)->lockForUpdate()->firstOrFail();

            // 1. Kiểm tra đơn hàng còn được phép điều chỉnh hay không
            if (!$order->canBeAdjusted()) {
                throw new DomainException('Đơn hàng đã xuất kho hoặc đã hủy, không thể yêu cầu điều chỉnh.');
            }

            // 2. Không cho phép có đồng thời hai yêu cầu pending cho cùng đơn
            if ($order->adjustments()->where('status', 'pending')->exists()) {
                throw new DomainException('Đơn hàng đang có một yêu cầu điều chỉnh đang chờ xử lý.');
            }

            // 3. Sinh mã yêu cầu duy nhất (ví dụ: ADJ-000001)
            $count = OrderAdjustment::count();
            $code = sprintf('ADJ-%06d', $count + 1);
            while (OrderAdjustment::where('code', $code)->exists()) {
                $count++;
                $code = sprintf('ADJ-%06d', $count + 1);
            }

            // 4. Tạo bản ghi yêu cầu điều chỉnh Header
            $adjustment = OrderAdjustment::create([
                'code' => $code,
                'order_id' => $order->id,
                'created_by' => $creatorId,
                'status' => 'pending',
                'reason' => $data['reason'],
            ]);

            // 5. Lưu chi tiết từng dòng điều chỉnh (Lines)
            // TUYỆT ĐỐI KHÔNG SỬA order_items TẠI THỜI ĐIỂM NÀY
            foreach ($data['items'] as $itemData) {
                /** @var OrderItem $orderItem */
                $orderItem = $order->items()->where('id', $itemData['order_item_id'])->firstOrFail();

                OrderAdjustmentItem::create([
                    'order_adjustment_id' => $adjustment->id,
                    'order_item_id' => $orderItem->id,
                    'old_sku' => $orderItem->sku,
                    'new_sku' => $itemData['new_sku'],
                    'old_quantity' => $orderItem->quantity,
                    'new_quantity' => (int) $itemData['new_quantity'],
                ]);
            }

            return $adjustment;
        });
    }
}
