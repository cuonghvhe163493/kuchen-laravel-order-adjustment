<?php

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\OrderController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Bài 2: Danh sách và tìm kiếm đơn hàng
Route::get('/', [OrderController::class, 'index'])->name('home');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

// Bài 3: Tạo yêu cầu điều chỉnh đơn hàng
Route::get('/adjustments/create', [AdjustmentController::class, 'create'])->name('adjustments.create');
Route::post('/adjustments', [AdjustmentController::class, 'store'])->name('adjustments.store');

// Bài 4 & Bài 6: Danh sách, Chi tiết, Phê duyệt, Từ chối yêu cầu điều chỉnh
Route::get('/adjustments', [AdjustmentController::class, 'index'])->name('adjustments.index');
Route::get('/adjustments/{id}', [AdjustmentController::class, 'show'])->name('adjustments.show');
Route::post('/adjustments/{id}/approve', [AdjustmentController::class, 'approve'])->name('adjustments.approve');
Route::post('/adjustments/{id}/reject', [AdjustmentController::class, 'reject'])->name('adjustments.reject');

// Chuyển đổi vai trò kiểm thử nhanh trên giao diện (Bài 5)
Route::get('/switch-user/{role}', function (string $role) {
    $user = User::where('role', $role)->first();
    if ($user) {
        Auth::login($user);
        return back()->with('success', "Đã chuyển sang tài khoản: {$user->name} (" . strtoupper($user->role) . ")");
    }
    return back()->with('error', "Không tìm thấy người dùng vai trò: {$role}");
})->name('user.switch');
