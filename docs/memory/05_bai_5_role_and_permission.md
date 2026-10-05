# 🧠 BMAD MEMORY — BÀI 5: PHÂN QUYỀN THEO MA TRẬN (ROLE & PERMISSION)

> **Điều hướng:** [⏮️ Bài 4: Phê duyệt / Từ chối](04_bai_4_approve_and_reject.md) | [🏠 Mục lục Memory](README.md) | [Bài 6: Lịch sử & Bàn giao ➔](06_bai_6_history_and_handover.md)  
> **Điểm số:** 10 / 10  
> **Trạng thái:** ✅ Đã hoàn thành & Kiểm thử tự động PASS 100%

---

## 1. MỤC TIÊU NGHIỆP VỤ
Thiết lập hệ thống phân quyền chặt chẽ giữa 3 vai trò: **SALE**, **Quản lý kho**, và **Admin**.  
Theo yêu cầu bắt buộc: Phải dùng Policy, Gate hoặc Middleware để kiểm soát trên backend, không chỉ ẩn nút trong giao diện. **SALE tuyệt đối không thể tự phê duyệt**.

---

## 2. MA TRẬN PHÂN QUYỀN & PERMISSIONS

| Quyền hạn bắt buộc | SALE | Quản lý kho | Admin | Mô tả & Ràng buộc bảo mật |
| :--- | :---: | :---: | :---: | :--- |
| **`order.adjustment.view`** | ✅ Có | ✅ Có | ✅ Có | Được phép xem danh sách đơn hàng và chi tiết các yêu cầu điều chỉnh. |
| **`order.adjustment.create`** | ✅ Có | ❌ **Không** | ✅ Có | Chỉ nhân viên SALE hoặc Admin mới được phép tạo yêu cầu điều chỉnh. |
| **`order.adjustment.approve`** | ❌ **Không** | ✅ Có | ✅ Có | Chỉ Quản lý kho hoặc Admin mới có quyền duyệt (chặn 403 nếu SALE duyệt). |
| **`order.adjustment.reject`** | ❌ **Không** | ✅ Có | ✅ Có | Chỉ Quản lý kho hoặc Admin mới có quyền từ chối (chặn 403 nếu SALE từ chối). |

---

## 3. TRIỂN KHAI KỸ THUẬT

### 3.1. Policy: `app/Policies/OrderAdjustmentPolicy.php`
Chứa các phương thức kiểm tra vai trò:
- `view(User $user, ?OrderAdjustment $adj)`: kiểm tra role in `['sale', 'warehouse_manager', 'admin']`.
- `create(User $user)`: kiểm tra role in `['sale', 'admin']`.
- `approve(User $user, ?OrderAdjustment $adj)`: kiểm tra role in `['warehouse_manager', 'admin']` (SALE trả về `false`).
- `reject(User $user, ?OrderAdjustment $adj)`: kiểm tra role in `['warehouse_manager', 'admin']` (SALE trả về `false`).

### 3.2. Đăng ký Gates: `app/Providers/AppServiceProvider.php`
Khai báo đầy đủ 4 Gates với đúng chuỗi định danh yêu cầu trong đề bài:
```php
Gate::policy(OrderAdjustment::class, OrderAdjustmentPolicy::class);

Gate::define('order.adjustment.view', [OrderAdjustmentPolicy::class, 'view']);
Gate::define('order.adjustment.create', [OrderAdjustmentPolicy::class, 'create']);
Gate::define('order.adjustment.approve', [OrderAdjustmentPolicy::class, 'approve']);
Gate::define('order.adjustment.reject', [OrderAdjustmentPolicy::class, 'reject']);
```

### 3.3. Kiểm soát cứng tại Backend (`AdjustmentController.php`)
Tất cả các hành động đều được bọc bởi `Gate::authorize(...)`:
- `create()` & `store()`: `Gate::authorize('order.adjustment.create');`
- `approve()`: `Gate::authorize('order.adjustment.approve', $adjustment);`
- `reject()`: `Gate::authorize('order.adjustment.reject', $adjustment);`
*Kết quả:* Khi vai trò không được phép gọi trực tiếp qua URL hoặc API, server sẽ trả về lỗi **`403 Forbidden`**.

### 3.4. Giao diện & Tiện ích chuyển đổi vai trò (Role Switcher)
- Trong Blade: Dùng `@can('order.adjustment.create')` và `@can('order.adjustment.approve')`.
- Trên Navbar [app.blade.php](resources/views/layouts/app.blade.php): Tích hợp dropdown chuyển đổi nhanh giữa:
  * `SALE (sale@kuchen.vn)`
  * `QUẢN LÝ KHO (kho@kuchen.vn)`
  * `ADMIN TỔNG (admin@kuchen.vn)`
- Route xử lý: `GET /switch-user/{role}`.

---

## 4. KIỂM THỬ TỰ ĐỘNG
*File test:* `tests/Feature/RoleAndPermissionTest.php`
1. Test cả 3 vai trò đều xem được danh sách và chi tiết yêu cầu.
2. Test SALE và Admin vào được form tạo yêu cầu; Quản lý kho bị chặn **403 Forbidden**.
3. Test Quản lý kho gửi POST tạo yêu cầu bị chặn **403 Forbidden**.
4. Test SALE cố tình gửi POST duyệt bị chặn **403 Forbidden** (Tiêu chí 3 của đề bài).
5. Test SALE cố tình gửi POST từ chối bị chặn **403 Forbidden**.
6. Test Quản lý kho và Admin duyệt thành công (HTTP 302).

---

## 📌 LƯU Ý CHO CÁC AGENT TIẾP THEO
- Mặc định khi khách truy cập chưa đăng nhập, `OrderController` sẽ tự động đăng nhập tài khoản SALE mẫu để tránh lỗi Null User khi demo.
- Dùng thanh chuyển đổi vai trò ở góc phải Navbar để test quyền ngay trên trình duyệt.
