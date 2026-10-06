@extends('layouts.app')

@section('title', 'Tạo yêu cầu điều chỉnh - Đơn hàng ' . $order->order_code)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Nút quay lại & Tiêu đề -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
            <div>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm mb-2 shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách đơn hàng
                </a>
                <h4 class="fw-bold mb-0 text-dark">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Lập Phiếu Điều Chỉnh Đơn Hàng
                </h4>
                <p class="text-muted small mb-0">Thay đổi biến thể quy cách SKU, số lượng hoặc bổ sung ghi chú nghiệp vụ</p>
            </div>
            <div>
                <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm">
                    Mã đơn: <strong>{{ $order->order_code }}</strong>
                </span>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm border-0" role="alert">
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
                        <div class="fw-bold text-uppercase text-primary">{{ $order->channel }}</div>
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
                        <div class="fw-bold text-dark">{{ $order->creator->name ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="small text-muted">Tổng tiền đơn gốc</div>
                        <div class="fw-bold text-dark font-monospace">{{ $order->formatted_total_amount }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Tạo Yêu Cầu Điều Chỉnh -->
        <form method="POST" action="{{ route('adjustments.store') }}" id="adjustmentForm">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-primary">
                            <i class="bi bi-table me-1"></i> Danh Sách Mặt Hàng Cần Điều Chỉnh
                        </h6>
                        <small class="text-muted">Chọn SKU mới và số lượng mới mong muốn cho từng dòng sản phẩm.</small>
                    </div>
                    <span class="badge bg-light text-secondary border">Tự động tính chênh lệch giá</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="itemsTable">
                        <thead class="table-light small text-uppercase text-muted">
                            <tr>
                                <th style="width: 40px;" class="text-center">#</th>
                                <th style="width: 30%;">Dòng hàng hiện tại (Trước)</th>
                                <th style="width: 12%;" class="text-center">Số lượng cũ</th>
                                <th style="width: 38%;">SKU đề xuất mới (Sau)</th>
                                <th style="width: 15%;" class="text-center">Số lượng mới (*)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $index => $item)
                                <tr class="item-row" 
                                    data-old-price="{{ $item->price }}" 
                                    data-old-qty="{{ $item->quantity }}">
                                    <td class="text-center text-muted small">
                                        {{ $index + 1 }}
                                        <input type="hidden" name="items[{{ $index }}][order_item_id]" value="{{ $item->id }}">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->productVariant->product->name ?? 'Sản phẩm' }}</div>
                                        <div class="small text-muted d-flex align-items-center gap-2 mt-1">
                                            <span class="badge bg-light text-dark border font-monospace">{{ $item->sku }}</span>
                                            <span>Đơn giá: {{ number_format($item->price, 0, ',', '.') }} ₫</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary px-3 py-2 fs-6">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        <select name="items[{{ $index }}][new_sku]" class="form-select form-select-sm sku-select" required>
                                            @foreach($variants as $variant)
                                                <option value="{{ $variant->sku }}" 
                                                        data-price="{{ $variant->price }}"
                                                        data-name="{{ $variant->product->name }}"
                                                    {{ old("items.{$index}.new_sku", $item->sku) === $variant->sku ? 'selected' : '' }}>
                                                    {{ $variant->sku }} - {{ $variant->product->name }} ({{ number_format($variant->price, 0, ',', '.') }} ₫)
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <input type="number" 
                                               name="items[{{ $index }}][new_quantity]" 
                                               class="form-control form-control-sm text-center fw-bold qty-input" 
                                               min="1" 
                                               max="999"
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

            <!-- BẢNG TÍNH TOÁN TÀI CHÍNH THỜI GIAN THỰC (LIVE FINANCIAL IMPACT CALCULATOR) -->
            <div class="card border-0 shadow-sm mb-4 bg-primary bg-opacity-10 border border-primary border-opacity-25">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-primary mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-calculator-fill fs-5"></i>
                            Dự Báo Chênh Lệch Tài Chính Real-time (Financial Impact)
                        </h6>
                        <span class="badge bg-primary text-white px-2 py-1 small">Tự động đối soát</span>
                    </div>

                    <div class="row g-3 text-center text-sm-start">
                        <div class="col-12 col-sm-4">
                            <div class="p-3 bg-white rounded-3 border shadow-sm">
                                <div class="text-muted small fw-medium">Tổng tiền đơn gốc</div>
                                <div class="fs-5 fw-bold text-dark font-monospace" id="calcOldTotal">
                                    {{ $order->formatted_total_amount }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="p-3 bg-white rounded-3 border shadow-sm">
                                <div class="text-muted small fw-medium">Tổng tiền sau điều chỉnh</div>
                                <div class="fs-5 fw-bold text-primary font-monospace" id="calcNewTotal">
                                    0 ₫
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="p-3 bg-white rounded-3 border shadow-sm">
                                <div class="text-muted small fw-medium">Mức độ chênh lệch (&Delta;)</div>
                                <div class="fs-5 fw-bold font-monospace" id="calcDeltaTotal">
                                    0 ₫
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Lý do điều chỉnh -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <label for="reason" class="form-label fw-bold text-dark">
                        Lý do xin điều chỉnh đơn hàng <span class="text-danger">(*)</span>
                    </label>
                    <textarea name="reason" 
                              id="reason" 
                              rows="3" 
                              class="form-control @error('reason') is-invalid @enderror" 
                              placeholder="Nhập lý do chi tiết (ví dụ: Khách hàng liên hệ đổi mẫu sang bếp đôi GL-889 và xin bù tiền chênh lệch)..." 
                              required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted mt-2">
                        <i class="bi bi-shield-lock me-1 text-primary"></i>
                        Quy tắc an toàn: Sau khi gửi yêu cầu, dữ liệu đơn gốc <strong>sẽ được giữ nguyên 100%</strong>. Đơn hàng chỉ chính thức thay đổi sau khi Quản lý kho xem xét và phê duyệt.
                    </div>
                </div>
            </div>

            <!-- Nút Hành Động -->
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mb-5">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary px-4 text-center">
                    Hủy bỏ
                </a>
                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-send-check"></i>
                    <span>Gửi Yêu Cầu Điều Chỉnh</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.item-row');
    const calcNewTotalEl = document.getElementById('calcNewTotal');
    const calcDeltaTotalEl = document.getElementById('calcDeltaTotal');

    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
    }

    function calculateFinancialImpact() {
        let oldTotal = 0;
        let newTotal = 0;

        rows.forEach(row => {
            const oldPrice = parseFloat(row.dataset.oldPrice) || 0;
            const oldQty = parseInt(row.dataset.oldQty) || 0;
            oldTotal += oldPrice * oldQty;

            const select = row.querySelector('.sku-select');
            const qtyInput = row.querySelector('.qty-input');

            const selectedOption = select ? select.options[select.selectedIndex] : null;
            const newPrice = selectedOption ? (parseFloat(selectedOption.dataset.price) || 0) : 0;
            const newQty = qtyInput ? (parseInt(qtyInput.value) || 0) : 0;

            newTotal += newPrice * newQty;
        });

        const delta = newTotal - oldTotal;

        if (calcNewTotalEl) {
            calcNewTotalEl.textContent = formatMoney(newTotal);
        }

        if (calcDeltaTotalEl) {
            if (delta > 0) {
                calcDeltaTotalEl.className = 'fs-5 fw-bold font-monospace text-success';
                calcDeltaTotalEl.innerHTML = `<i class="bi bi-arrow-up-right me-1"></i>+ ${formatMoney(delta)} (Khách bù thêm)`;
            } else if (delta < 0) {
                calcDeltaTotalEl.className = 'fs-5 fw-bold font-monospace text-danger';
                calcDeltaTotalEl.innerHTML = `<i class="bi bi-arrow-down-right me-1"></i>${formatMoney(delta)} (Hoàn trừ)`;
            } else {
                calcDeltaTotalEl.className = 'fs-5 fw-bold font-monospace text-secondary';
                calcDeltaTotalEl.innerHTML = `0 ₫ (Không đổi giá trị)`;
            }
        }
    }

    // Lắng nghe sự kiện thay đổi trên các ô input và select
    rows.forEach(row => {
        const select = row.querySelector('.sku-select');
        const qtyInput = row.querySelector('.qty-input');

        if (select) select.addEventListener('change', calculateFinancialImpact);
        if (qtyInput) {
            qtyInput.addEventListener('input', calculateFinancialImpact);
            qtyInput.addEventListener('change', calculateFinancialImpact);
        }
    });

    // Tính toán ban đầu
    calculateFinancialImpact();
});
</script>
@endsection
