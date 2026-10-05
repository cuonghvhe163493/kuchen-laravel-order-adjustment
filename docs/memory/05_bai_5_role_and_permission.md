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
- `approve(User $user, ?OrderAdjustment $adj)`:
  * SALE: trả về `false` 100%.
  * Vai trò hợp lệ: `warehouse_manager` hoặc `admin`.
  * **Nguyên tắc Segregation of Duties (SoD / Four-Eyes Principle):** Người tạo yêu cầu KHÔNG ĐƯỢC tự mình phê duyệt (`$adjustment->created_by === $user->id` $\rightarrow$ cấm, kể cả Admin).
- `reject(User $user, ?OrderAdjustment $adj)`:
  * SALE: trả về `false` 100%.
  * Người tạo không tự từ chối yêu cầu của mình.

### 3.2. Đăng ký Gates: `app/Providers/AppServiceProvider.php`
Khai báo đầy đủ 4 Gates với đúng chuỗi định danh yêu cầu trong đề bài:
```php
Gate::policy(OrderAdjustment::class, OrderAdjustmentPolicy::class);

Gate::define('order.adjustment.view', [OrderAdjustmentPolicy::class, 'view']);
Gate::define('order.adjustment.create', [OrderAdjustmentPolicy::class, 'create']);
Gate::define('order.adjustment.approve', [OrderAdjustmentPolicy::class, 'approve']);
Gate::define('order.adjustment.reject', [OrderAdjustmentPolicy::class, 'reject']);
```

### 3.3. Bảo vệ đa tầng (Defense-in-Depth): Middleware + Controller Gate
- **Tầng Route Middleware:** Khai báo `can:order.adjustment.create`, `can:order.adjustment.view` trực tiếp trên các Route trong `routes/web.php` để chặn đứng truy cập trái phép ngay tại HTTP pipeline trước khi tới Controller.
- **Tầng Controller Backend (`AdjustmentController.php`):** Tất cả các hành động đều được kiểm thực chặt chẽ qua `Gate::authorize(...)`.
- **Loại bỏ triệt để Hardcoded Fallback:** Chỉ sử dụng `Auth::id()` thực tế, loại bỏ việc fallback gán bừa user làm sai lệch Audit Trail.
- **Middleware Quản lý phiên Demo (`EnsureDemoUserAuthenticated`):** Tự động duy trì phiên làm việc cho môi trường demo trên toàn bộ `web` pipeline, đảm bảo không có lỗ hổng "khách vô danh" gây lỗi 500/session drop.

### 3.4. Giao diện & Tiện ích chuyển đổi vai trò (Role Switcher)
- Trong Blade: Dùng `@can('order.adjustment.create')` và `@can('order.adjustment.approve', $adjustment)`.
- Khi người xem là người tạo đơn: Hiển thị thông báo giải thích rõ nguyên tắc tách biệt nhiệm vụ không được tự duyệt.
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
7. Test người tạo (kể cả Admin) không được tự phê duyệt đơn của mình (Four-Eyes Principle).
8. Test chặn thao túng truyền dòng sản phẩm của đơn hàng khác (Cross-Order Injection).
9. Test chặn gửi trùng lặp dòng sản phẩm trong cùng một yêu cầu.

---

## 📌 LƯU Ý CHO CÁC AGENT TIẾP THEO
- Hệ thống áp dụng kiểm soát quyền 3 lớp: Route Middleware $\rightarrow$ Controller Gate $\rightarrow$ Policy Model.
- Phiên làm việc được quản lý tự động bởi `EnsureDemoUserAuthenticated`. Dùng thanh chuyển đổi vai trò ở góc phải Navbar để test các góc nhìn người dùng khác nhau.
