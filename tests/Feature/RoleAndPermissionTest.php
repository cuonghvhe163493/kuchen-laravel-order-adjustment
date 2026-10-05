<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderAdjustment;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAndPermissionTest extends TestCase
{
    use RefreshDatabase;

    private User $saleUser;
    private User $warehouseUser;
    private User $adminUser;
    private Order $order;
    private OrderItem $orderItem;
    private OrderAdjustment $adjustment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->saleUser = User::create([
            'name' => 'Sale Test',
            'email' => 'sale@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'sale',
        ]);

        $this->warehouseUser = User::create([
            'name' => 'Kho Test',
            'email' => 'kho@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'warehouse_manager',
        ]);

        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@kuchen.vn',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $product = Product::create(['name' => 'Bếp từ đôi KÜCHEN']);
        $variant = ProductVariant::create(['product_id' => $product->id, 'sku' => 'KC-001', 'price' => 15000000]);

        $this->order = Order::create([
            'order_code' => 'DH-PERM-001',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $this->saleUser->id,
        ]);

        $this->orderItem = OrderItem::create([
            'order_id' => $this->order->id,
            'product_variant_id' => $variant->id,
            'sku' => 'KC-001',
            'quantity' => 2,
            'price' => 15000000,
        ]);

        $this->adjustment = OrderAdjustment::create([
            'code' => 'ADJ-PERM-001',
            'order_id' => $this->order->id,
            'created_by' => $this->saleUser->id,
            'status' => 'pending',
            'reason' => 'Kiểm thử phân quyền',
        ]);
    }

    /**
     * Quyền 1: Xem đơn / yêu cầu (order.adjustment.view) -> SALE, KHO, ADMIN đều CÓ
     */
    public function test_all_roles_can_view_adjustments(): void
    {
        // SALE xem
        $this->actingAs($this->saleUser)->get('/adjustments')->assertStatus(200);
        $this->actingAs($this->saleUser)->get("/adjustments/{$this->adjustment->id}")->assertStatus(200);

        // KHO xem
        $this->actingAs($this->warehouseUser)->get('/adjustments')->assertStatus(200);
        $this->actingAs($this->warehouseUser)->get("/adjustments/{$this->adjustment->id}")->assertStatus(200);

        // ADMIN xem
        $this->actingAs($this->adminUser)->get('/adjustments')->assertStatus(200);
        $this->actingAs($this->adminUser)->get("/adjustments/{$this->adjustment->id}")->assertStatus(200);
    }

    /**
     * Quyền 2: Tạo yêu cầu (order.adjustment.create) -> SALE: CÓ, ADMIN: CÓ, KHO: KHÔNG
     */
    public function test_sale_and_admin_can_access_create_form_but_warehouse_cannot(): void
    {
        $freshOrder = Order::create([
            'order_code' => 'DH-FRESH-001',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $this->saleUser->id,
        ]);

        // SALE truy cập form tạo -> 200 OK
        $this->actingAs($this->saleUser)
            ->get("/adjustments/create?order_id={$freshOrder->id}")
            ->assertStatus(200);

        // ADMIN truy cập form tạo -> 200 OK
        $this->actingAs($this->adminUser)
            ->get("/adjustments/create?order_id={$freshOrder->id}")
            ->assertStatus(200);

        // KHO truy cập form tạo -> 403 Forbidden (chặn cứng ở backend)
        $this->actingAs($this->warehouseUser)
            ->get("/adjustments/create?order_id={$freshOrder->id}")
            ->assertStatus(403);
    }

    public function test_warehouse_manager_cannot_post_store_adjustment(): void
    {
        // KHO cố tình gửi POST -> 403 Forbidden
        $response = $this->actingAs($this->warehouseUser)->post('/adjustments', [
            'order_id' => $this->order->id,
            'reason' => 'Kho gửi yêu cầu',
            'items' => [
                ['order_item_id' => $this->orderItem->id, 'new_sku' => 'KC-001', 'new_quantity' => 3],
            ],
        ]);

        $response->assertStatus(403);
    }

    /**
     * Tiêu chí 3 của đề bài: SALE không thể tự phê duyệt (hoặc duyệt bất kỳ đơn nào)
     */
    public function test_sale_cannot_approve_adjustment_and_receives_403_forbidden(): void
    {
        // SALE gửi POST duyệt -> 403 Forbidden
        $response = $this->actingAs($this->saleUser)
            ->post("/adjustments/{$this->adjustment->id}/approve");

        $response->assertStatus(403);
        $this->assertTrue($this->adjustment->fresh()->isPending());
    }

    /**
     * Quyền 3: SALE không thể từ chối yêu cầu
     */
    public function test_sale_cannot_reject_adjustment_and_receives_403_forbidden(): void
    {
        // SALE gửi POST từ chối -> 403 Forbidden
        $response = $this->actingAs($this->saleUser)
            ->post("/adjustments/{$this->adjustment->id}/reject", [
                'rejected_reason' => 'Sale tự từ chối',
            ]);

        $response->assertStatus(403);
        $this->assertTrue($this->adjustment->fresh()->isPending());
    }

    /**
     * Quyền 4: Quản lý kho và Admin CÓ quyền Phê duyệt & Từ chối
     */
    public function test_warehouse_manager_and_admin_can_approve(): void
    {
        // KHO duyệt -> Cho phép
        $response = $this->actingAs($this->warehouseUser)
            ->post("/adjustments/{$this->adjustment->id}/approve");
        $response->assertStatus(302);
        $this->assertTrue($this->adjustment->fresh()->isApproved());
    }
}
