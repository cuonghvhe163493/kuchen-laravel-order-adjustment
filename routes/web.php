<?php

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RolePermissionController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - KÜCHEN ENTERPRISE PORTAL
|--------------------------------------------------------------------------
*/

// Bài 2: Danh sách và tra cứu đơn hàng đa kênh
Route::get('/', [OrderController::class, 'index'])->name('home');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

// ==========================================
// CÁC ROUTE NGHIỆP VỤ ĐƯỢC BẢO VỆ (AUTH MIDDLEWARE & RBAC GATES)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Bài 3: Tạo yêu cầu điều chỉnh đơn hàng (Kiểm tra Permission: order.adjustment.create)
    Route::get('/adjustments/create', [AdjustmentController::class, 'create'])
        ->middleware('can:order.adjustment.create')
        ->name('adjustments.create');

    Route::post('/adjustments', [AdjustmentController::class, 'store'])
        ->middleware('can:order.adjustment.create')
        ->name('adjustments.store');

    // Bài 4 & Bài 6: Danh sách, Chi tiết, Phê duyệt, Từ chối yêu cầu điều chỉnh
    Route::get('/adjustments', [AdjustmentController::class, 'index'])
        ->middleware('can:order.adjustment.view')
        ->name('adjustments.index');

    Route::get('/adjustments/{id}', [AdjustmentController::class, 'show'])
        ->name('adjustments.show');

    Route::post('/adjustments/{id}/approve', [AdjustmentController::class, 'approve'])
        ->name('adjustments.approve');

    Route::post('/adjustments/{id}/reject', [AdjustmentController::class, 'reject'])
        ->name('adjustments.reject');

    // Quản trị Ma trận Phân quyền & Vai trò Database (RBAC Matrix)
    Route::get('/roles', [RolePermissionController::class, 'index'])
        ->name('roles.index');
});

// Chuyển đổi vai trò phục vụ kiểm thử nhanh (Testing & QA)
Route::get('/switch-user/{role}', function (Request $request, string $role) {
    $user = User::where('role', $role)->first();
    if ($user) {
        Auth::login($user);
        $request->session()->regenerate();
        $prev = url()->previous();
        if ($request->has('redirect') || str_contains($prev, '/login') || str_contains($prev, '/register')) {
            return redirect()->route('orders.index')->with('success', "Xác thực nhanh thành công! Chào mừng: {$user->name} (" . strtoupper($user->role) . ")");
        }
        return back()->with('success', "Đã chuyển đổi sang tài khoản: {$user->name} (" . strtoupper($user->role) . ")");
    }
    return back()->with('error', "Không tìm thấy người dùng với vai trò: {$role}");
})->name('user.switch');

// Xác thực & Quản trị tài khoản (Login / Register / Logout)
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.post');
    Route::post('/logout', 'logout')->name('logout');
});
