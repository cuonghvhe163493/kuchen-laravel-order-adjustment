<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Bảng Vai trò (Roles) trong Database
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, warehouse_manager, sale
            $table->string('display_name'); // Quản trị viên, Quản lý kho, Nhân viên Sale
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Bảng Quyền hạn (Permissions) trong Database
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // order.view, order.adjustment.create, ...
            $table->string('display_name');
            $table->string('module')->default('Chung'); // Đơn hàng, Điều chỉnh, Hệ thống
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Bảng liên kết Phân quyền cho Vai trò (Permission - Role)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        // 4. Bảng liên kết Người dùng với Vai trò (Role - User)
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->primary(['user_id', 'role_id']);
        });

        // Tự động Seed Dữ liệu RBAC nền tảng ngay trong Migration
        $this->seedInitialRbacData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }

    /**
     * Nạp dữ liệu Role & Permission mặc định và ánh xạ cho người dùng hiện có
     */
    private function seedInitialRbacData(): void
    {
        $now = now();

        // 1. Tạo 3 Vai trò cốt lõi
        $roles = [
            'admin' => [
                'display_name' => 'Quản trị viên Hệ thống',
                'description' => 'Toàn quyền kiểm soát, cấu hình hệ thống, quản lý đơn hàng và phê duyệt cấp cao',
            ],
            'warehouse_manager' => [
                'display_name' => 'Quản lý Kho vận',
                'description' => 'Kiểm soát hàng hóa tồn kho, phê duyệt hoặc từ chối các yêu cầu điều chỉnh đơn hàng',
            ],
            'sale' => [
                'display_name' => 'Nhân viên Kinh doanh (Sale)',
                'description' => 'Theo dõi đơn hàng các kênh bán và tạo yêu cầu điều chỉnh đơn hàng',
            ],
        ];

        $roleIds = [];
        foreach ($roles as $key => $roleData) {
            $roleIds[$key] = DB::table('roles')->insertGetId([
                'name' => $key,
                'display_name' => $roleData['display_name'],
                'description' => $roleData['description'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 2. Tạo danh sách Quyền hạn (Permissions)
        $permissions = [
            // Module Đơn hàng
            [
                'name' => 'order.view',
                'display_name' => 'Xem danh sách & tìm kiếm đơn hàng',
                'module' => 'Đơn hàng',
                'description' => 'Truy cập danh sách đơn, lọc theo kênh bán và tìm kiếm mã đơn',
            ],
            // Module Yêu cầu điều chỉnh đơn
            [
                'name' => 'order.adjustment.view',
                'display_name' => 'Xem danh sách yêu cầu điều chỉnh',
                'module' => 'Điều chỉnh đơn',
                'description' => 'Xem lịch sử các phiếu điều chỉnh đơn hàng và chi tiết từng phiếu',
            ],
            [
                'name' => 'order.adjustment.create',
                'display_name' => 'Tạo yêu cầu điều chỉnh đơn hàng',
                'module' => 'Điều chỉnh đơn',
                'description' => 'Gửi yêu cầu đổi phân loại sản phẩm, số lượng hoặc ghi chú điều chỉnh',
            ],
            [
                'name' => 'order.adjustment.approve',
                'display_name' => 'Phê duyệt yêu cầu điều chỉnh',
                'module' => 'Điều chỉnh đơn',
                'description' => 'Duyệt phiếu điều chỉnh (áp dụng nguyên tắc Four-Eyes: không tự duyệt phiếu do mình tạo)',
            ],
            [
                'name' => 'order.adjustment.reject',
                'display_name' => 'Từ chối yêu cầu điều chỉnh',
                'module' => 'Điều chỉnh đơn',
                'description' => 'Từ chối phiếu điều chỉnh đơn và nhập lý do từ chối',
            ],
            // Module Phân quyền & Quản trị
            [
                'name' => 'role.view',
                'display_name' => 'Xem ma trận vai trò & phân quyền',
                'module' => 'Hệ thống & Phân quyền',
                'description' => 'Xem danh sách vai trò, phân quyền trong DB và phân bổ nhân sự',
            ],
            [
                'name' => 'system.manage',
                'display_name' => 'Quản trị tham số hệ thống',
                'module' => 'Hệ thống & Phân quyền',
                'description' => 'Toàn quyền cấu hình tham số, đồng bộ kho vận và dữ liệu tổng',
            ],
        ];

        $permissionIds = [];
        foreach ($permissions as $perm) {
            $permissionIds[$perm['name']] = DB::table('permissions')->insertGetId([
                'name' => $perm['name'],
                'display_name' => $perm['display_name'],
                'module' => $perm['module'],
                'description' => $perm['description'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // 3. Phân bổ Quyền vào từng Vai trò (Matrix Role-Permission)
        $rolePermissionMap = [
            'admin' => [
                'order.view',
                'order.adjustment.view',
                'order.adjustment.create',
                'order.adjustment.approve',
                'order.adjustment.reject',
                'role.view',
                'system.manage',
            ],
            'warehouse_manager' => [
                'order.view',
                'order.adjustment.view',
                'order.adjustment.approve',
                'order.adjustment.reject',
                'role.view',
            ],
            'sale' => [
                'order.view',
                'order.adjustment.view',
                'order.adjustment.create',
                'role.view',
            ],
        ];

        foreach ($rolePermissionMap as $roleName => $permNames) {
            if (isset($roleIds[$roleName])) {
                $roleId = $roleIds[$roleName];
                foreach ($permNames as $pName) {
                    if (isset($permissionIds[$pName])) {
                        DB::table('permission_role')->insert([
                            'role_id' => $roleId,
                            'permission_id' => $permissionIds[$pName],
                        ]);
                    }
                }
            }
        }

        // 4. Đồng bộ tất cả người dùng hiện có trong bảng users vào bảng role_user
        $existingUsers = DB::table('users')->get(['id', 'role']);
        foreach ($existingUsers as $u) {
            if (isset($roleIds[$u->role])) {
                DB::table('role_user')->insertOrIgnore([
                    'user_id' => $u->id,
                    'role_id' => $roleIds[$u->role],
                ]);
            }
        }
    }
};
