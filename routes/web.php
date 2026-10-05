<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Bài 2: Danh sách và tìm kiếm đơn hàng
Route::get('/', [OrderController::class, 'index'])->name('home');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

// Placeholder cho Bài 3 (Tạo yêu cầu điều chỉnh)
Route::get('/adjustments/create', function (Request $request) {
    return redirect()->route('orders.index')->with('success', 'Chuyển hướng đến Bài 3: Tạo yêu cầu điều chỉnh');
})->name('adjustments.create');
