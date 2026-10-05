# 🧠 BMAD MEMORY — BÀI 2: DANH SÁCH VÀ TÌM KIẾM ĐƠN HÀNG

> **Điều hướng:** [⏮️ Bài 1: Database & Models](01_bai_1_database_and_models.md) | [🏠 Mục lục Memory](README.md) | [Bài 3: Tạo yêu cầu điều chỉnh ➔](03_bai_3_create_adjustment.md)  
> **Điểm số:** 10 / 10  
> **Trạng thái:** ✅ Đã hoàn thành & Kiểm thử tự động PASS 100%

---

## 1. MỤC TIÊU NGHIỆP VỤ
Xây dựng giao diện danh sách đơn hàng cho nhân viên:
- Thể hiện: Mã đơn, kênh bán, trạng thái đơn, các mặt hàng (Tên sản phẩm, SKU và số lượng).
- Tìm kiếm theo mã đơn hàng; Lọc theo kênh bán (Sale, Shopee, TikTok, Lazada, Bán lẻ).
- Phân trang chuẩn 20 đơn/trang.
- Chỉ hiển thị nút "Tạo yêu cầu điều chỉnh" cho các đơn đủ điều kiện (chưa xuất kho và chưa bị hủy).
- Chặn cứng tại backend nếu đơn đã xuất kho hoặc hủy.

---

## 2. QUYẾT ĐỊNH KIẾN TRÚC & TỐI ƯU HÓA (CHỐNG N+1 QUERY)

### 2.1. Eager Loading trong `OrderController.php`
Bẫy lớn nhất trong đề bài là lỗi N+1 Query khi lặp qua danh sách đơn hàng và danh sách sản phẩm.  
**Giải pháp triển khai:**
```php
$orders = Order::query()
    ->with([
        'items.productVariant.product', // Nạp chi tiết hàng, biến thể và sản phẩm cha
        'creator',                      // Nạp người tạo đơn
        'pendingAdjustment',            // Nạp yêu cầu đang pending nếu có
        'adjustments',                  // Nạp toàn bộ lịch sử điều chỉnh
    ])
    ->when($search !== '', fn($q) => $q->where('order_code', 'like', "%{$search}%"))
    ->when($channel !== '', fn($q) => $q->where('channel', $channel))
    ->latest('id')
    ->paginate(20)
    ->withQueryString();
```
*Hiệu quả:* Giảm từ hàng chục câu query xuống còn **dưới 10 câu truy vấn cố định** cho bất kỳ số lượng đơn hàng nào hiển thị trên trang.

---

## 3. MA TRẬN ĐIỀU KIỆN HIỂN THỊ NÚT TRÊN BLADE UI

Trong file `resources/views/orders/index.blade.php`:

| Trạng thái đơn | Tình trạng yêu cầu điều chỉnh | Giao diện hiển thị | Hành vi |
| :--- | :--- | :--- | :--- |
| `pending` / `confirmed` | Chưa có yêu cầu nào | Nút xanh **"Yêu cầu điều chỉnh"** (nếu là SALE/Admin) | Bấm mở form Bài 3 |
| `pending` / `confirmed` | Đã có yêu cầu `pending` | Badge vàng **"Đang chờ duyệt: ADJ-xxxx"** | Khóa nút, không cho tạo đè |
| `exported` | Bất kỳ | Badge xám **"Đã xuất kho"** | Ẩn/khóa nút tạo |
| `cancelled` | Bất kỳ | Badge xám **"Đã hủy"** | Ẩn/khóa nút tạo |

---

## 4. GIAO DIỆN & PHÂN TRANG BOOTSTRAP 5
- Khai báo `Paginator::useBootstrapFive()` tại `app/Providers/AppServiceProvider.php`.
- Giữ nguyên query param tìm kiếm khi chuyển trang: `->withQueryString()`.
- Layout chung [app.blade.php](resources/views/layouts/app.blade.php) tích hợp Bootstrap 5 CDN và badge nhận diện thương hiệu KÜCHEN.

---

## 5. KIỂM THỬ TỰ ĐỘNG
*File test:* `tests/Feature/OrderListAndSearchTest.php`  
- Test render danh sách đơn hàng (HTTP 200).
- Test tìm kiếm theo mã đơn `?search=DH-2026-001`.
- Test lọc theo kênh bán `?channel=shopee`.
- Test ẩn nút điều chỉnh trên đơn đã xuất kho/hủy.
- Test đo đếm câu query SQL (khẳng định $\le 10$ queries, không bị N+1).

---

## 📌 LƯU Ý CHO CÁC AGENT TIẾP THEO
- Nút "Yêu cầu điều chỉnh" trỏ tới route `route('adjustments.create', ['order_id' => $order->id])`.
- Đơn hàng có phương thức `$order->canBeAdjusted()` trả về `false` nếu trạng thái là `exported` hoặc `cancelled`.
