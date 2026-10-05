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
