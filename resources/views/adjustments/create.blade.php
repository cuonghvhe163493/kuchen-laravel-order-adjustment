@extends('layouts.app')

@section('title', 'Tạo yêu cầu điều chỉnh - Đơn hàng ' . $order->order_code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Nút quay lại & Tiêu đề -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách đơn hàng
                </a>
                <h4 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Tạo Yêu Cầu Điều Chỉnh Đơn Hàng
                </h4>
            </div>
            <div>
                <span class="badge bg-primary px-3 py-2 fs-6">
                    Mã đơn: <strong>{{ $order->order_code }}</strong>
                </span>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-octagon-fill me-1"></i> Vui lòng kiểm tra lại thông tin:</h6>
                <ul class="mb-0 small ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Card Thông tin đơn hàng gốc -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light py-3 border-0">
                <h6 class="fw-bold mb-0 text-secondary">
                    <i class="bi bi-info-circle me-1"></i> Thông Tin Đơn Hàng Gốc
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="small text-muted">Kênh bán</div>
                        <div class="fw-bold text-uppercase">{{ $order->channel }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Trạng thái hiện tại</div>
                        <div>
                            @if($order->status === 'pending')
                                <span class="badge badge-status-pending px-2 py-1">Chờ xử lý</span>
                            @elseif($order->status === 'confirmed')
                                <span class="badge badge-status-confirmed px-2 py-1">Đã xác nhận</span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Người tạo đơn</div>
                        <div class="fw-bold">{{ $order->creator->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Thời gian tạo đơn</div>
                        <div class="fw-bold">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tạo Yêu Cầu Điều Chỉnh -->
        <form method="POST" action="{{ route('adjustments.store') }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-primary">
                        <i class="bi bi-table me-1"></i> Danh Sách Mặt Hàng Cần Điều Chỉnh
                    </h6>
                    <small class="text-muted">Chọn SKU mới và số lượng mới mong muốn cho từng dòng sản phẩm.</small>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light small text-uppercase text-muted">
                            <tr>
                                <th style="width: 40px;" class="text-center">#</th>
                                <th style="width: 35%;">Dòng hàng hiện tại (Trước)</th>
                                <th style="width: 15%;" class="text-center">Số lượng cũ</th>
                                <th style="width: 35%;">SKU đề xuất mới (Sau)</th>
                                <th style="width: 15%;" class="text-center">Số lượng mới (*)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $index => $item)
                                <tr>
                                    <td class="text-center text-muted small">
                                        {{ $index + 1 }}
                                        <input type="hidden" name="items[{{ $index }}][order_item_id]" value="{{ $item->id }}">
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $item->productVariant->product->name ?? 'Sản phẩm' }}</div>
                                        <div class="small text-muted">
                                            SKU hiện tại: <span class="badge bg-light text-dark border">{{ $item->sku }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary px-3 py-2 fs-6">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <select name="items[{{ $index }}][new_sku]" class="form-select form-select-sm" required>
                                            @foreach($variants as $variant)
                                                <option value="{{ $variant->sku }}" 
                                                    {{ old("items.{$index}.new_sku", $item->sku) === $variant->sku ? 'selected' : '' }}>
                                                    {{ $variant->sku }} - {{ $variant->product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" 
                                               name="items[{{ $index }}][new_quantity]" 
                                               class="form-control form-control-sm text-center fw-bold" 
                                               min="1" 
                                               step="1"
                                               value="{{ old("items.{$index}.new_quantity", $item->quantity) }}" 
                                               required>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card Lý do điều chỉnh -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <label for="reason" class="form-label fw-bold">
                        Lý do xin điều chỉnh đơn hàng <span class="text-danger">(*)</span>
                    </label>
                    <textarea name="reason" 
                              id="reason" 
                              rows="3" 
                              class="form-control @error('reason') is-invalid @enderror" 
                              placeholder="Ví dụ: Khách hàng yêu cầu tăng số lượng bếp từ từ 2 lên 3 chiếc..." 
                              required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted">
                        <i class="bi bi-shield-lock me-1"></i>
                        Lưu ý: Sau khi gửi yêu cầu, dữ liệu đơn gốc <strong>sẽ giữ nguyên</strong> cho đến khi Quản lý kho xem xét và phê duyệt.
                    </div>
                </div>
            </div>

            <!-- Nút Hành Động -->
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mb-5">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary px-4 text-center">
                    Hủy bỏ
                </a>
                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                    <i class="bi bi-send-check me-1"></i> Gửi Yêu Cầu Điều Chỉnh
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
