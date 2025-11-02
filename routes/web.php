<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('/about', function () {
    return view('about');
});

// Public Ideas (Ngân hàng Ý tưởng) - Trang công khai
Route::get('/ideas', [\App\Http\Controllers\PublicIdeaController::class, 'index'])->name('ideas.index');
Route::get('/ideas/{slug}', [\App\Http\Controllers\PublicIdeaController::class, 'show'])->name('ideas.show');
Route::middleware('auth')->post('/ideas/{id}/like', [\App\Http\Controllers\PublicIdeaController::class, 'like'])->name('ideas.like');

// ---- Khu nội bộ (đã đăng nhập + đã verify + đã approved) ----
Route::middleware(['auth', 'verified.to.login', 'approved.to.login'])->group(function () {

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // TODO: Thêm các route nội bộ cho ý tưởng ở đây khi cần
    // Route::prefix('my-ideas')->group(function () {
    //     Route::get('/', [IdeaController::class, 'myIdeas'])->name('my-ideas.index');
    //     Route::get('/create', [IdeaController::class, 'create'])->name('my-ideas.create');
    //     // ...
    // });
});

// --- Nhóm Admin: 1 trang duy nhất + các action --- //
Route::middleware(['auth', 'verified.to.login', 'approved.to.login', 'is.admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        // Trang một trang (tab theo query)
        Route::get('/', [\App\Http\Controllers\Admin\AdminHomeController::class, 'index'])
            ->name('home');

        Route::get('/approvals', [\App\Http\Controllers\Admin\ApprovalController::class, 'index'])
            ->name('approvals.index');
        Route::post('/approvals/{user}/update-role', [\App\Http\Controllers\Admin\ApprovalController::class, 'updateRole'])
            ->name('approvals.updateRole');

        Route::get('/users', [\App\Http\Controllers\Admin\UserRoleController::class, 'index'])
            ->name('users.index');
        Route::post('/users/{user}/roles', [\App\Http\Controllers\Admin\UserRoleController::class, 'updateRoles'])
            ->name('users.roles.update');

        // Approvals
        Route::post('/approvals/{user}/approve', [\App\Http\Controllers\Admin\ApprovalActionController::class, 'approve'])
            ->name('approvals.approve');
        Route::post('/approvals/{user}/reject', [\App\Http\Controllers\Admin\ApprovalActionController::class, 'reject'])
            ->name('approvals.reject');

        // Users
        Route::post('/users/{user}/role', [\App\Http\Controllers\Admin\UserActionController::class, 'updateRole'])
            ->name('users.role');
        Route::post('/users/{user}/lock', [\App\Http\Controllers\Admin\UserActionController::class, 'lock'])
            ->name('users.lock');
        Route::post('/users/{user}/unlock', [\App\Http\Controllers\Admin\UserActionController::class, 'unlock'])
            ->name('users.unlock');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserActionController::class, 'destroy'])
            ->name('users.destroy');

        // Taxonomies (3 nhóm dùng form nhỏ trong 1 trang)
        Route::post('/taxonomies/faculties', [\App\Http\Controllers\Admin\TaxonomyActionController::class, 'storeFaculty'])->name('tax.faculties.store');
        Route::post('/taxonomies/faculties/{faculty}', [\App\Http\Controllers\Admin\TaxonomyActionController::class, 'updateFaculty'])->name('tax.faculties.update');
        Route::post('/taxonomies/faculties/{faculty}/del', [\App\Http\Controllers\Admin\TaxonomyActionController::class, 'destroyFaculty'])->name('tax.faculties.destroy');

        Route::post('/taxonomies/categories', [\App\Http\Controllers\Admin\TaxonomyActionController::class, 'storeCategory'])->name('tax.categories.store');
        Route::post('/taxonomies/categories/{category}', [\App\Http\Controllers\Admin\TaxonomyActionController::class, 'updateCategory'])->name('tax.categories.update');
        Route::post('/taxonomies/categories/{category}/del', [\App\Http\Controllers\Admin\TaxonomyActionController::class, 'destroyCategory'])->name('tax.categories.destroy');

        Route::post('/taxonomies/tags', [\App\Http\Controllers\Admin\TaxonomyActionController::class, 'storeTag'])->name('tax.tags.store');
        Route::post('/taxonomies/tags/{tag}', [\App\Http\Controllers\Admin\TaxonomyActionController::class, 'updateTag'])->name('tax.tags.update');
        Route::post('/taxonomies/tags/{tag}/del', [\App\Http\Controllers\Admin\TaxonomyActionController::class, 'destroyTag'])->name('tax.tags.destroy');

        // Ideas (MVP: đổi trạng thái + gán reviewer) - TODO: Uncomment when IdeaActionController is created
        // Route::post('/ideas/{idea}/status', [\App\Http\Controllers\Admin\IdeaActionController::class, 'updateStatus'])->name('ideas.status');
        // Route::post('/ideas/{idea}/reviewer', [\App\Http\Controllers\Admin\IdeaActionController::class, 'assignReviewer'])->name('ideas.reviewer');
    });

// ---- Khu người dùng (chỉ cần đăng nhập) ----
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes Breeze (login / register / verify / forgot ...)
require __DIR__ . '/auth.php';
