<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\OrderAdjustmentItem;
use App\Models\OrderItem;
use App\Models\ProductVariant;
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

    /**
     * Phê duyệt yêu cầu điều chỉnh (Bài 4)
     * 
     * @throws DomainException khi yêu cầu không hợp lệ hoặc không thể duyệt
     */
    public function approveAdjustment(int $adjustmentId, int $reviewerId): OrderAdjustment
    {
        return DB::transaction(function () use ($adjustmentId, $reviewerId) {
            // 1. Khóa bi quan bản ghi yêu cầu điều chỉnh để chống duyệt đồng thời
            $adjustment = OrderAdjustment::where('id', $adjustmentId)
                ->with(['items'])
                ->lockForUpdate()
                ->firstOrFail();

            // 2. Kiểm tra yêu cầu còn ở trạng thái pending hay không
            if (!$adjustment->isPending()) {
                throw new DomainException("Yêu cầu này đã được xử lý ({$adjustment->status}), không thể phê duyệt lại.");
            }

            // 3. Khóa bản ghi đơn hàng và kiểm tra đơn còn hợp lệ (chưa bị xuất kho/hủy trong lúc chờ duyệt)
            $order = Order::where('id', $adjustment->order_id)->lockForUpdate()->firstOrFail();
            if (!$order->canBeAdjusted()) {
                throw new DomainException("Đơn hàng đã đổi trạng thái ({$order->status}), không còn hợp lệ để phê duyệt.");
            }

            // 4. Cập nhật chính xác các dòng order_items theo chi tiết điều chỉnh
            foreach ($adjustment->items as $adjItem) {
                $orderItem = OrderItem::where('id', $adjItem->order_item_id)->lockForUpdate()->first();
                if ($orderItem) {
                    $variant = ProductVariant::where('sku', $adjItem->new_sku)->first();
                    $orderItem->update([
                        'sku' => $adjItem->new_sku,
                        'quantity' => $adjItem->new_quantity,
                        'product_variant_id' => $variant ? $variant->id : $orderItem->product_variant_id,
                        'price' => $variant ? $variant->price : $orderItem->price,
                    ]);
                }
            }

            // 5. Cập nhật trạng thái yêu cầu sang APPROVED, lưu người duyệt và thời gian duyệt
            $adjustment->update([
                'status' => 'approved',
                'reviewed_by' => $reviewerId,
                'reviewed_at' => now(),
            ]);

            return $adjustment;
        });
    }

    /**
     * Từ chối yêu cầu điều chỉnh (Bài 4)
     * 
     * @throws DomainException khi yêu cầu không hợp lệ hoặc thiếu lý do
     */
    public function rejectAdjustment(int $adjustmentId, string $rejectReason, int $reviewerId): OrderAdjustment
    {
        return DB::transaction(function () use ($adjustmentId, $rejectReason, $reviewerId) {
            $rejectReason = trim($rejectReason);
            if (empty($rejectReason)) {
                throw new DomainException('Bắt buộc phải nhập lý do từ chối yêu cầu.');
            }

            // 1. Khóa bi quan bản ghi yêu cầu điều chỉnh
            $adjustment = OrderAdjustment::where('id', $adjustmentId)
                ->lockForUpdate()
                ->firstOrFail();

            // 2. Kiểm tra yêu cầu còn pending không
            if (!$adjustment->isPending()) {
                throw new DomainException("Yêu cầu này đã được xử lý ({$adjustment->status}), không thể từ chối lại.");
            }

            // 3. Cập nhật trạng thái sang REJECTED - TUYỆT ĐỐI KHÔNG SỬA ĐƠN HÀNG
            $adjustment->update([
                'status' => 'rejected',
                'rejected_reason' => $rejectReason,
                'reviewed_by' => $reviewerId,
                'reviewed_at' => now(),
            ]);

            return $adjustment;
        });
    }
}
