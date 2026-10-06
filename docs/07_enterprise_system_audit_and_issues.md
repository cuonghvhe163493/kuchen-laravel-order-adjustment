# BÁO CÁO KIỂM TOÁN HỆ THỐNG & DANH MỤC LỖI CẦN CẢI TIẾN
**Dự án:** KÜCHEN Enterprise Order Adjustment Portal  
**Phiên bản:** v1.2.0-Enterprise  
**Ngày kiểm toán:** 06/10/2026  
**Đơn vị thực hiện:** Liên minh Agent Kiến trúc Frontend & Backend (Antigravity AI Multi-Agent Team)

---

## 1. TỔNG QUAN HIỆN TRẠNG
Sau khi hoàn thành phần chấm thi nghiệm thu cơ bản của bài toán KÜCHEN (Quản trị điều phối đơn hàng và phê duyệt điều chỉnh theo nguyên tắc Four-Eyes), hệ thống đã đáp ứng đúng các tiêu chí nghiệp vụ. Tuy nhiên, để đưa sản phẩm ra trình diễn chính thức cho khách hàng doanh nghiệp (Showcase & Pitching), một cuộc kiểm toán toàn diện đã được tiến hành trên cả hai tầng Frontend (Giao diện / Trải nghiệm người dùng) và Backend (Kiến trúc dữ liệu / An ninh bảo mật / Hiệu năng).

Cuộc kiểm toán phát hiện một số điểm khuyết, rủi ro tiềm ẩn và các cơ hội nâng cấp sản phẩm lên đẳng cấp thương mại cao cấp.

---

## 2. DANH MỤC LỖI & ĐIỂM YẾU PHÁT HIỆN

### 2.1. Phân hệ Frontend & UI/UX (Giao diện người dùng)
| Mã | Phân hệ | Mô tả lỗi / Điểm yếu | Rủi ro với Khách hàng | Mức độ | Giải pháp khắc phục |
|---|---|---|---|---|---|
| **FE-01** | Header / Navbar | Menu "Yêu cầu điều chỉnh" thiếu badge thông báo số lượng phiếu đang chờ xử lý (`pending`). | Quản lý kho và Giám đốc không nắm được khối lượng công việc tồn đọng ngay tức thì khi nhìn vào thanh điều hướng. | Trung bình | Bổ sung Counter Badge động (`+N` chờ duyệt) trên Header khi người dùng đăng nhập có quyền xem/duyệt. |
| **FE-02** | Orders List | Màn hình danh sách đơn hàng chỉ có lọc `channel` và `search`, thiếu bộ lọc theo `status` đơn hàng (Chờ xử lý, Đã xác nhận, Đã xuất kho, Đã hủy). | Khó khăn cho nhân viên khi muốn tra cứu nhanh các đơn hàng đủ điều kiện điều chỉnh (`pending`, `confirmed`). | Cao | Bổ sung thanh trạng thái Filter Tabs (Pills) cho `status` kết hợp query parameter chuẩn. |
| **FE-03** | Orders List | Bảng đơn hàng hiển thị danh sách SKU và số lượng nhưng thiếu **Tổng giá trị đơn hàng (VND)**. | Khách hàng/Ban điều hành không nhìn thấy giá trị thương mại của đơn hàng khi đối soát. | Trung bình | Bổ sung cột Tổng tiền (`₫XX.XXX.XXX`) với hàm tính toán tổng giá trị realtime từ các order items. |
| **FE-04** | Create Adjustment | Khi thay đổi biến thể (SKU) hoặc số lượng, form không hiển thị trước mức độ chênh lệch tài chính ($\Delta$ giá tiền). | Nhân viên Sale không ước tính được sự thay đổi ngân sách của khách hàng trước khi bấm gửi duyệt. | Cao | Tích hợp **Bộ tính toán tài chính thời gian thực (Live Price Delta Calculator)** hiển thị số tiền chênh lệch trước/sau. |
| **FE-05** | Show Adjustment | Bảng chi tiết yêu cầu chưa có dạng **So sánh trực quan (Visual Diff Table)** giữa dòng gốc và dòng điều chỉnh. | Người phê duyệt phải tự nhẩm xem sản phẩm nào bị đổi, số lượng tăng/giảm bao nhiêu. | Cao | Thiết kế bảng so sánh Trước vs Sau với cột Delta $\Delta$ (+/- SL, +/- Tiền) có màu sắc nhận diện trực quan. |
| **FE-06** | Show Adjustment | Nút Duyệt và Từ chối đang submit trực tiếp qua form POST, thiếu Modal xác nhận phòng ngừa thao tác nhầm (Accidental Clicks). | Quản lý kho có thể click nhầm phê duyệt đơn sai mà không có bước xác nhận bảo vệ. | Nghiêm trọng | Bổ sung Modal xác nhận Phê duyệt và Modal Từ chối bắt buộc nhập lý do chi tiết ($\ge 5$ ký tự). |
| **FE-07** | Show Adjustment | Thông báo về nguyên tắc Four-Eyes chưa trực quan khi người tạo xem phiếu của chính mình. | Người dùng thắc mắc tại sao nút Duyệt bị ẩn mà không có giải thích ngữ cảnh nghiệp vụ. | Trung bình | Hiển thị Banner Segregation of Duties (SoD) màu vàng giải thích rõ: *"Bạn là người lập phiếu này, tuân thủ nguyên tắc Four-Eyes không được tự phê duyệt"*. |
| **FE-08** | Register Page | Giao diện đăng ký tài khoản còn đơn sơ, chưa đồng bộ với phong cách siêu xe Đức công nghệ cao của trang Đăng nhập. | Trải nghiệm người dùng bị đứt gãy, thiếu tính thẩm mỹ đồng bộ thương hiệu KÜCHEN. | Trung bình | Nâng cấp trang Register lên chuẩn 3D German Engineering Luxury với thẻ thông tin bảo mật và mắt ẩn/hiện mật khẩu. |

---

### 2.2. Phân hệ Backend & Bảo mật (Kiến trúc & Dữ liệu)
| Mã | Phân hệ | Mô tả lỗi / Điểm yếu | Rủi ro Kỹ thuật | Mức độ | Giải pháp khắc phục |
|---|---|---|---|---|---|
| **BE-01** | AdjustmentService | Trong hàm `approveAdjustment` và `rejectAdjustment`, bản ghi `OrderAdjustment` chưa được áp dụng `lockForUpdate()`. | Race condition nếu 2 quản trị viên cùng mở màn hình và nhấn Duyệt/Từ chối đồng thời. | Nghiêm trọng | Bổ sung `lockForUpdate()` trên bản ghi `OrderAdjustment` trong transaction xử lý phê duyệt/từ chối. |
| **BE-02** | OrderController | Backend `OrderController::index` chỉ lọc `channel` và `search`, chưa xử lý query param `status`. | Không hỗ trợ API/Filter trạng thái đơn hàng khi FE gửi request lọc. | Cao | Bổ sung điều kiện `when($status !== '', ...)` trong query builder của `OrderController`. |
| **BE-03** | Order Model | Model `Order` thiếu helper tính tổng giá trị tài chính của đơn hàng. | Lặp code tính tiền ở nhiều Blade view khác nhau. | Thấp | Bổ sung accessor / method `totalAmount()` vào Model `Order` tính tổng `quantity * price`. |
| **BE-04** | Validation | Form từ chối (`reject`) cần đảm bảo lý do từ chối có ý nghĩa và ghi nhận đầy đủ vào Audit Trail. | Người duyệt có thể từ chối mà để trống lý do hoặc nhập ký tự vô nghĩa. | Cao | Sử dụng `RejectAdjustmentRequest` với luật bắt buộc `reason` từ 5 đến 1000 ký tự. |
| **BE-05** | View Composer | Badge số lượng chờ duyệt trên Header cần dữ liệu mà không làm tăng truy vấn N+1 ở từng Controller. | Phải truyền biến thủ công từ mọi controller hoặc gây chậm trang. | Trung bình | Sử dụng View Composer hoặc query đếm tối ưu cached cho navbar badge. |

---

## 3. LỘ TRÌNH THỰC THI CHUẨN HÓA
1. **Bước 1:** Ban hành Hợp đồng kỹ thuật FE - BE (`docs/08_fe_be_technical_contract.md`).
2. **Bước 2:** Cập nhật Backend Service (`AdjustmentService`, `OrderController`, `Order` model) khóa Pessimistic Lock & lọc dữ liệu.
3. **Bước 3:** Nâng cấp toàn diện các giao diện Blade (`app.blade.php`, `orders/index.blade.php`, `adjustments/create.blade.php`, `adjustments/show.blade.php`, `adjustments/index.blade.php`, `auth/register.blade.php`).
4. **Bước 4:** Soạn thảo Tài liệu Kịch bản Trình diễn Khách hàng (`docs/09_customer_showcase_and_operations_manual.md`).
5. **Bước 5:** Chạy kiểm thử tự động toàn diện và đồng bộ kho mã nguồn Git.
