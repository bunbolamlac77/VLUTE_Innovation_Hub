<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ---- Khu nội bộ (ví dụ) ----
// Dùng alias đã đăng ký: verified.to.login + approved.to.login
Route::middleware(['auth', 'verified.to.login', 'approved.to.login'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Ví dụ các module nội bộ
    Route::prefix('ideas')->group(function () {
        Route::get('/', fn() => 'Ideas index');     // -> view thực tế của bạn
        Route::get('/create', fn() => 'Create idea');
        // ...
    });

    // Trang admin chung (nếu có trang home admin)
    Route::prefix('admin')->group(function () {
        Route::get('/', function () {
            return <<<HTML
            <div style="padding:24px;font-family:system-ui,Arial">
              <h1>Admin Home</h1>
              <p><a href="/admin/approvals" style="font-weight:700">Quản lý phê duyệt tài khoản</a></p>
            </div>
        HTML;
        });
    });

    // ===== Chỉ dành cho admin: phê duyệt tài khoản =====
    // ... trong Route::middleware(['auth','verified.to.login','approved.to.login'])->group(function () { ... });

    Route::prefix('admin')->name('admin.')->middleware('is.admin')->group(function () {
        Route::get('/approvals', [\App\Http\Controllers\Admin\ApprovalController::class, 'index'])->name('approvals.index');
        Route::post('/approvals/{user}/approve', [\App\Http\Controllers\Admin\ApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{user}/reject', [\App\Http\Controllers\Admin\ApprovalController::class, 'reject'])->name('approvals.reject');
        Route::post('/approvals/{user}/update-role', [\App\Http\Controllers\Admin\ApprovalController::class, 'updateRole'])->name('approvals.updateRole');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';