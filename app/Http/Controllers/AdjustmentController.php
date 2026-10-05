<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdjustmentRequest;
use App\Models\Order;
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
}
