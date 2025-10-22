<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ---- Khu nội bộ (ví dụ) ----
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Ví dụ các module nội bộ
    Route::prefix('ideas')->group(function () {
        Route::get('/', fn() => 'Ideas index');     // -> view thực tế của bạn
        Route::get('/create', fn() => 'Create idea');
        // ...
    });

    // Trang admin / approvals ...
    Route::prefix('admin')->group(function () {
        Route::get('/', fn() => 'Admin Home');
        // ...
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
