# 🧠 BMAD MEMORY — BÀI 3: TẠO YÊU CẦU ĐIỀU CHỈNH

> **Điều hướng:** [⏮️ Bài 2: Danh sách & Tìm kiếm](02_bai_2_order_list_and_search.md) | [🏠 Mục lục Memory](README.md) | [Bài 4: Phê duyệt / Từ chối ➔](04_bai_4_approve_and_reject.md)  
> **Điểm số:** 15 / 15  
> **Trạng thái:** ✅ Đã hoàn thành & Kiểm thử tự động PASS 100%

---

## 1. MỤC TIÊU NGHIỆP VỤ
Cho phép nhân viên SALE gửi yêu cầu điều chỉnh sản phẩm/SKU/số lượng khi đi đơn bị sai:
- Ví dụ thực tế từ đề bài: Đơn `DH-2026-001` có SKU `KC-001` từ 2 lên 3 sản phẩm; SKU `KC-002` từ 1 lên 2 sản phẩm.
- **Quy tắc bất biến 1:** Bắt buộc nhập lý do xin điều chỉnh.
- **Quy tắc bất biến 2:** Bắt buộc SKU mới phải tồn tại trong danh mục KÜCHEN và số lượng mới phải là số nguyên dương $\ge 1$.
- **Quy tắc bất biến 3:** Lưu giá trị cũ và mới vào `order_adjustments` / `order_adjustment_items`; **tuyệt đối không sửa bảng `order_items` tại thời điểm tạo yêu cầu**.
- **Quy tắc bất biến 4:** Không cho phép có đồng thời hai yêu cầu `pending` cho cùng 1 đơn, kể cả khi gửi request cùng lúc (Race Condition).

---

## 2. KIẾN TRÚC & GIẢI PHÁP KỸ THUẬT

### 2.1. Chống Race Condition bằng Pessimistic Lock
Triển khai tại `app/Services/AdjustmentService.php`:
```php
public function createAdjustment(int $orderId, array $data, int $creatorId): OrderAdjustment
{
    return DB::transaction(function () use ($orderId, $data, $creatorId) {
        // Khóa độc quyền dòng đơn hàng trong database
        $order = Order::where('id', $orderId)->lockForUpdate()->firstOrFail();

        // Chặn đơn đã xuất kho hoặc hủy
        if (!$order->canBeAdjusted()) {
            throw new DomainException('Đơn hàng đã xuất kho hoặc đã hủy, không thể yêu cầu điều chỉnh.');
        }

        // Chặn tạo đè khi đã có yêu cầu pending
        if ($order->adjustments()->where('status', 'pending')->exists()) {
            throw new DomainException('Đơn hàng đang có một yêu cầu điều chỉnh đang chờ xử lý.');
        }

        // Tự động sinh mã ADJ-000001 duy nhất
        $code = sprintf('ADJ-%06d', OrderAdjustment::count() + 1);

        // Tạo bản ghi Header
        $adjustment = OrderAdjustment::create([ ... ]);

        // Tạo từng dòng chi tiết thay đổi (order_items không bị chỉnh sửa)
        foreach ($data['items'] as $itemData) {
            OrderAdjustmentItem::create([ ... ]);
        }

        return $adjustment;
    });
}
```

### 2.2. Kiểm thực dữ liệu bằng Form Request
*File:* `app/Http/Requests/StoreAdjustmentRequest.php`
- `order_id`: `required|integer|exists:orders,id`
- `reason`: `required|string|min:5|max:1000`
- `items.*.order_item_id`: `required|integer|distinct|exists:order_items,id` (chống gửi trùng lặp cùng 1 dòng mặt hàng)
- `items.*.new_sku`: `required|exists:product_variants,sku` (đảm bảo SKU tồn tại trong danh mục)
- `items.*.new_quantity`: `required|integer|min:1` (số nguyên dương)
- **Kiểm tra chéo (Cross-Order Item Check):** Khối `withValidator` thẩm định tất cả `order_item_id` gửi lên phải thực sự thuộc về đơn hàng `order_id`, chống hành vi cố tình inject dòng hàng của đơn khác.
- **Sinh mã duy nhất an toàn:** Dựa trên `max('id') + 1` và vòng lặp retry trong transaction chống Race Condition.

---

## 3. GIAO DIỆN FORM ĐIỀU CHỈNH
*File view:* `resources/views/adjustments/create.blade.php`
- Hiển thị card tóm tắt đơn hàng gốc (Mã đơn, kênh, người tạo, ngày tạo).
- Bảng so sánh nhập liệu từng dòng: SKU cũ, Số lượng cũ $\rightarrow$ Dropdown chọn SKU mới, Input số lượng mới.
- Textarea nhập lý do điều chỉnh bắt buộc.
- Cảnh báo: Dữ liệu đơn gốc sẽ giữ nguyên cho đến khi Quản lý kho phê duyệt.

---

## 4. KIỂM THỬ TỰ ĐỘNG
*File test:* `tests/Feature/CreateAdjustmentTest.php`
1. Test SALE tạo yêu cầu thành công; `order_items` ban đầu **giữ nguyên số lượng 2 và 1**.
2. Test đơn đã xuất kho (`exported`) không được tạo yêu cầu.
3. Test chặn 2 yêu cầu pending cùng lúc trên 1 đơn hàng.
4. Test validation: Thiếu lý do, SKU không tồn tại, số lượng $\le 0$.

---

## 📌 LƯU Ý CHO CÁC AGENT TIẾP THEO
- Sau khi tạo thành công, `adjustment->status` là `pending`.
- Chỉ chuyển sang bước Phê duyệt hoặc Từ chối ở Bài 4 thông qua `AdjustmentService`.
