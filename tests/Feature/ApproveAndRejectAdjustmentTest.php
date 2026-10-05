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

class ApproveAndRejectAdjustmentTest extends TestCase
{
    use RefreshDatabase;

    private User $saleUser;
    private User $warehouseUser;
    private Order $order;
    private OrderItem $item1;
    private OrderItem $item2;
    private ProductVariant $variant1;
    private ProductVariant $variant2;
    private OrderAdjustment $adjustment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->saleUser = User::create([
            'name' => 'Sale Kuchen',
            'email' => 'sale@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'sale',
        ]);

        $this->warehouseUser = User::create([
            'name' => 'Kho Kuchen',
            'email' => 'kho@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'warehouse_manager',
        ]);

        $p1 = Product::create(['name' => 'Bếp từ đôi KÜCHEN']);
        $this->variant1 = ProductVariant::create(['product_id' => $p1->id, 'sku' => 'KC-001', 'price' => 15000000]);

        $p2 = Product::create(['name' => 'Nồi chiên không dầu KÜCHEN']);
        $this->variant2 = ProductVariant::create(['product_id' => $p2->id, 'sku' => 'KC-002', 'price' => 4500000]);

        // Đơn DH-2026-001: KC-001 số lượng 2; KC-002 số lượng 1
        $this->order = Order::create([
            'order_code' => 'DH-2026-001',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $this->saleUser->id,
        ]);

        $this->item1 = OrderItem::create([
            'order_id' => $this->order->id,
            'product_variant_id' => $this->variant1->id,
            'sku' => 'KC-001',
            'quantity' => 2,
            'price' => 15000000,
        ]);

        $this->item2 = OrderItem::create([
            'order_id' => $this->order->id,
            'product_variant_id' => $this->variant2->id,
            'sku' => 'KC-002',
            'quantity' => 1,
            'price' => 4500000,
        ]);

        // Yêu cầu điều chỉnh pending: KC-001 từ 2 thành 3; KC-002 từ 1 thành 2
        $this->adjustment = OrderAdjustment::create([
            'code' => 'ADJ-000001',
            'order_id' => $this->order->id,
            'created_by' => $this->saleUser->id,
            'status' => 'pending',
            'reason' => 'Khách hàng tăng số lượng',
        ]);

        OrderAdjustmentItem::create([
            'order_adjustment_id' => $this->adjustment->id,
            'order_item_id' => $this->item1->id,
            'old_sku' => 'KC-001',
            'new_sku' => 'KC-001',
            'old_quantity' => 2,
            'new_quantity' => 3,
        ]);

        OrderAdjustmentItem::create([
            'order_adjustment_id' => $this->adjustment->id,
            'order_item_id' => $this->item2->id,
            'old_sku' => 'KC-002',
            'new_sku' => 'KC-002',
            'old_quantity' => 1,
            'new_quantity' => 2,
        ]);
    }

    /**
     * Tiêu chí 1: Quản lý kho duyệt đúng; order_items cập nhật chính xác; ghi người và thời gian duyệt
     */
    public function test_warehouse_manager_approves_adjustment_and_order_items_updated_accurately(): void
    {
        $this->actingAs($this->warehouseUser);

        $response = $this->post("/adjustments/{$this->adjustment->id}/approve");

        $response->assertRedirect("/adjustments/{$this->adjustment->id}");
        $response->assertSessionHas('success');

        // Kiểm tra trạng thái yêu cầu
        $this->adjustment->refresh();
        $this->assertTrue($this->adjustment->isApproved());
        $this->assertEquals($this->warehouseUser->id, $this->adjustment->reviewed_by);
        $this->assertNotNull($this->adjustment->reviewed_at);

        // Kiểm tra order_items đã được cập nhật chính xác sang số lượng mới
        $this->assertEquals(3, $this->item1->fresh()->quantity);
        $this->assertEquals(2, $this->item2->fresh()->quantity);
    }

    /**
     * Tiêu chí 2: Yêu cầu đã được duyệt không thể duyệt lần hai
     */
    public function test_already_approved_adjustment_cannot_be_approved_again(): void
    {
        $this->actingAs($this->warehouseUser);

        // Duyệt lần 1
        $this->post("/adjustments/{$this->adjustment->id}/approve");
        $this->assertTrue($this->adjustment->fresh()->isApproved());

        // Cố tình gửi request duyệt lần 2
        $response = $this->post("/adjustments/{$this->adjustment->id}/approve");

        $response->assertSessionHas('error');
    }

    /**
     * Tiêu chí 3: Từ chối: bắt buộc lý do; không sửa đơn; ghi người và thời gian từ chối
     */
    public function test_reject_adjustment_requires_reason_and_keeps_order_items_unchanged(): void
    {
        $this->actingAs($this->warehouseUser);

        // Thử từ chối không có lý do -> Bị chặn validation
        $responseFail = $this->post("/adjustments/{$this->adjustment->id}/reject", [
            'rejected_reason' => '',
        ]);
        $responseFail->assertSessionHasErrors('rejected_reason');

        // Từ chối với lý do hợp lệ
        $responseSuccess = $this->post("/adjustments/{$this->adjustment->id}/reject", [
            'rejected_reason' => 'Kho hiện tại đã hết hàng KC-001, không thể tăng số lượng.',
        ]);

        $responseSuccess->assertRedirect("/adjustments/{$this->adjustment->id}");
        $responseSuccess->assertSessionHas('success');

        // Kiểm tra trạng thái và thông tin từ chối
        $this->adjustment->refresh();
        $this->assertTrue($this->adjustment->isRejected());
        $this->assertEquals('Kho hiện tại đã hết hàng KC-001, không thể tăng số lượng.', $this->adjustment->rejected_reason);
        $this->assertEquals($this->warehouseUser->id, $this->adjustment->reviewed_by);
        $this->assertNotNull($this->adjustment->reviewed_at);

        // Đơn hàng gốc KHÔNG ĐƯỢC PHÉP THAY ĐỔI
        $this->assertEquals(2, $this->item1->fresh()->quantity);
        $this->assertEquals(1, $this->item2->fresh()->quantity);
    }

    /**
     * Tiêu chí 4: Yêu cầu đã từ chối không thể duyệt lại
     */
    public function test_rejected_adjustment_cannot_be_approved(): void
    {
        $this->actingAs($this->warehouseUser);

        // Từ chối yêu cầu
        $this->post("/adjustments/{$this->adjustment->id}/reject", [
            'rejected_reason' => 'Không đồng ý điều chỉnh.',
        ]);
        $this->assertTrue($this->adjustment->fresh()->isRejected());

        // Cố tình bấm duyệt trên yêu cầu đã bị từ chối
        $response = $this->post("/adjustments/{$this->adjustment->id}/approve");

        $response->assertSessionHas('error');
        $this->assertTrue($this->adjustment->fresh()->isRejected());
    }

    /**
     * Tiêu chí 5: Đơn hàng đổi trạng thái sang exported lúc đang chờ duyệt -> Chặn không cho duyệt
     */
    public function test_cannot_approve_if_order_was_exported_while_pending(): void
    {
        $this->actingAs($this->warehouseUser);

        // Trong lúc pending, đơn hàng bị xuất kho
        $this->order->update(['status' => 'exported']);

        $response = $this->post("/adjustments/{$this->adjustment->id}/approve");

        $response->assertSessionHas('error');
        $this->assertTrue($this->adjustment->fresh()->isPending());
        // Số lượng đơn hàng gốc không bị thay đổi
        $this->assertEquals(2, $this->item1->fresh()->quantity);
    }
}
