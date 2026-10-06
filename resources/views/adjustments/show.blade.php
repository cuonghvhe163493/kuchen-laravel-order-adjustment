@extends('layouts.app')

@section('title', 'Chi tiết yêu cầu ' . $adjustment->code . ' - KÜCHEN PORTAL')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Nút quay lại & Header -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <a href="{{ route('adjustments.index') }}" class="btn btn-outline-secondary btn-sm mb-2 shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách yêu cầu
                </a>
                <h4 class="fw-bold mb-0 text-dark d-flex flex-wrap align-items-center gap-2">
                    <span>Phiếu Điều Chỉnh:</span>
                    <span class="text-primary font-monospace">{{ $adjustment->code }}</span>
                </h4>
            </div>
            <div>
                @if($adjustment->status === 'pending')
                    <span class="badge bg-warning text-dark border border-warning px-3 py-2 fs-6 shadow-sm">
                        <i class="bi bi-hourglass-split me-1"></i> Đang chờ phê duyệt
                    </span>
                @elseif($adjustment->status === 'approved')
                    <span class="badge bg-success px-3 py-2 fs-6 shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Đã phê duyệt
                    </span>
                @elseif($adjustment->status === 'rejected')
                    <span class="badge bg-danger px-3 py-2 fs-6 shadow-sm">
                        <i class="bi bi-x-circle-fill me-1"></i> Đã từ chối
                    </span>
                @endif
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm border-0" role="alert">
                <ul class="mb-0 small ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Banner cảnh báo nguyên tắc Four-Eyes khi người lập phiếu xem -->
        @if(auth()->check() && auth()->id() === $adjustment->created_by && $adjustment->status === 'pending')
            <div class="alert alert-warning border border-warning-subtle shadow-sm d-flex align-items-center gap-3 p-3 mb-4 rounded-3" role="alert">
                <div class="fs-3 text-warning">
                    <i class="bi bi-shield-exclamation"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Nguyên Tắc Bốn Mắt (Four-Eyes Principle & Segregation of Duties)</h6>
                    <div class="small text-secondary">
                        Bạn là người khởi tạo phiếu điều chỉnh này. Nhằm ngăn ngừa xung đột lợi ích và tuân thủ chính sách quản trị chuỗi cung ứng, hệ thống <strong>chặn quyền tự phê duyệt của người tạo</strong>. Phiếu này cần một Quản lý kho độc lập xem xét.
                    </div>
                </div>
            </div>
        @endif

        <!-- Card 1: Thông tin tổng quan -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light py-3 border-0">
                <h6 class="fw-bold mb-0 text-secondary">
                    <i class="bi bi-info-circle me-1"></i> Thông Tin Chung Phiếu Điều Chỉnh
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="small text-muted">Mã đơn hàng liên quan</div>
                        <div class="fw-bold text-primary fs-6 font-monospace">
                            <a href="{{ route('orders.index', ['search' => $adjustment->order->order_code]) }}" class="text-decoration-none">
                                {{ $adjustment->order->order_code ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="small text-muted">Kênh: <span class="badge bg-light text-dark border text-uppercase">{{ $adjustment->order->channel ?? 'N/A' }}</span></div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Người lập yêu cầu</div>
                        <div class="fw-bold text-dark">{{ $adjustment->creator->name ?? 'N/A' }}</div>
                        <div class="small text-muted">Vai trò: <span class="badge bg-secondary text-uppercase">{{ $adjustment->creator->role ?? 'sale' }}</span></div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Thời gian gửi yêu cầu</div>
                        <div class="fw-bold text-dark font-monospace">{{ $adjustment->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Trạng thái đơn hàng gốc</div>
                        <div>
                            @if($adjustment->order->status === 'pending')
                                <span class="badge badge-status-pending px-2 py-1">Chờ xử lý</span>
                            @elseif($adjustment->order->status === 'confirmed')
                                <span class="badge badge-status-confirmed px-2 py-1">Đã xác nhận</span>
                            @elseif($adjustment->order->status === 'exported')
                                <span class="badge badge-status-exported px-2 py-1">Đã xuất kho</span>
                            @elseif($adjustment->order->status === 'cancelled')
                                <span class="badge badge-status-cancelled px-2 py-1">Đã hủy</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: BẢNG SO SÁNH TRỰC QUAN TRƯỚC VÀ SAU ĐIỀU CHỈNH (VISUAL DIFF COMPARISON) -->
        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-arrow-left-right me-1 text-primary"></i> Bảng So Sánh Thay Đổi Chi Tiết (Trước — Sau)
                    </h6>
                    <small class="text-muted">Chi tiết các mặt hàng và biến động giá trị tài chính tương ứng.</small>
                </div>
                <span class="badge bg-light text-secondary border">Đối soát minh bạch</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted border-bottom" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                        <tr>
                            <th style="width: 40px;" class="text-center">#</th>
                            <th style="width: 25%;">Dòng hàng gốc (Trước)</th>
                            <th class="text-center" style="width: 15%;">Số lượng cũ</th>
                            <th style="width: 30%;">Đề xuất mới (Sau)</th>
                            <th class="text-center" style="width: 15%;">Số lượng mới</th>
                            <th class="text-center" style="width: 15%;">Biến động (&Delta;)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adjustment->items as $index => $item)
                            <tr>
                                <td class="text-center text-muted small fw-medium">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">
                                        {{ $item->orderItem->productVariant->product->name ?? 'Sản phẩm KÜCHEN' }}
                                    </div>
                                    <div class="small text-muted font-monospace mt-1">
                                        SKU: <span class="badge bg-light text-secondary border">{{ $item->old_sku }}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary px-3 py-2 fs-6">
                                        {{ $item->old_quantity }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $newVariant = \App\Models\ProductVariant::where('sku', $item->new_sku)->with('product')->first();
                                    @endphp
                                    <div class="fw-bold text-primary">
                                        {{ $newVariant->product->name ?? 'Biến thể mới' }}
                                    </div>
                                    <div class="small text-muted font-monospace mt-1">
                                        SKU mới: <span class="badge bg-primary text-white">{{ $item->new_sku }}</span>
                                        @if($newVariant)
                                            <span class="ms-1">({{ number_format($newVariant->price, 0, ',', '.') }} ₫)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm">
                                        {{ $item->new_quantity }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $diffQty = $item->new_quantity - $item->old_quantity;
                                    @endphp
                                    @if($diffQty > 0)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                            <i class="bi bi-arrow-up-right me-1"></i>+{{ $diffQty }} sản phẩm
                                        </span>
                                    @elseif($diffQty < 0)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                            <i class="bi bi-arrow-down-right me-1"></i>{{ $diffQty }} sản phẩm
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border px-2 py-1">
                                            Chỉ đổi phân loại
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card 3: Lý do điều chỉnh & Lịch sử kiểm toán (Audit Trail) -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-clock-history me-1"></i> Lịch Sử Xử Lý & Dấu Vết Kiểm Toán (Audit Trail)
                </h6>
                <span class="badge bg-light text-secondary border">Bảo đảm toàn vẹn dữ liệu</span>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <label class="fw-bold text-secondary small text-uppercase mb-2">Lý do xin điều chỉnh đơn hàng:</label>
                    <div class="p-3 bg-light rounded border text-dark">
                        <i class="bi bi-chat-quote text-primary me-2"></i>{{ $adjustment->reason }}
                    </div>
                </div>

                <!-- Timeline trực quan theo thời gian thực -->
                <div class="border-start border-2 border-primary ps-4 ms-3 mt-3">
                    <!-- Bước 1: Khởi tạo -->
                    <div class="position-relative mb-4">
                        <span class="position-absolute top-0 start-0 translate-middle p-2 bg-primary border border-white rounded-circle"></span>
                        <div class="small text-muted font-monospace">{{ $adjustment->created_at->format('d/m/Y H:i:s') }}</div>
                        <div class="fw-bold text-dark">Khởi tạo phiếu yêu cầu điều chỉnh</div>
                        <div class="small text-secondary">
                            Người thực hiện: <strong>{{ $adjustment->creator->name ?? 'N/A' }}</strong> 
                            (Vai trò: <span class="badge bg-secondary text-uppercase">{{ $adjustment->creator->role ?? 'sale' }}</span>)
                        </div>
                    </div>

                    <!-- Bước 2: Phê duyệt / Từ chối / Chờ duyệt -->
                    <div class="position-relative">
                        @if($adjustment->status === 'approved')
                            <span class="position-absolute top-0 start-0 translate-middle p-2 bg-success border border-white rounded-circle"></span>
                            <div class="small text-muted font-monospace">{{ $adjustment->reviewed_at?->format('d/m/Y H:i:s') }}</div>
                            <div class="fw-bold text-success">Đã phê duyệt yêu cầu điều chỉnh</div>
                            <div class="small text-secondary">
                                Người phê duyệt: <strong>{{ $adjustment->reviewer->name ?? 'Quản lý kho' }}</strong> 
                                (Vai trò: <span class="badge bg-success text-uppercase">{{ $adjustment->reviewer->role ?? 'kho' }}</span>)
                            </div>
                            <div class="alert alert-success mt-2 py-2 px-3 small mb-0 border-0">
                                <i class="bi bi-check-circle-fill me-1"></i> Số lượng và quy cách mới đã được đồng bộ vào đơn hàng trong hệ thống kho vận.
                            </div>
                        @elseif($adjustment->status === 'rejected')
                            <span class="position-absolute top-0 start-0 translate-middle p-2 bg-danger border border-white rounded-circle"></span>
                            <div class="small text-muted font-monospace">{{ $adjustment->reviewed_at?->format('d/m/Y H:i:s') }}</div>
                            <div class="fw-bold text-danger">Đã từ chối yêu cầu điều chỉnh</div>
                            <div class="small text-secondary">
                                Người từ chối: <strong>{{ $adjustment->reviewer->name ?? 'Quản lý kho' }}</strong> 
                                (Vai trò: <span class="badge bg-danger text-uppercase">{{ $adjustment->reviewer->role ?? 'kho' }}</span>)
                            </div>
                            <div class="alert alert-danger mt-2 py-2 px-3 small mb-0 border-0">
                                <strong>Lý do từ chối:</strong> {{ $adjustment->rejected_reason }}
                            </div>
                        @else
                            <span class="position-absolute top-0 start-0 translate-middle p-2 bg-warning border border-white rounded-circle"></span>
                            <div class="small text-muted">Hiện tại</div>
                            <div class="fw-bold text-warning-emphasis">Đang chờ Quản lý kho xem xét</div>
                            <div class="small text-secondary">Yêu cầu đang ở trạng thái pending, chưa có quyết định phê duyệt hoặc từ chối.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Thao tác Xét duyệt / Từ chối (Modal An Toàn Chuẩn Enterprise) -->
        @if($adjustment->status === 'pending')
            @can('order.adjustment.approve', $adjustment)
                <div class="card border-0 shadow-sm bg-light mb-5">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 p-4">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">
                                <i class="bi bi-shield-check me-1 text-primary"></i> Quyết Định Xét Duyệt Phiếu Điều Chỉnh
                            </h6>
                            <small class="text-muted">Kiểm tra thông tin đối soát trước khi cập nhật đơn hàng vào kho vận.</small>
                        </div>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <!-- Nút Mở Modal Từ Chối -->
                            <button type="button" class="btn btn-outline-danger px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i> Từ Chối Yêu Cầu
                            </button>

                            <!-- Nút Mở Modal Phê Duyệt -->
                            <button type="button" class="btn btn-success px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#approveModal">
                                <i class="bi bi-check2-circle me-1"></i> Phê Duyệt Yêu Cầu
                            </button>
                        </div>
                    </div>
                </div>

                <!-- MODAL XÁC NHẬN PHÊ DUYỆT CHUẨN ENTERPRISE -->
                <div class="modal fade" id="approveModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form method="POST" action="{{ route('adjustments.approve', $adjustment->id) }}">
                            @csrf
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title fs-6 fw-bold">
                                        <i class="bi bi-shield-check me-1"></i> Xác Nhận Phê Duyệt Điều Chỉnh
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <p class="mb-2">Bạn đang tiến hành phê duyệt phiếu <strong>{{ $adjustment->code }}</strong> cho đơn hàng <strong>{{ $adjustment->order->order_code }}</strong>.</p>
                                    <div class="alert alert-warning py-2 px-3 small mb-0 border-0 rounded-3">
                                        <i class="bi bi-info-circle-fill me-1"></i>
                                        Các dòng sản phẩm và số lượng mới sẽ được cập nhật chính thức vào đơn hàng ngay lập tức. Hành động này được ghi nhận vào dấu vết kiểm toán.
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                                    <button type="submit" class="btn btn-success btn-sm px-4 fw-bold">Xác nhận Phê duyệt</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan

            <!-- MODAL NHẬP LÝ DO TỪ CHỐI CHUẨN ENTERPRISE -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="{{ route('adjustments.reject', $adjustment->id) }}">
                        @csrf
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title fs-6 fw-bold">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Từ Chối Yêu Cầu Điều Chỉnh
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label for="rejected_reason" class="form-label fw-bold text-dark small">
                                        Lý do từ chối (bắt buộc) <span class="text-danger">(*)</span>
                                    </label>
                                    <textarea name="rejected_reason" 
                                              id="rejected_reason" 
                                              rows="3" 
                                              class="form-control" 
                                              minlength="5"
                                              placeholder="Nhập lý do chi tiết (ví dụ: Biến thể mới đã hết hàng trong kho, hoặc khách không đồng ý phụ thu)..." 
                                              required></textarea>
                                </div>
                                <div class="small text-muted">
                                    Sau khi từ chối, phiếu sẽ đóng lại và toàn bộ mặt hàng đơn gốc được giữ nguyên không thay đổi.
                                </div>
                            </div>
                            <div class="modal-footer bg-light">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                                <button type="submit" class="btn btn-danger btn-sm px-4 fw-bold">Xác nhận Từ chối</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
