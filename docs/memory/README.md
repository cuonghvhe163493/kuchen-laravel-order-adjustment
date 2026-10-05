# 🧠 HỆ THỐNG BMAD MEMORY LOG — KÜCHEN PORTAL

> **Dự án:** `kuchen-laravel-order-adjustment`  
> **Mã đề:** `KUCHEN-DEV-LARAVEL-01`  
> **Mục đích:** Lưu trữ ngữ cảnh kỹ thuật, quyết định thiết kế và tiến trình thực thi của từng bài để các AI Agent và Developer tiếp theo có thể nắm bắt ngay toàn bộ hệ thống mà không cần đọc lại toàn bộ mã nguồn.

---

## 🗺️ BẢN ĐỒ TIẾN TRÌNH & ĐIỀU HƯỚNG LIÊN KẾT (NAVIGATION CHAIN)

Mỗi bài tương ứng với một file Memory độc lập, liên kết chặt chẽ với nhau:

| STT | File Memory | Trạng thái | Điểm | Trọng tâm kỹ thuật cốt lõi |
| :---: | :--- | :---: | :---: | :--- |
| **01** | [01_bai_1_database_and_models.md](01_bai_1_database_and_models.md) | ✅ Hoàn thành | 10/10 | Thiết kế 7 bảng chuẩn, Migrations, Indexes, Eloquent 1-N, Seeder |
| **02** | [02_bai_2_order_list_and_search.md](02_bai_2_order_list_and_search.md) | ✅ Hoàn thành | 10/10 | Giao diện Blade, Eager Loading triệt tiêu N+1, Search/Filter, Phân trang 20 |
| **03** | [03_bai_3_create_adjustment.md](03_bai_3_create_adjustment.md) | ✅ Hoàn thành | 15/15 | FormRequest, `AdjustmentService`, Pessimistic Lock chống race condition |
| **04** | [04_bai_4_approve_and_reject.md](04_bai_4_approve_and_reject.md) | ✅ Hoàn thành | 15/15 | `DB::transaction()`, Duyệt cập nhật kho, Từ chối kèm lý do, Chống duyệt trùng |
| **05** | [05_bai_5_role_and_permission.md](05_bai_5_role_and_permission.md) | ✅ Hoàn thành | 10/10 | `OrderAdjustmentPolicy`, 4 Gates, Chặn 403 ở backend, Role Switcher |
| **06** | [06_bai_6_history_and_handover.md](06_bai_6_history_and_handover.md) | ✅ Hoàn thành | 10/10 | Audit Trail Timeline, Lọc lịch sử theo đơn, Bộ test 27/27, Git bàn giao |

---

## 🏛️ KIẾN TRÚC TỔNG QUAN HỆ THỐNG

```
[Client / Browser]
        │
        ▼
[Routing: web.php]
        │
        ├── [OrderController] ────> Eager Loading ────> [Order / OrderItem]
        │
        └── [AdjustmentController]
                 │
                 ├── [Gate & Policy Check] ──> (403 if unauthorized)
                 │
                 ├── [StoreAdjustmentRequest / RejectAdjustmentRequest]
                 │
                 └── [AdjustmentService] (Core Business Logic)
                          │
                          ├── [DB::transaction()]
                          ├── [Pessimistic Lock: lockForUpdate()]
                          └── Update [order_adjustments] & [order_items]
```

---

## 🚀 HƯỚNG DẪN DÀNH CHO AGENT TIẾP THEO

1. **Khi cần mở rộng thêm chức năng:** Đọc file Memory của bài liên quan nhất để nắm rõ cấu trúc dữ liệu và các ràng buộc nghiệp vụ đã được thiết lập.
2. **Khi chạy test nghiệm thu:** Chạy lệnh `php artisan test` (đảm bảo 27/27 tests luôn xanh).
3. **Khi đẩy code:** Luôn commit lên nhánh `deploy` trước, kiểm tra kỹ lưỡng rồi mới merge và push sang nhánh `product`.
