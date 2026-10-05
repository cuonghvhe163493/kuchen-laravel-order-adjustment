# 🧠 BMAD MEMORY — BÀI 4: PHÊ DUYỆT VÀ TỪ CHỐI YÊU CẦU

> **Điều hướng:** [⏮️ Bài 3: Tạo yêu cầu điều chỉnh](03_bai_3_create_adjustment.md) | [🏠 Mục lục Memory](README.md) | [Bài 5: Phân quyền ma trận ➔](05_bai_5_role_and_permission.md)  
> **Điểm số:** 15 / 15  
> **Trạng thái:** ✅ Đã hoàn thành & Kiểm thử tự động PASS 100%

---

## 1. MỤC TIÊU NGHIỆP VỤ
Xây dựng quy trình xét duyệt yêu cầu điều chỉnh bởi Quản lý kho:
- **Luồng trạng thái khép kín:** `PENDING` $\rightarrow$ `APPROVED` hoặc `REJECTED`. Tuyệt đối không duyệt lại, từ chối lại hoặc đổi trạng thái sau khi đã xử lý xong.
- **Khi Phê duyệt:**
  - Kiểm tra yêu cầu còn `pending`.
  - Khóa và kiểm tra đơn hàng gốc vẫn còn hợp lệ (nếu đơn bị xuất kho hoặc hủy trong lúc chờ duyệt $\rightarrow$ từ chối và rollback).
  - Cập nhật chính xác các dòng `order_items` sang SKU và số lượng mới đề xuất.
  - Ghi nhận người duyệt (`reviewed_by`) và thời gian duyệt (`reviewed_at`).
- **Khi Từ chối:**
  - Bắt buộc phải có lý do từ chối (tối thiểu 5 ký tự).
  - Không được sửa bất kỳ dữ liệu nào của đơn hàng gốc.
  - Ghi nhận người từ chối, thời gian từ chối và lý do từ chối.
- **An toàn giao dịch:** Bọc toàn bộ trong `DB::transaction()` và Pessimistic Lock để chống việc 2 quản lý kho cùng bấm duyệt đồng thời.

---

## 2. QUYẾT ĐỊNH THIẾT KẾ & CODE ARTIFACTS

### 2.1. Phương thức `approveAdjustment()` trong `AdjustmentService.php`
```php
public function approveAdjustment(int $adjustmentId, int $reviewerId): OrderAdjustment
{
    return DB::transaction(function () use ($adjustmentId, $reviewerId) {
        // 1. Khóa bản ghi yêu cầu
        $adjustment = OrderAdjustment::where('id', $adjustmentId)
            ->with(['items'])
            ->lockForUpdate()
            ->firstOrFail();

        if (!$adjustment->isPending()) {
            throw new DomainException("Yêu cầu này đã được xử lý ({$adjustment->status}), không thể phê duyệt lại.");
        }

        // 2. Khóa và kiểm tra đơn hàng gốc
        $order = Order::where('id', $adjustment->order_id)->lockForUpdate()->firstOrFail();
        if (!$order->canBeAdjusted()) {
            throw new DomainException("Đơn hàng đã đổi trạng thái ({$order->status}), không còn hợp lệ để phê duyệt.");
        }

        // 3. Cập nhật chính xác order_items theo từng dòng thay đổi
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

        // 4. Chốt trạng thái approved
        $adjustment->update([
            'status' => 'approved',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);

        return $adjustment;
    });
}
```

### 2.2. Phương thức `rejectAdjustment()` trong `AdjustmentService.php`
- Validate lý do từ chối qua `RejectAdjustmentRequest.php`.
- Khóa bản ghi bằng `lockForUpdate()`, kiểm tra `isPending()`.
- Cập nhật trạng thái sang `rejected`, lưu `rejected_reason`, `reviewed_by`, `reviewed_at`.
- Bảng `order_items` giữ nguyên 100%.

---

## 3. GIAO DIỆN XÉT DUYỆT (`adjustments/show.blade.php`)
- Bảng so sánh trực quan dòng hàng: SKU (Cũ $\rightarrow$ Mới), Số lượng (Cũ $\rightarrow$ Mới), Biến động ($+1, -1$).
- Form bấm "Phê duyệt" có hộp thoại xác nhận JavaScript.
- Modal Bootstrap nhập "Lý do từ chối" khi bấm nút Từ chối.
- Khi đã duyệt hoặc từ chối, ẩn toàn bộ nút hành động và hiển thị thẻ thông báo kết quả.

---

## 4. KIỂM THỬ TỰ ĐỘNG
*File test:* `tests/Feature/ApproveAndRejectAdjustmentTest.php`
1. Test Quản lý kho duyệt thành công; `order_items` cập nhật số lượng mới; ghi nhận người và ngày duyệt.
2. Test chặn duyệt lần hai trên yêu cầu đã được duyệt.
3. Test từ chối bắt buộc lý do; `order_items` không bị thay đổi.
4. Test chặn duyệt yêu cầu đã bị từ chối.
5. Test chặn duyệt nếu đơn hàng bị chuyển trạng thái `exported` trong lúc pending.

---

## 📌 LƯU Ý CHO CÁC AGENT TIẾP THEO
- Logic duyệt/từ chối được bảo vệ ở tầng phân quyền tại Bài 5 (chỉ `warehouse_manager` và `admin` mới được gọi).
- Mọi thông tin phê duyệt và từ chối được lưu trữ trực tiếp vào MySQL để phục vụ xem lịch sử ở Bài 6.
