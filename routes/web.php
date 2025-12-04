<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\CompetitionRegistrationController;
use App\Http\Controllers\MyCompetitionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('/about', function () {
    return view('about');
});

// Bản tin khoa học - Trang công khai
Route::get('/scientific-news', [\App\Http\Controllers\ScientificNewsController::class, 'index'])->name('scientific-news.index');
Route::get('/scientific-news/{news}', [\App\Http\Controllers\ScientificNewsController::class, 'show'])->name('scientific-news.show');

// Cuộc thi & Sự kiện - Trang công khai
Route::get('/competitions', [CompetitionController::class, 'index'])->name('competitions.index');
Route::get('/competitions/{competition:slug}', [CompetitionController::class, 'show'])->name('competitions.show');

// Challenges - Trang công khai
Route::get('/challenges', [\App\Http\Controllers\ChallengeController::class, 'index'])->name('challenges.index');
Route::get('/challenges/{challenge}', [\App\Http\Controllers\ChallengeController::class, 'show'])->name('challenges.show');

// Events page (Cuộc thi & Sự kiện)
Route::get('/events', [\App\Http\Controllers\EventsController::class, 'index'])->name('events.index');

// Search page
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Newsletter subscribe
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Public Ideas (Ngân hàng Ý tưởng) - Trang công khai
Route::get('/ideas', [\App\Http\Controllers\PublicIdeaController::class, 'index'])->name('ideas.index');
Route::get('/ideas/{slug}', [\App\Http\Controllers\PublicIdeaController::class, 'show'])->name('ideas.show');
Route::middleware('auth')->post('/ideas/{id}/like', [\App\Http\Controllers\PublicIdeaController::class, 'like'])->name('ideas.like');

// ---- Khu nội bộ (đã đăng nhập + đã verify + đã approved) ----
Route::middleware(['auth', 'verified.to.login', 'approved.to.login'])->group(function () {

    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // My Ideas - Ý tưởng của tôi
    Route::prefix('my-ideas')->name('my-ideas.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MyIdeasController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\MyIdeasController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\MyIdeasController::class, 'store'])->name('store');
        Route::get('/{idea}', [\App\Http\Controllers\MyIdeasController::class, 'show'])
            ->middleware('can:view,idea')
            ->name('show');
        Route::get('/{idea}/edit', [\App\Http\Controllers\MyIdeasController::class, 'edit'])
            ->middleware('can:update,idea')
            ->name('edit');
        Route::put('/{idea}', [\App\Http\Controllers\MyIdeasController::class, 'update'])
            ->middleware('can:update,idea')
            ->name('update');
        Route::delete('/{idea}', [\App\Http\Controllers\MyIdeasController::class, 'destroy'])
            ->middleware('can:delete,idea')
            ->name('destroy');
        Route::post('/{idea}/submit', [\App\Http\Controllers\MyIdeasController::class, 'submit'])
            ->middleware('can:submit,idea')
            ->name('submit');
        Route::post('/{idea}/invite', [\App\Http\Controllers\MyIdeasController::class, 'invite'])
            ->middleware('can:invite,idea')
            ->name('invite');

        // Team-only comments for mentors and members
        Route::post('/{idea}/comments', [\App\Http\Controllers\IdeaCommentController::class, 'store'])
            ->name('comments.store');
        Route::delete('/{idea}/comments/{comment}', [\App\Http\Controllers\IdeaCommentController::class, 'destroy'])
            ->name('comments.destroy');
    });

    // Review Queue - Hàng chờ phản biện (cho Giảng viên, Trung tâm ĐMST, BGH)
    Route::prefix('manage')->name('manage.')->group(function () {
        Route::get('/review-queue', [\App\Http\Controllers\Review\ReviewQueueController::class, 'index'])
            ->name('review-queue.index');

        // Trang hiển thị form để phản biện (GET)
        Route::get('/review/{idea}', [\App\Http\Controllers\Review\ReviewFormController::class, 'show'])
            ->middleware('can:review,idea')
            ->name('review.form');

        // Nơi nhận dữ liệu khi GV bấm nút "Duyệt" / "Yêu cầu sửa" (POST)
        Route::post('/review/{idea}', [\App\Http\Controllers\Review\ReviewFormController::class, 'store'])
            ->middleware('can:review,idea')
            ->name('review.store');
    });

    // Attachments Download
    Route::get('/attachments/{id}/download', [\App\Http\Controllers\AttachmentController::class, 'download'])
        ->name('attachments.download');

    // Mentor Dashboard: các dự án đang hướng dẫn
    Route::get('/mentored-ideas', [\App\Http\Controllers\MentorController::class, 'index'])
        ->middleware('role:staff')
        ->name('mentor.ideas');

    // Nhóm dành riêng cho Doanh nghiệp (Enterprise)
    Route::middleware(['role:enterprise'])->prefix('enterprise')->name('enterprise.')->group(function () {
        // Quản lý Challenge
        Route::get('/challenges', [\App\Http\Controllers\Enterprise\ChallengeManagerController::class, 'index'])->name('challenges.index');
        Route::get('/challenges/create', [\App\Http\Controllers\Enterprise\ChallengeManagerController::class, 'create'])->name('challenges.create');
        Route::post('/challenges', [\App\Http\Controllers\Enterprise\ChallengeManagerController::class, 'store'])->name('challenges.store');
        Route::get('/challenges/{challenge}', [\App\Http\Controllers\Enterprise\ChallengeManagerController::class, 'show'])->name('challenges.show');
        Route::post('/challenges/{challenge}/close', [\App\Http\Controllers\Enterprise\ChallengeManagerController::class, 'close'])->name('challenges.close');
        Route::post('/challenges/{challenge}/reopen', [\App\Http\Controllers\Enterprise\ChallengeManagerController::class, 'reopen'])->name('challenges.reopen');
        Route::post('/challenges/{challenge}/submissions/{submission}/review', [\App\Http\Controllers\Enterprise\ChallengeManagerController::class, 'reviewSubmission'])->name('challenges.submissions.review');
    });
});

// Invitation routes (có thể truy cập khi chưa đăng nhập)
Route::prefix('invitations')->name('invitations.')->group(function () {
    Route::get('/accept/{token}', [\App\Http\Controllers\IdeaInvitationController::class, 'accept'])->name('accept');
    Route::get('/decline/{token}', [\App\Http\Controllers\IdeaInvitationController::class, 'decline'])->name('decline');
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

        // Ideas (MVP: đổi trạng thái + gán reviewer)
        Route::post('/ideas/{idea}/status', [\App\Http\Controllers\Admin\IdeaActionController::class, 'updateStatus'])->name('ideas.status');
        Route::post('/ideas/{idea}/reviewer', [\App\Http\Controllers\Admin\IdeaActionController::class, 'assignReviewer'])->name('ideas.reviewer');

        // Admin CRUD: competitions, news, challenges
        Route::resource('competitions', \App\Http\Controllers\Admin\AdminCompetitionController::class);
        Route::resource('news', \App\Http\Controllers\Admin\AdminNewsController::class);
        Route::resource('challenges', \App\Http\Controllers\Admin\AdminChallengeController::class);
    });

// ---- Khu người dùng (chỉ cần đăng nhập) ----
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route để xử lý đăng ký cuộc thi (cần đăng nhập và verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/competitions/{competition}/register', [CompetitionRegistrationController::class, 'store'])
        ->name('competitions.register');
    
    // Trang danh sách các cuộc thi SV đã đăng ký
    Route::get('/my-competitions', [MyCompetitionsController::class, 'index'])
         ->name('my-competitions.index');

    // Nộp bài dự thi
    Route::get('/my-competitions/{registration}/submit', [\App\Http\Controllers\CompetitionSubmissionController::class, 'create'])
        ->name('competitions.submit.create');
    Route::post('/my-competitions/{registration}/submit', [\App\Http\Controllers\CompetitionSubmissionController::class, 'store'])
        ->name('competitions.submit.store');

    // Chỉnh sửa/Xoá bài nộp dự thi
    Route::get('/my-competitions/submissions/{submission}/edit', [\App\Http\Controllers\CompetitionSubmissionController::class, 'edit'])
        ->name('competitions.submit.edit');
    Route::put('/my-competitions/submissions/{submission}', [\App\Http\Controllers\CompetitionSubmissionController::class, 'update'])
        ->name('competitions.submit.update');
    Route::delete('/my-competitions/submissions/{submission}', [\App\Http\Controllers\CompetitionSubmissionController::class, 'destroy'])
        ->name('competitions.submit.destroy');

    // Nộp bài Challenge (mỗi user 1 submission/Challenge)
    Route::get('/challenges/{challenge}/submit', [\App\Http\Controllers\ChallengeSubmissionController::class, 'create'])
        ->name('challenges.submit.create');
    Route::post('/challenges/{challenge}/submit', [\App\Http\Controllers\ChallengeSubmissionController::class, 'store'])
        ->name('challenges.submit.store');
});

// Routes Breeze (login / register / verify / forgot ...)
require __DIR__ . '/auth.php';
