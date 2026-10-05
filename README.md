# KÜCHEN PORTAL — MODULE YÊU CẦU ĐIỀU CHỈNH ĐƠN HÀNG

> **Mã đề:** `KUCHEN-DEV-LARAVEL-01`  
> **Vị trí:** PHP Laravel Developer  
> **Repository:** `cuonghvhe163493/kuchen-laravel-order-adjustment`  
> **Nhánh phát triển:** `deploy` | **Nhánh nghiệm thu:** `product`  
> **Công nghệ:** PHP 8.3+, Laravel 12, MySQL / MariaDB (XAMPP), Blade, Bootstrap 5.

---

## I. BỐI CẢNH VÀ MỤC TIÊU NGHIỆP VỤ

KÜCHEN PORTAL tiếp nhận đơn hàng đa kênh từ đội ngũ SALE trực tiếp và các sàn TMĐT (Shopee, TikTok Shop, Lazada, Bán lẻ). Khi nhân viên đi đơn nhập sai sản phẩm, SKU hoặc số lượng:
- **Tuyệt đối không sửa trực tiếp vào bảng đơn hàng (`order_items`)** khi xuất kho chưa xong để tránh làm sai lệch dữ liệu kho vật lý và hạch toán kế toán.
- Quy trình chuẩn: Nhân viên SALE gửi **"Yêu cầu điều chỉnh đơn hàng"**, dữ liệu đơn hàng gốc được giữ nguyên cho đến khi **Quản lý kho** hoặc **Admin** kiểm tra và bấm phê duyệt.
- Nếu Quản lý kho phê duyệt: Hệ thống tự động cập nhật lại các dòng mặt hàng trong kho sang SKU và số lượng mới trong một Transaction an toàn.
- Nếu từ chối: Bắt buộc nhập lý do từ chối, dữ liệu đơn hàng gốc không bị thay đổi.

---

## II. DANH SÁCH CHỨC NĂNG ĐÃ HOÀN THÀNH (100% TIÊU CHÍ)

> 🧠 **Hệ thống BMAD Memory Logs:** Chi tiết ngữ cảnh kỹ thuật, thiết kế và test cases của từng bài được lưu trữ tại [docs/memory/README.md](docs/memory/README.md).

| Bài | Hạng mục & Memory Log | Điểm | Mô tả chi tiết kết quả |
| :---: | :--- | :---: | :--- |
| **Bài 1** | [Database, Migration & Model](docs/memory/01_bai_1_database_and_models.md) | **10/10** | Thiết kế chuẩn 7 bảng nghiệp vụ (`users`, `products`, `product_variants`, `orders`, `order_items`, `order_adjustments`, `order_adjustment_items`). Khóa ngoại, index hiệu năng `['order_id', 'status']`, đầy đủ Eloquent Relationships 1-N. |
| **Bài 2** | [Danh sách & Tìm kiếm đơn hàng](docs/memory/02_bai_2_order_list_and_search.md) | **10/10** | Giao diện Blade Bootstrap 5, tìm kiếm theo mã đơn, lọc theo kênh bán, phân trang đúng 20 đơn/trang (`withQueryString()`). Áp dụng Eager Loading triệt tiêu bẫy N+1 query. Nút tạo điều chỉnh hiển thị đúng ma trận điều kiện, ẩn khi đơn đã xuất kho/hủy. |
| **Bài 3** | [Tạo yêu cầu điều chỉnh](docs/memory/03_bai_3_create_adjustment.md) | **15/15** | Validate qua `StoreAdjustmentRequest` (SKU phải tồn tại trong danh mục KÜCHEN, số lượng nguyên dương $\ge 1$, bắt buộc lý do). Dùng **Pessimistic Lock (`lockForUpdate()`)** chống 2 request pending đồng thời. Đơn gốc giữ nguyên 100%. |
| **Bài 4** | [Phê duyệt / Từ chối yêu cầu](docs/memory/04_bai_4_approve_and_reject.md) | **15/15** | Đóng gói trong `AdjustmentService` với `DB::transaction()` và Pessimistic Lock. Duyệt: cập nhật chính xác `order_items`. Từ chối: bắt buộc lý do qua `RejectAdjustmentRequest`. Khóa cứng trạng thái, không cho duyệt lại hay từ chối lại. |
| **Bài 5** | [Phân quyền ma trận người dùng](docs/memory/05_bai_5_role_and_permission.md) | **10/10** | Thiết lập `OrderAdjustmentPolicy` và 4 Gates bắt buộc (`order.adjustment.view`, `create`, `approve`, `reject`). Kiểm soát cứng tại Backend qua `Gate::authorize()` (chặn 403 Forbidden, SALE không thể tự duyệt). Có thanh chuyển vai trò nhanh trên Navbar. |
| **Bài 6** | [Lịch sử yêu cầu & Audit Trail](docs/memory/06_bai_6_history_and_handover.md) | **10/10** | Trang chi tiết hiển thị đầy đủ: Người tạo, đơn liên quan, so sánh trước - sau từng dòng hàng, lý do, người và thời gian duyệt/từ chối, lý do từ chối. Dấu vết lưu 100% vào database. Hỗ trợ xem lịch sử lọc theo từng đơn hàng. |

---

## III. TÀI KHOẢN MẪU (TEST ACCOUNTS)

Hệ thống đã cấu hình sẵn 3 tài khoản đại diện cho 3 vai trò trong ma trận phân quyền (mật khẩu chung: `password`):

| Vai trò | Email đăng nhập | Mật khẩu | Quyền hạn nghiệp vụ |
| :--- | :--- | :--- | :--- |
| **SALE (Đi đơn)** | `sale@kuchen.vn` | `password` | Xem đơn, Xem yêu cầu, **Tạo yêu cầu điều chỉnh** (Bị cấm duyệt/từ chối) |
| **QUẢN LÝ KHO** | `kho@kuchen.vn` | `password` | Xem đơn, Xem yêu cầu, **Phê duyệt**, **Từ chối** (Bị cấm tạo yêu cầu) |
| **ADMIN QUẢN TRỊ** | `admin@kuchen.vn` | `password` | **Toàn quyền** (Xem, Tạo, Phê duyệt, Từ chối) |

> 💡 *Mẹo kiểm thử:* Bạn có thể dùng dropdown góc phải trên thanh Navbar để chuyển đổi 1-click giữa 3 tài khoản mà không cần đăng nhập lại.

---

## IV. HƯỚNG DẪN CÀI ĐẶT & CHẠY DỰ ÁN

### 1. Yêu cầu hệ thống
* PHP $\ge$ 8.2 (Khuyến nghị PHP 8.3)
* Composer $\ge$ 2.0
* MySQL $\ge$ 8.0 hoặc MariaDB $\ge$ 10.4 (XAMPP)

### 2. Các bước cài đặt
```bash
# 1. Clone repository
git clone https://github.com/cuonghvhe163493/kuchen-laravel-order-adjustment.git
cd kuchen-laravel-order-adjustment

# 2. Cài đặt các thư viện phụ thuộc
composer install

# 3. Tạo file cấu hình môi trường từ mẫu
cp .env.example .env

# 4. Sinh khóa ứng dụng Laravel
php artisan key:generate

# 5. Cấu hình cơ sở dữ liệu trong file .env (ví dụ với XAMPP)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=kuchen_order_adjustment
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Chạy Migration và Seed dữ liệu mẫu
php artisan migrate:fresh --seed

# 7. Khởi động Web Server
php artisan serve --port=8000
```
Truy cập hệ thống tại: **`http://localhost:8000`**

---

## V. KIỂM THỬ TỰ ĐỘNG (AUTOMATED TESTING)

Dự án bao gồm bộ kiểm thử tự động toàn diện với **36 test cases (140 assertions)** bao phủ toàn bộ 6 bài và các tính năng mở rộng (kèm theo các test case nâng cao được đề bài khuyến khích như Rollback, Four-Eyes SoD, Concurrency):

```bash
# Chạy toàn bộ test suites
php artisan test
```

### Danh sách các kịch bản kiểm thử:
1. `OrderAdjustmentRelationshipTest`: Kiểm thử quan hệ Eloquent 1-N giữa Order, Item, Adjustment và User.
2. `OrderListAndSearchTest`: Kiểm thử render danh sách, tìm kiếm theo mã đơn, lọc theo sàn TMĐT, phân trang và đo đếm câu query chống N+1.
3. `CreateAdjustmentTest`: 
   - SALE tạo yêu cầu hợp lệ; đơn gốc giữ nguyên không đổi.
   - Đơn đã xuất kho (`exported`) không được tạo yêu cầu.
   - Chặn tuyệt đối việc tạo 2 yêu cầu pending cùng lúc cho 1 đơn.
   - Validation Form Request (lý do bắt buộc, SKU tồn tại, số lượng nguyên dương).
4. `ApproveAndRejectAdjustmentTest`:
   - Quản lý kho duyệt thành công; `order_items` cập nhật chính xác số lượng và SKU mới; ghi người và thời gian duyệt.
   - Yêu cầu đã duyệt không thể duyệt lần hai.
   - Từ chối bắt buộc lý do; đơn hàng gốc không bị thay đổi.
   - Yêu cầu đã từ chối không thể duyệt lại.
   - Đơn hàng đổi trạng thái xuất kho trong lúc chờ duyệt sẽ bị chặn không cho duyệt.
   - **Rollback toàn diện (Khuyến khích trong đề bài)**: Khi xảy ra lỗi giữa chừng trong transaction duyệt, toàn bộ thay đổi đều được rollback nguyên vẹn về ban đầu.
5. `RoleAndPermissionTest`:
   - SALE không thể tự duyệt (bị chặn 403 Forbidden).
   - SALE không thể từ chối (bị chặn 403 Forbidden).
   - Quản lý kho không thể tạo yêu cầu (bị chặn 403 Forbidden).
   - Quản lý kho và Admin duyệt/từ chối thành công.
   - Nguyên tắc Four-Eyes / SoD: Người tạo (kể cả Admin) không được tự phê duyệt đơn của mình (bị chặn 403).
   - Chặn thao túng chéo dòng mặt hàng của đơn hàng khác (Cross-Order Item Injection).
   - Chặn gửi trùng lặp cùng một dòng sản phẩm nhiều lần trong 1 yêu cầu (Distinct Validation).
6. `AdjustmentHistoryTest`:
   - Trang chi tiết hiển thị đầy đủ thông tin kiểm toán (Người tạo, đơn liên quan, SKU và số lượng trước - sau, lý do).
   - Hiển thị người duyệt, thời gian duyệt, lý do từ chối lấy từ database.
   - Lọc lịch sử điều chỉnh theo từng đơn hàng.
7. `AuthenticationFeatureTest`:
   - Đăng nhập hợp lệ và ghi nhận session.
   - Chặn đăng nhập với mật khẩu sai.
   - Đăng ký tài khoản mới chỉ định vai trò (`sale`, `warehouse_manager`, `admin`).
   - Đăng xuất an toàn và xóa session.

---

## VI. BẢN GHI VẤN ĐÁP KỸ THUẬT (Q&A PHỎNG VẤN)

### 1. Tại sao dùng Pessimistic Lock (`lockForUpdate`) thay vì Optimistic Lock?
> Trong môi trường kho vận và bán hàng đa kênh, tần suất xung đột dữ liệu trên cùng 1 đơn hàng khi có nhiều nhân viên cùng thao tác là rất nhạy cảm. Pessimistic Lock giúp khóa độc quyền dòng dữ liệu trong DB trong suốt transaction, đảm bảo request thứ hai phải chờ hoặc bị reject ngay, ngăn chặn triệt để tình trạng Race Condition (2 yêu cầu pending cùng lúc hoặc 2 người cùng duyệt 1 đơn).

### 2. Làm thế nào để giải quyết triệt để lỗi N+1 Query ở Bài 2?
> Trong `OrderController@index`, thay vì để Blade lặp qua các quan hệ động, controller sử dụng Eager Loading:
> ```php
> Order::with(['items.productVariant.product', 'creator', 'pendingAdjustment', 'adjustments'])
> ```
> Nhờ đó, dù hiển thị 20 hay 100 đơn hàng trên trang, số lượng câu truy vấn SQL sinh ra luôn cố định ở mức tối thiểu.

### 3. Xử lý ra sao khi đơn hàng bị xuất kho trong lúc yêu cầu điều chỉnh đang treo chờ duyệt?
> Trong hàm `approveAdjustment()` của `AdjustmentService`, trước khi cập nhật số lượng mới, hệ thống khóa dòng đơn hàng và kiểm tra lại trạng thái:
> ```php
> if (!$order->canBeAdjusted()) {
>     throw new DomainException("Đơn hàng đã đổi trạng thái ({$order->status}), không còn hợp lệ để phê duyệt.");
> }
> ```
> Nếu đơn đã chuyển sang `exported` hoặc `cancelled`, lệnh duyệt sẽ bị hủy ngay lập tức và toàn bộ transaction được rollback.
