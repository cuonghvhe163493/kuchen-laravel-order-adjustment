<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\OrderAdjustmentItem;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdjustmentHistoryTest extends TestCase
{
    use RefreshDatabase;

    private User $saleUser;
    private User $warehouseUser;
    private Order $order;
    private OrderItem $orderItem;
    private ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->saleUser = User::create([
            'name' => 'Nguyễn Văn Sale',
            'email' => 'sale@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'sale',
        ]);

        $this->warehouseUser = User::create([
            'name' => 'Trần Văn Kho',
            'email' => 'kho@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'warehouse_manager',
        ]);

        $product = Product::create(['name' => 'Bếp từ đôi KÜCHEN']);
        $this->variant = ProductVariant::create(['product_id' => $product->id, 'sku' => 'KC-001', 'price' => 15000000]);

        $this->order = Order::create([
            'order_code' => 'DH-2026-HIST',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $this->saleUser->id,
        ]);

        $this->orderItem = OrderItem::create([
            'order_id' => $this->order->id,
            'product_variant_id' => $this->variant->id,
            'sku' => 'KC-001',
            'quantity' => 2,
            'price' => 15000000,
        ]);
    }

    /**
     * Tiêu chí Bài 6: Trang chi tiết thể hiện đầy đủ người tạo, đơn liên quan, SKU và số lượng trước - sau, lý do
     */
    public function test_adjustment_detail_page_displays_all_audit_history_fields(): void
    {
        $adj = OrderAdjustment::create([
            'code' => 'ADJ-HIST-001',
            'order_id' => $this->order->id,
            'created_by' => $this->saleUser->id,
            'status' => 'pending',
            'reason' => 'Khách hàng đổi ý muốn mua thêm 1 bếp từ',
        ]);

        OrderAdjustmentItem::create([
            'order_adjustment_id' => $adj->id,
            'order_item_id' => $this->orderItem->id,
            'old_sku' => 'KC-001',
            'new_sku' => 'KC-001',
            'old_quantity' => 2,
            'new_quantity' => 3,
        ]);

        $response = $this->actingAs($this->saleUser)->get("/adjustments/{$adj->id}");

        $response->assertStatus(200);
        // Kiểm tra thông tin người tạo
        $response->assertSee('Nguyễn Văn Sale');
        // Kiểm tra mã đơn liên quan
        $response->assertSee('DH-2026-HIST');
        // Kiểm tra SKU và số lượng trước - sau
        $response->assertSee('KC-001');
        $response->assertSee('2');
        $response->assertSee('3');
        // Kiểm tra lý do điều chỉnh
        $response->assertSee('Khách hàng đổi ý muốn mua thêm 1 bếp từ');
        // Kiểm tra trạng thái pending
        $response->assertSee('Đang chờ phê duyệt');
    }

    /**
     * Tiêu chí Bài 6: Yêu cầu đã phê duyệt thể hiện người duyệt và thời gian duyệt lưu trong DB
     */
    public function test_approved_adjustment_displays_reviewer_and_reviewed_at(): void
    {
        $adj = OrderAdjustment::create([
            'code' => 'ADJ-HIST-002',
            'order_id' => $this->order->id,
            'created_by' => $this->saleUser->id,
            'status' => 'approved',
            'reason' => 'Xin điều chỉnh tăng số lượng',
            'reviewer_id' => $this->warehouseUser->id,
            'reviewed_by' => $this->warehouseUser->id,
            'reviewed_at' => now(),
        ]);

        OrderAdjustmentItem::create([
            'order_adjustment_id' => $adj->id,
            'order_item_id' => $this->orderItem->id,
            'old_sku' => 'KC-001',
            'new_sku' => 'KC-001',
            'old_quantity' => 2,
            'new_quantity' => 4,
        ]);

        $response = $this->actingAs($this->warehouseUser)->get("/adjustments/{$adj->id}");

        $response->assertStatus(200);
        $response->assertSee('Trần Văn Kho');
        $response->assertSee('Đã phê duyệt');
    }

    /**
     * Tiêu chí Bài 6: Yêu cầu đã từ chối thể hiện người từ chối, thời gian từ chối và lý do từ chối
     */
    public function test_rejected_adjustment_displays_rejection_reason_and_reviewer(): void
    {
        $adj = OrderAdjustment::create([
            'code' => 'ADJ-HIST-003',
            'order_id' => $this->order->id,
            'created_by' => $this->saleUser->id,
            'status' => 'rejected',
            'reason' => 'Xin tăng hàng',
            'reviewer_id' => $this->warehouseUser->id,
            'reviewed_by' => $this->warehouseUser->id,
            'rejected_reason' => 'Kho đã hết hàng model này, không thể duyệt.',
            'reviewed_at' => now(),
        ]);

        OrderAdjustmentItem::create([
            'order_adjustment_id' => $adj->id,
            'order_item_id' => $this->orderItem->id,
            'old_sku' => 'KC-001',
            'new_sku' => 'KC-001',
            'old_quantity' => 2,
            'new_quantity' => 5,
        ]);

        $response = $this->actingAs($this->warehouseUser)->get("/adjustments/{$adj->id}");

        $response->assertStatus(200);
        $response->assertSee('Trần Văn Kho');
        $response->assertSee('Đã từ chối');
        $response->assertSee('Kho đã hết hàng model này, không thể duyệt.');
    }

    /**
     * Tiêu chí Bài 6: Xem lịch sử điều chỉnh lọc theo từng đơn hàng cụ thể
     */
    public function test_can_filter_adjustment_history_by_order_id(): void
    {
        $adj = OrderAdjustment::create([
            'code' => 'ADJ-FILTER-001',
            'order_id' => $this->order->id,
            'created_by' => $this->saleUser->id,
            'status' => 'approved',
            'reason' => 'Lịch sử đơn 1',
        ]);

        $otherOrder = Order::create([
            'order_code' => 'DH-OTHER',
            'channel' => 'tiktok',
            'status' => 'pending',
            'created_by' => $this->saleUser->id,
        ]);

        $adjOther = OrderAdjustment::create([
            'code' => 'ADJ-FILTER-002',
            'order_id' => $otherOrder->id,
            'created_by' => $this->saleUser->id,
            'status' => 'approved',
            'reason' => 'Lịch sử đơn khác',
        ]);

        $response = $this->actingAs($this->saleUser)->get("/adjustments?order_id={$this->order->id}");

        $response->assertStatus(200);
        $response->assertSee('ADJ-FILTER-001');
        $response->assertDontSee('ADJ-FILTER-002');
    }
}
