@extends('layouts.app')

@section('title', 'Chi tiết yêu cầu ' . $adjustment->code . ' - KÜCHEN PORTAL')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Nút quay lại & Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('adjustments.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách yêu cầu
                </a>
                <h4 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                    <span>Yêu Cầu Điều Chỉnh:</span>
                    <span class="text-primary">{{ $adjustment->code }}</span>
                </h4>
            </div>
            <div>
                @if($adjustment->status === 'pending')
                    <span class="badge bg-warning text-dark border border-warning px-3 py-2 fs-6">
                        <i class="bi bi-hourglass-split me-1"></i> Đang chờ phê duyệt
                    </span>
                @elseif($adjustment->status === 'approved')
                    <span class="badge bg-success px-3 py-2 fs-6">
                        <i class="bi bi-check-circle-fill me-1"></i> Đã phê duyệt
                    </span>
                @elseif($adjustment->status === 'rejected')
                    <span class="badge bg-danger px-3 py-2 fs-6">
                        <i class="bi bi-x-circle-fill me-1"></i> Đã từ chối
                    </span>
                @endif
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0 small ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Card 1: Thông tin tổng quan -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light py-3 border-0">
                <h6 class="fw-bold mb-0 text-secondary">
                    <i class="bi bi-info-circle me-1"></i> Thông Tin Chung
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="small text-muted">Mã đơn hàng liên quan</div>
                        <div class="fw-bold text-primary fs-6">{{ $adjustment->order->order_code ?? 'N/A' }}</div>
                        <div class="small text-muted">Kênh: <span class="text-uppercase">{{ $adjustment->order->channel ?? 'N/A' }}</span></div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Người tạo yêu cầu</div>
                        <div class="fw-bold">{{ $adjustment->creator->name ?? 'N/A' }}</div>
                        <div class="small text-muted">Vai trò: {{ $adjustment->creator->role ?? 'Sale' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Thời gian gửi yêu cầu</div>
                        <div class="fw-bold">{{ $adjustment->created_at->format('d/m/Y H:i:s') }}</div>
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

        <!-- Card 2: So sánh chi tiết trước và sau điều chỉnh -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-arrow-left-right me-1"></i> So Sánh Thay Đổi Chi Tiết (Trước — Sau)
                </h6>
                <small class="text-muted">Các mặt hàng sẽ được cập nhật chính xác vào đơn hàng khi Quản lý kho duyệt.</small>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light small text-uppercase text-muted">
                        <tr>
                            <th style="width: 40px;" class="text-center">#</th>
                            <th>Mặt hàng</th>
                            <th class="text-center" style="width: 25%;">SKU (Trước → Sau)</th>
                            <th class="text-center" style="width: 25%;">Số lượng (Trước → Sau)</th>
                            <th class="text-center" style="width: 15%;">Biến động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adjustment->items as $index => $item)
                            <tr>
                                <td class="text-center text-muted small">{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold">
                                        {{ $item->orderItem->productVariant->product->name ?? 'Sản phẩm KÜCHEN' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-secondary border px-2 py-1">{{ $item->old_sku }}</span>
                                    <i class="bi bi-arrow-right mx-1 text-primary"></i>
                                    <span class="badge bg-primary text-white px-2 py-1">{{ $item->new_sku }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary px-2 py-1 fs-6">{{ $item->old_quantity }}</span>
                                    <i class="bi bi-arrow-right mx-1 text-primary"></i>
                                    <span class="badge bg-success px-2 py-1 fs-6">{{ $item->new_quantity }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $diff = $item->new_quantity - $item->old_quantity;
                                    @endphp
                                    @if($diff > 0)
                                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                            +{{ $diff }} sản phẩm
                                        </span>
                                    @elseif($diff < 0)
                                        <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">
                                            {{ $diff }} sản phẩm
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted border px-2 py-1">
                                            Chỉ đổi SKU
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card 3: Lý do xin điều chỉnh & Nhật ký duyệt/từ chối -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold text-secondary mb-2">
                    <i class="bi bi-chat-left-text me-1"></i> Lý do xin điều chỉnh:
                </h6>
                <div class="p-3 bg-light rounded border mb-3">
                    {{ $adjustment->reason }}
                </div>

                @if($adjustment->status === 'approved')
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-0" role="alert">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                        <div>
                            <strong>Đã phê duyệt bởi:</strong> {{ $adjustment->reviewer->name ?? 'Quản lý kho' }} 
                            vào lúc <strong>{{ $adjustment->reviewed_at?->format('d/m/Y H:i:s') }}</strong>.
                            <div class="small text-success">Đơn hàng gốc đã được cập nhật số lượng mới trong kho.</div>
                        </div>
                    </div>
                @elseif($adjustment->status === 'rejected')
                    <div class="alert alert-danger mb-0" role="alert">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-x-circle-fill fs-4"></i>
                            <div>
                                <strong>Đã từ chối bởi:</strong> {{ $adjustment->reviewer->name ?? 'Quản lý kho' }} 
                                vào lúc <strong>{{ $adjustment->reviewed_at?->format('d/m/Y H:i:s') }}</strong>.
                            </div>
                        </div>
                        <div class="p-2 bg-white rounded border border-danger-subtle small">
                            <strong>Lý do từ chối:</strong> {{ $adjustment->rejected_reason }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Card 4: Bảng thao tác Phê duyệt / Từ chối (Bài 4, Bài 5) -->
        @if($adjustment->status === 'pending')
            @can('order.adjustment.approve')
                <div class="card border-0 shadow-sm bg-light mb-5">
                    <div class="card-body d-flex justify-content-between align-items-center p-4">
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">
                                <i class="bi bi-shield-check me-1 text-primary"></i> Quyền Quản Lý Kho / Admin
                            </h6>
                            <small class="text-muted">Xem xét kỹ chi tiết điều chỉnh trước khi xác nhận phê duyệt hoặc từ chối.</small>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Nút Từ chối (Mở Modal) -->
                            <button type="button" class="btn btn-outline-danger px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i> Từ Chối Yêu Cầu
                            </button>

                            <!-- Form Phê duyệt -->
                            <form method="POST" action="{{ route('adjustments.approve', $adjustment->id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn PHÊ DUYỆT yêu cầu này? Số lượng trong đơn hàng sẽ được cập nhật ngay lập tức.');">
                                @csrf
                                <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                                    <i class="bi bi-check2-circle me-1"></i> Phê Duyệt Yêu Cầu
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning d-flex align-items-center gap-2 mb-5" role="alert">
                    <i class="bi bi-shield-lock-fill fs-4"></i>
                    <div>
                        <strong>Phân quyền (Bài 5):</strong> Tài khoản của bạn đang có vai trò <strong>{{ strtoupper(auth()->user()->role ?? 'KHÁCH') }}</strong> (không có quyền phê duyệt/từ chối).
                        Chỉ <strong>Quản lý kho</strong> hoặc <strong>Admin</strong> mới có quyền phê duyệt hoặc từ chối yêu cầu này.
                    </div>
                </div>
            @endcan

            <!-- Modal Nhập Lý Do Từ Chối -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('adjustments.reject', $adjustment->id) }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title fs-6 fw-bold">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Từ Chối Yêu Cầu Điều Chỉnh
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="rejected_reason" class="form-label fw-bold">
                                        Lý do từ chối <span class="text-danger">(*)</span>
                                    </label>
                                    <textarea name="rejected_reason" 
                                              id="rejected_reason" 
                                              rows="3" 
                                              class="form-control" 
                                              placeholder="Nhập lý do từ chối (bắt buộc, tối thiểu 5 ký tự)..." 
                                              required></textarea>
                                </div>
                                <div class="small text-muted">
                                    Sau khi từ chối, yêu cầu sẽ đóng lại và đơn hàng gốc được giữ nguyên không thay đổi.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-danger btn-sm px-3 fw-bold">Xác nhận Từ chối</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
