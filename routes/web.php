<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ---- Khu nội bộ (đã đăng nhập + đã verify + đã approved) ----
Route::middleware(['auth', 'verified.to.login', 'approved.to.login'])->group(function () {

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Ví dụ các module nội bộ
    Route::prefix('ideas')->group(function () {
        Route::get('/', fn() => 'Ideas index');
        Route::get('/create', fn() => 'Create idea');
        // ...
    });

    // Trang admin Home (nếu muốn)
    Route::prefix('admin')->group(function () {
        Route::get('/', fn() => 'Admin Home');
    });

    // ---- CHỈ ADMIN: phê duyệt tài khoản ----
    Route::prefix('admin')->name('admin.')->middleware('is.admin')->group(function () {
        Route::get('/approvals', [\App\Http\Controllers\Admin\ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/{user}/approve', [\App\Http\Controllers\Admin\ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{user}/reject', [\App\Http\Controllers\Admin\ApprovalController::class, 'reject'])->name('approvals.reject');
        Route::post('/approvals/{user}/update-role', [\App\Http\Controllers\Admin\ApprovalController::class, 'updateRole'])->name('approvals.updateRole');
    });
});

// ---- Khu người dùng (chỉ cần đăng nhập) ----
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes Breeze (login / register / verify / forgot ...)
require __DIR__ . '/auth.php';