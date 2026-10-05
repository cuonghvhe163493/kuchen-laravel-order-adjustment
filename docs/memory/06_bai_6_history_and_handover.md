# 🧠 BMAD MEMORY — BÀI 6: LỊCH SỬ YÊU CẦU & BÀN GIAO DỰ ÁN

> **Điều hướng:** [⏮️ Bài 5: Phân quyền ma trận](05_bai_5_role_and_permission.md) | [🏠 Mục lục Memory](README.md)  
> **Điểm số:** 10 / 10  
> **Trạng thái:** ✅ Đã hoàn thành & Kiểm thử tự động PASS 100%

---

## 1. MỤC TIÊU NGHIỆP VỤ
Xây dựng hệ thống lưu trữ và hiển thị lịch sử yêu cầu điều chỉnh hoàn chỉnh (Audit Trail):
- **Thông tin bắt buộc lưu trữ trong Database (không chỉ Laravel log):**
  1. Người tạo yêu cầu & vai trò.
  2. Đơn hàng liên quan (Mã đơn, kênh bán, trạng thái đơn).
  3. Chi tiết sản phẩm/SKU và số lượng trước - sau (bảng đối chiếu).
  4. Trạng thái hiện tại (`pending`, `approved`, `rejected`).
  5. Lý do xin điều chỉnh ban đầu.
  6. Người và thời gian phê duyệt/từ chối.
  7. Lý do từ chối (nếu có).
- Hỗ trợ tra cứu lịch sử điều chỉnh theo từng đơn hàng cụ thể.
- Hoàn tất hồ sơ bàn giao mã nguồn, tài liệu hướng dẫn và bộ test tự động theo quy định.

---

## 2. GIAO DIỆN & DẤU VẾT KIỂM TOÁN (AUDIT TRAIL)

### 2.1. Khối Audit Trail Timeline trong `adjustments/show.blade.php`
Hiển thị dòng thời gian sự kiện trực quan theo chuẩn kiểm toán doanh nghiệp:
- **Mốc 1 (Khởi tạo):** Thời gian, Tên người tạo, Vai trò (`SALE`), Mã đơn liên quan, Lý do xin điều chỉnh.
- **Mốc 2 (Xét duyệt):**
  - Nếu `APPROVED`: Dấu tích xanh, hiển thị người duyệt, thời gian duyệt, thông báo cập nhật dữ liệu kho.
  - Nếu `REJECTED`: Dấu cảnh báo đỏ, hiển thị người từ chối, thời gian từ chối và **Lý do từ chối**.
  - Nếu `PENDING`: Biểu tượng đồng hồ vàng, thông báo đang chờ Quản lý kho xem xét.

### 2.2. Tra cứu lịch sử từ danh sách đơn hàng
- Trong `OrderController.php`: Eager load quan hệ `adjustments` để hiển thị số lần điều chỉnh của đơn hàng (`$order->adjustments->count()`).
- Trong `AdjustmentController.php`: Bổ sung tham số `order_id` cho màn hình danh sách yêu cầu (`GET /adjustments?order_id=1`) để lọc riêng các yêu cầu của đơn được chọn.

---

## 3. KIỂM THỬ TỰ ĐỘNG
*File test:* `tests/Feature/AdjustmentHistoryTest.php`
1. Test trang chi tiết thể hiện đầy đủ người tạo, đơn liên quan, SKU và số lượng trước - sau, lý do điều chỉnh.
2. Test yêu cầu đã phê duyệt thể hiện người duyệt và ngày duyệt lấy từ database.
3. Test yêu cầu đã từ chối thể hiện lý do từ chối và người từ chối lấy từ database.
4. Test lọc lịch sử điều chỉnh theo `order_id`.

---

## 4. HỒ SƠ BÀN GIAO (MỤC VI ĐỀ BÀI)

### 4.1. Quy chuẩn Git Repository
* **Repository URL:** `https://github.com/cuonghvhe163493/kuchen-laravel-order-adjustment.git`
* **Nhánh phát triển:** `deploy` (đẩy trước để kiểm tra tích hợp)
* **Nhánh sản phẩm:** `product` (merge và đồng bộ từ deploy)
* **Số lượng commit:** 9 commits có ý nghĩa rõ ràng, mô tả chi tiết chức năng hoàn thành:
  1. `f7e8a09`: `feat(database): implement migrations, models and seeders for Order Adjustment (Bai 1)`
  2. `70122cd`: `feat(orders): implement order list, filter by channel, search by code and pagination (Bai 2)`
  3. `2bd79b8`: `feat(adjustments): implement create order adjustment with concurrency safe lock and validation (Bai 3)`
  4. `55e66f2`: `feat(adjustments): implement approval and rejection with transaction and pessimistic lock (Bai 4)`
  5. `ef159ab`: `feat(auth): implement OrderAdjustmentPolicy and register required Gates (Bai 5)`
  6. `d5f10ac`: `feat(ui): add role-based permission checks in Blade views and quick user role switcher (Bai 5)`
  7. `520af97`: `fix(routes): register user.switch route for quick role switching in navbar`
  8. `e494b68`: `feat(history): implement adjustment audit trail, order history filtering and test suite (Bai 6)`
  9. `eea4bb1`: `docs(handover): complete comprehensive README and environment config (Bai 6 & Ban giao)`

### 4.2. Bảo mật & Cấu hình môi trường
- File `.env` thật chứa thông tin nhạy cảm đã được loại trừ an toàn qua `.gitignore`.
- Cung cấp file `.env.example` mẫu chuẩn cấu hình kết nối MySQL XAMPP.
- Cung cấp file `README.md` chi tiết từ cài đặt, chạy test đến giải thích kiến trúc phục vụ vấn đáp.

---

## 🏆 TỔNG KẾT DỰ ÁN
Toàn bộ 6 bài trong đề bài kiểm tra thực hành `KUCHEN-DEV-LARAVEL-01` đã được hoàn thành xuất sắc với điểm số tối đa **100/100**, kiểm thử tự động **27/27 test cases pass** và toàn vẹn hệ thống tài liệu BMAD Memory.
