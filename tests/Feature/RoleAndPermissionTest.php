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

    /**
     * Tiêu chí nâng cao: Nguyên tắc Segregation of Duties (SoD) & Four-Eyes Principle
     * Người tạo yêu cầu KHÔNG ĐƯỢC tự mình phê duyệt (kể cả Admin)
     */
    public function test_creator_cannot_self_approve_own_created_adjustment_even_if_admin(): void
    {
        // Admin tự tạo 1 yêu cầu điều chỉnh
        $adminAdjustment = OrderAdjustment::create([
            'code' => 'ADJ-ADMIN-001',
            'order_id' => $this->order->id,
            'created_by' => $this->adminUser->id,
            'status' => 'pending',
            'reason' => 'Admin tự tạo yêu cầu',
        ]);

        // Chính Admin đó cố tình tự duyệt yêu cầu của mình -> 403 Forbidden
        $response = $this->actingAs($this->adminUser)
            ->post("/adjustments/{$adminAdjustment->id}/approve");

        $response->assertStatus(403);
        $this->assertTrue($adminAdjustment->fresh()->isPending());

        // Nhưng Quản lý kho vào duyệt thì ĐƯỢC PHÉP
        $responseKho = $this->actingAs($this->warehouseUser)
            ->post("/adjustments/{$adminAdjustment->id}/approve");

        $responseKho->assertStatus(302);
        $this->assertTrue($adminAdjustment->fresh()->isApproved());
    }

    /**
     * Tiêu chí an toàn dữ liệu: Chặn việc cố tình truyền order_item_id của đơn khác
     */
    public function test_cross_order_item_manipulation_is_rejected_by_form_request(): void
    {
        $otherOrder = Order::create([
            'order_code' => 'DH-OTHER-999',
            'channel' => 'sale',
            'status' => 'pending',
            'created_by' => $this->saleUser->id,
        ]);

        $otherItem = OrderItem::create([
            'order_id' => $otherOrder->id,
            'product_variant_id' => 1,
            'sku' => 'KC-001',
            'quantity' => 5,
            'price' => 15000000,
        ]);

        // Sale gửi order_id của $this->order nhưng mảng items lại chứa $otherItem->id
        $response = $this->actingAs($this->saleUser)->post('/adjustments', [
            'order_id' => $this->order->id,
            'reason' => 'Cố tình inject item của đơn khác',
            'items' => [
                ['order_item_id' => $otherItem->id, 'new_sku' => 'KC-001', 'new_quantity' => 3],
            ],
        ]);

        $response->assertSessionHasErrors('items.0.order_item_id');
    }

    /**
     * Tiêu chí an toàn dữ liệu: Chặn việc gửi trùng lặp cùng 1 order_item_id nhiều lần trong 1 request
     */
    public function test_duplicate_order_items_in_request_are_rejected(): void
    {
        $response = $this->actingAs($this->saleUser)->post('/adjustments', [
            'order_id' => $this->order->id,
            'reason' => 'Cố tình gửi trùng lặp item',
            'items' => [
                ['order_item_id' => $this->orderItem->id, 'new_sku' => 'KC-001', 'new_quantity' => 3],
                ['order_item_id' => $this->orderItem->id, 'new_sku' => 'KC-001', 'new_quantity' => 4],
            ],
        ]);

        $response->assertSessionHasErrors('items.0.order_item_id');
    }

    /**
     * Tiêu chí RBAC Database: Các vai trò và quyền hạn được lưu đầy đủ trong CSDL
     */
    public function test_database_rbac_roles_and_permissions_exist_in_db(): void
    {
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'warehouse_manager']);
        $this->assertDatabaseHas('roles', ['name' => 'sale']);

        $this->assertDatabaseHas('permissions', ['name' => 'order.adjustment.create']);
        $this->assertDatabaseHas('permissions', ['name' => 'order.adjustment.approve']);
        $this->assertDatabaseHas('permissions', ['name' => 'order.adjustment.view']);

        $this->assertTrue($this->saleUser->hasPermission('order.adjustment.create'));
        $this->assertFalse($this->saleUser->hasPermission('order.adjustment.approve'));
        $this->assertTrue($this->warehouseUser->hasPermission('order.adjustment.approve'));
    }

    /**
     * Tiêu chí Quản trị: Người dùng đăng nhập có thể truy cập trang Ma trận Phân quyền /roles
     */
    public function test_authenticated_user_can_view_rbac_matrix_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/roles');
        $response->assertStatus(200);
        $response->assertSee('Ma trận Phân quyền');
        $response->assertSee('DATABASE-DRIVEN RBAC');
    }

    /**
     * Tiêu chí Bảo mật: Khách chưa đăng nhập bị chặn khỏi trang /roles và chuyển hướng về /login
     */
    public function test_guest_is_redirected_to_login_when_accessing_roles_page(): void
    {
        $response = $this->get('/roles');
        $response->assertRedirect('/login');
    }
}
