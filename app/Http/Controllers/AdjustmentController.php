<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectAdjustmentRequest;
use App\Http\Requests\StoreAdjustmentRequest;
use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\AdjustmentService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdjustmentController extends Controller
{
    protected AdjustmentService $adjustmentService;

    public function __construct(AdjustmentService $adjustmentService)
    {
        $this->adjustmentService = $adjustmentService;
    }

    /**
     * Danh sách yêu cầu điều chỉnh (Bài 4, Bài 6)
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', '');

        $adjustments = OrderAdjustment::query()
            ->with(['order', 'creator', 'reviewer'])
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('adjustments.index', compact('adjustments', 'status'));
    }

    /**
     * Màn hình tạo yêu cầu điều chỉnh (Bài 3)
     */
    public function create(Request $request): View|RedirectResponse
    {
        $orderId = $request->query('order_id');
        if (!$orderId) {
            return redirect()->route('orders.index')->with('error', 'Vui lòng chọn đơn hàng cần điều chỉnh.');
        }

        $order = Order::with(['items.productVariant.product', 'pendingAdjustment'])->find($orderId);
        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Đơn hàng không tồn tại.');
        }

        // Kiểm tra điều kiện nghiệp vụ
        if (!$order->canBeAdjusted()) {
            return redirect()->route('orders.index')->with('error', 'Đơn hàng đã xuất kho hoặc đã hủy, không thể yêu cầu điều chỉnh.');
        }

        if ($order->pendingAdjustment) {
            return redirect()->route('orders.index')->with('error', "Đơn hàng đang có yêu cầu {$order->pendingAdjustment->code} đang chờ duyệt.");
        }

        // Lấy danh sách tất cả các biến thể SKU hợp lệ trong hệ thống
        $variants = ProductVariant::with('product')->get();

        return view('adjustments.create', compact('order', 'variants'));
    }

    /**
     * Xử lý lưu yêu cầu điều chỉnh (Bài 3)
     */
    public function store(StoreAdjustmentRequest $request): RedirectResponse
    {
        $creatorId = auth()->id() ?? User::where('role', 'sale')->value('id') ?? 1;

        try {
            $adjustment = $this->adjustmentService->createAdjustment(
                (int) $request->validated('order_id'),
                $request->validated(),
                $creatorId
            );

            return redirect()->route('orders.index')
                ->with('success', "Tạo yêu cầu điều chỉnh {$adjustment->code} thành công và đang chờ Quản lý kho phê duyệt!");
        } catch (DomainException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Chi tiết yêu cầu điều chỉnh (Bài 4, Bài 6)
     */
    public function show(int $id): View|RedirectResponse
    {
        $adjustment = OrderAdjustment::with([
            'order.items.productVariant.product',
            'creator',
            'reviewer',
            'items.orderItem.productVariant.product',
        ])->find($id);

        if (!$adjustment) {
            return redirect()->route('adjustments.index')->with('error', 'Yêu cầu điều chỉnh không tồn tại.');
        }

        return view('adjustments.show', compact('adjustment'));
    }

    /**
     * Phê duyệt yêu cầu điều chỉnh (Bài 4)
     */
    public function approve(int $id): RedirectResponse
    {
        // Mặc định tài khoản Quản lý kho duyệt (ở Bài 5 sẽ dùng Policy/Gate xác thực)
        $reviewerId = auth()->id() ?? User::where('role', 'warehouse_manager')->value('id') ?? 2;

        try {
            $adjustment = $this->adjustmentService->approveAdjustment($id, $reviewerId);

            return redirect()->route('adjustments.show', $adjustment->id)
                ->with('success', "Đã phê duyệt yêu cầu {$adjustment->code} thành công! Đơn hàng {$adjustment->order->order_code} đã được cập nhật số lượng mới.");
        } catch (DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Từ chối yêu cầu điều chỉnh (Bài 4)
     */
    public function reject(RejectAdjustmentRequest $request, int $id): RedirectResponse
    {
        $reviewerId = auth()->id() ?? User::where('role', 'warehouse_manager')->value('id') ?? 2;

        try {
            $adjustment = $this->adjustmentService->rejectAdjustment(
                $id,
                $request->validated('rejected_reason'),
                $reviewerId
            );

            return redirect()->route('adjustments.show', $adjustment->id)
                ->with('success', "Đã từ chối yêu cầu {$adjustment->code}. Dữ liệu đơn hàng được giữ nguyên.");
        } catch (DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
