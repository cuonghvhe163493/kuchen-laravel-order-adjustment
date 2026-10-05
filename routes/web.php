<?php

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\OrderController;
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
