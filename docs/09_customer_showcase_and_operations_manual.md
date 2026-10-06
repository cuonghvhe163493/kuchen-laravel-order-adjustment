# KỊCH BẢN TRÌNH DIỄN KHÁCH HÀNG & CẨM NANG VẬN HÀNH (SHOWCASE & SOP)
**Khách hàng mục tiêu:** Ban Giám đốc Doanh nghiệp, Trưởng phòng Chuỗi Cung ứng & Logistics, Giám đốc CNTT (CIO/CTO)  
**Sản phẩm:** KÜCHEN Enterprise Portal - Phân hệ Điều phối Đơn hàng & Kiểm soát Phân quyền Four-Eyes  
**Phiên bản phát hành:** v1.2.0-Enterprise  

---

## 1. GIỚI THIỆU SẢN PHẨM & GIÁ TRỊ CỐT LÕI (VALUE PROPOSITIONS)
KÜCHEN Enterprise Portal là giải pháp quản trị điều phối đơn hàng đa kênh (E-commerce OMS) kết hợp kiểm soát kho vận chính xác theo tiêu chuẩn kỹ nghệ Đức:
1. **Kiểm soát tính toàn vẹn tài chính (Financial Integrity):** Mọi sự thay đổi về quy cách sản phẩm, số lượng, biến thể phát sinh sau khi chốt đơn đều phải qua quy trình xét duyệt minh bạch có bảng so sánh trước/sau rõ ràng.
2. **Nguyên tắc Four-Eyes Principle (Kiểm soát 4 mắt):** Chống gian lận và xung đột lợi ích nội bộ - Người lập phiếu điều chỉnh tuyệt đối không thể tự duyệt đơn do mình tạo ra.
3. **Phân quyền Động thuần Database (Database-driven RBAC):** Mọi quyền hạn và vai trò được lưu trữ và quản lý trực tiếp trong cơ sở dữ liệu MySQL, dễ dàng mở rộng và tùy biến mà không cần can thiệp mã nguồn.
4. **Trải nghiệm Đẳng cấp cao (Executive UX):** Thiết kế trực quan, hiệu ứng 3D thể thao Đức, tương tác vi mô mượt mà, thông báo tài chính tức thời.

---

## 2. KỊCH BẢN TRÌNH DIỄN SẢN PHẨM 5 HỒI (5-ACT SHOWCASE SCRIPT)

### Hồi 1: Cổng Đăng Nhập Doanh Nghiệp & An Ninh Bảo Mật
- **Hành động:** Mở màn hình `/login`.
- **Điểm nhấn thuyết trình:**
  - Card 3D siêu xe thể thao Đức Porsche/AMG Heritage với vệt sáng Specular Glare trượt mượt mà theo đầu chuột và chiều sâu Z-Depth.
  - Nền Antigravity Interactive Dot Grid phản ứng tinh tế với con trỏ chuột và làn sóng nước lan tỏa khi click.
  - Cơ chế phòng thủ: Tích hợp Rate Limiter chống tấn công Brute-Force (tối đa 5 lần sai/phút), xác thực token CSRF.
  - Tiện ích Trình diễn (Showcase Feature): Bộ 3 nút **Autofill 1-Click** cho 3 vai trò `SALE`, `QUẢN LÝ KHO`, `ADMIN` giúp người xem dễ dàng quan sát luồng đăng nhập chính quy mà không tốn thời gian gõ bàn phím.

### Hồi 2: Trung Tâm Điều Phối Đơn Hàng Đa Kênh (`/orders`)
- **Hành động:** Đăng nhập tài khoản Sale (`sale@kuchen.vn`). Vào trang `/orders`.
- **Điểm nhấn thuyết trình:**
  - Tổng quan số lượng đơn hàng và giá trị tài chính bằng VNĐ chuẩn xác cho từng đơn hàng.
  - Bộ lọc đa chiều: Lọc nhanh theo kênh bán (Shopee, TikTok, Lazada, Sale trực tiếp) và theo trạng thái (Chờ xử lý, Đã xác nhận, Đã xuất kho, Đã hủy).
  - Khóa logic thông minh: Đơn hàng đã xuất kho hoặc đã hủy sẽ hiển thị ổ khóa an toàn, ngăn chặn việc tạo yêu cầu sai quy trình.

### Hồi 3: Tạo Yêu Cầu Điều Chỉnh Kèm Dự Báo Tài Chính Real-time (`/adjustments/create`)
- **Hành động:** Bấm "Yêu cầu điều chỉnh" trên đơn hàng đang chờ xử lý.
- **Điểm nhấn thuyết trình:**
  - Form điều chỉnh cho phép đổi phân loại sản phẩm (ví dụ: đổi từ bếp đôi sang nồi chiên không dầu) hoặc tăng giảm số lượng.
  - **Live Financial Delta Calculator:** Khi nhân viên thay đổi số lượng hoặc chọn SKU mới, hệ thống tự động tính toán tổng tiền cũ vs tổng tiền mới và hiển thị ngay mức độ chênh lệch tiền ($\Delta$ chênh lệch: `+ ₫2.500.000` hoặc `- ₫1.000.000`) trước khi gửi duyệt.
  - Bấm "Gửi yêu cầu điều chỉnh": Hệ thống sử dụng khóa bi quan Pessimistic Locking chống tranh chấp dữ liệu.

### Hồi 4: Quy Trình Phê Duyệt & Kiểm Soát Chéo Four-Eyes (`/adjustments/{id}`)
- **Hành động:** 
  1. Sale cố bấm vào phiếu vừa tạo: Nút phê duyệt bị khóa kèm Banner thông báo: *"Nguyên tắc Four-Eyes: Bạn là người lập phiếu nên không được tự phê duyệt"*.
  2. Đổi sang tài khoản Quản lý kho (`kho@kuchen.vn`): Nút "Phê duyệt" và "Từ chối" xuất hiện.
  3. Quản lý kho xem **Bảng so sánh trực quan Trước/Sau (Visual Diff Table)**: Từng dòng sản phẩm hiển thị rõ SKU cũ vs SKU mới, Số lượng cũ vs Số lượng mới, Thành tiền cũ vs Thành tiền mới.
  4. Quản lý kho bấm "Phê duyệt": Modal xác nhận an toàn hiện lên trước khi cập nhật kho vận.
  5. Nếu từ chối: Bắt buộc nhập lý do chi tiết để lưu vào Audit Trail.

### Hồi 5: Bảng Ma Trận Phân Quyền Database (RBAC Matrix) (`/roles`)
- **Hành động:** Mở trang `/roles`.
- **Điểm nhấn thuyết trình:**
  - Toàn bộ vai trò (Admin, Quản lý kho, Sale) và danh sách 7 quyền hạn nghiệp vụ được trực quan hóa theo từng phân hệ trong CSDL.
  - Bảng danh sách nhân viên nội bộ cùng vai trò thực tế lưu trữ trong bảng `role_user`.
  - Khách hàng yên tâm về khả năng tùy biến phân quyền cho các phòng ban khác (Kế toán, CSKH, Trưởng kho...) trong tương lai mà không cần viết lại mã nguồn.

---

## 3. CẨM NANG VẬN HÀNH TIÊU CHUẨN (STANDARD OPERATING PROCEDURES - SOP)

### 3.1. Dành cho Nhân viên Kinh doanh (Sales Rep)
1. **Tiếp nhận yêu cầu:** Khi khách hàng thông báo đổi mẫu sản phẩm hoặc thay đổi số lượng, kiểm tra trạng thái đơn hàng trên portal.
2. **Kiểm tra trạng thái:** Đơn hàng phải ở trạng thái `pending` hoặc `confirmed`. Nếu đơn đã `exported` (đã xuất kho), hướng dẫn khách theo quy trình đổi trả hàng hóa sau nhận.
3. **Lập phiếu điều chỉnh:** Nhập lý do cụ thể và kiểm tra mức chênh lệch tài chính hiển thị trên màn hình trước khi gửi duyệt.
4. **Theo dõi tiến độ:** Theo dõi trạng thái phiếu trên màn hình `/adjustments`.

### 3.2. Dành cho Quản lý Kho vận (Warehouse Manager)
1. **Kiểm tra phiếu chờ duyệt:** Theo dõi badge đỏ `+N` chờ duyệt trên thanh điều hướng Header.
2. **Đối soát tồn kho và quy cách:** Mở chi tiết phiếu để xem Bảng so sánh Trước/Sau.
3. **Quyết định xử lý:**
   - Nếu đủ hàng: Bấm nút "Phê duyệt" và xác nhận trên Modal. Đơn hàng gốc sẽ được cập nhật sản phẩm mới ngay lập tức.
   - Nếu hết hàng hoặc không phù hợp: Bấm nút "Từ chối" và điền lý do chi tiết để nhân viên Sale nhận thông báo và hỗ trợ khách hàng.

---

## 4. CHECKLIST SẴN SÀNG TRIỂN KHAI PRODUCTION
- [x] Database Migrations & RBAC Seeding hoàn tất không lỗi.
- [x] 100% Unit & Feature Tests Passed (40/40 test cases).
- [x] Pessimistic Locking ngăn chặn xung đột dữ liệu đồng thời.
- [x] Segregation of Duties (Four-Eyes Principle) hoạt động chặt chẽ.
- [x] Giao diện Responsive chuẩn máy tính bảng và điện thoại di động.
- [x] Rate Limiting chống tấn công thử mật khẩu liên tục.
- [x] Header & Footer doanh nghiệp đầy đủ thông tin hỗ trợ và bản quyền.
