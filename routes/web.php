<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\CompetitionRegistrationController;
use App\Http\Controllers\MyCompetitionsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Api\AIController;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('/about', fn() => view('about'));

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
Route::middleware('auth')->post('/ideas/{id}/comments', [\App\Http\Controllers\PublicIdeaController::class, 'storeComment'])->name('ideas.comments.store');

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

    // AI routes (Authenticated)
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::post('/review-insight', [AIController::class, 'reviewInsight'])->name('review');
        Route::post('/vision', [AIController::class, 'analyzeVisual'])->name('vision');
        Route::post('/check-duplicate', [AIController::class, 'checkDuplicate'])->name('duplicate');
        Route::post('/suggest-tech', [AIController::class, 'suggestTechStack'])->name('tech_stack');
        Route::post('/scout-solutions', [AIController::class, 'scoutSolutions'])->name('scout');
        Route::get('/seed', [AIController::class, 'seedEmbeddings'])->name('seed');
        Route::get('/debug', [AIController::class, 'debugInfo'])->name('debug');
        
        // Student AI Business Consultant
        Route::post('/student/business-plan', [\App\Http\Controllers\Api\StudentAIController::class, 'analyzeBusinessPlan'])
            ->name('student.business-plan');
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
        
        // Thợ săn Giải pháp
        Route::get('/scout', function () {
            return view('enterprise.scout');
        })->name('scout');
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

        // Admin CRUD: competitions, news, challenges, banners
        Route::resource('competitions', \App\Http\Controllers\Admin\AdminCompetitionController::class);
        Route::get('/competitions/{competition}/export', [\App\Http\Controllers\Admin\AdminCompetitionController::class, 'exportRegistrations'])
            ->name('competitions.export');
        Route::resource('news', \App\Http\Controllers\Admin\AdminNewsController::class);
        Route::resource('challenges', \App\Http\Controllers\Admin\AdminChallengeController::class);
        Route::resource('banners', \App\Http\Controllers\Admin\AdminBannerController::class);

        // TEMP: Debug schema for profiles table (admin-only). Remove after verification.
        Route::get('/_debug/schema/profiles', function () {
            try {
                $cols = \Illuminate\Support\Facades\Schema::getColumnListing('profiles');
                return response()->json([
                    'ok' => true,
                    'has_class_name' => in_array('class_name', $cols, true),
                    'columns' => $cols,
                ]);
            } catch (\Throwable $e) {
                return response()->json([
                    'ok' => false,
                    'error' => $e->getMessage(),
                ], 500);
            }
        })->name('admin.debug.schema.profiles');

        // Admin tool: Seed 10 ideas, 10 competitions, 10 challenges (demo data)
        Route::get('/_tools/seed-demo', function () {
            // 0) Ensure students exist
            $createdStudents = 0;
            for ($i = 6; $i <= 15; $i++) {
                $email = "student{$i}@st.vlute.edu.vn";
                $u = \App\Models\User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => 'Student '.str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                        'password' => bcrypt('Password@123'),
                        'role' => 'student',
                        'approval_status' => 'approved',
                        'is_active' => true,
                        'email_verified_at' => now(),
                    ]
                );
                $u->syncRoles(['student']);
                if ($u->wasRecentlyCreated) $createdStudents++;
            }
            $students = \App\Models\User::where('role', 'student')->get();

            // 1) Ensure faculties & categories
            $faculties = [
                ['code' => 'CNTT', 'name' => 'Khoa Công nghệ thông tin', 'description' => 'CNTT', 'sort_order' => 1],
                ['code' => 'DDT', 'name' => 'Khoa Điện - Điện tử', 'description' => 'Điện - Điện tử', 'sort_order' => 2],
                ['code' => 'CKD', 'name' => 'Khoa Cơ khí - Động lực', 'description' => 'Cơ khí - Động lực', 'sort_order' => 3],
                ['code' => 'KT', 'name' => 'Khoa Kinh tế', 'description' => 'Kinh tế', 'sort_order' => 4],
                ['code' => 'NN', 'name' => 'Khoa Ngoại ngữ', 'description' => 'Ngoại ngữ', 'sort_order' => 5],
            ];
            foreach ($faculties as $f) { \App\Models\Faculty::firstOrCreate(['code' => $f['code']], $f); }

            $categories = [
                ['slug' => 'cong-nghe-thong-tin', 'name' => 'Công nghệ thông tin', 'description' => 'CNTT', 'sort_order' => 1],
                ['slug' => 'dien-tu-tu-dong-hoa', 'name' => 'Điện tử - Tự động hóa', 'description' => 'IoT/Điện tử', 'sort_order' => 2],
                ['slug' => 'co-khi-che-tao', 'name' => 'Cơ khí - Chế tạo', 'description' => 'Cơ khí', 'sort_order' => 3],
                ['slug' => 'kinh-te-quan-ly', 'name' => 'Kinh tế - Quản lý', 'description' => 'Kinh tế', 'sort_order' => 4],
                ['slug' => 'giao-duc-dao-tao', 'name' => 'Giáo dục - Đào tạo', 'description' => 'Giáo dục', 'sort_order' => 5],
            ];
            foreach ($categories as $c) { \App\Models\Category::firstOrCreate(['slug' => $c['slug']], $c); }

            $facultyIds = \App\Models\Faculty::pluck('id')->all();
            $categoryIds = \App\Models\Category::pluck('id')->all();

            // 2) Seed 10 ideas
            $ideaTitles = [
                'Nền tảng chia sẻ tài liệu học tập thông minh',
                'Ứng dụng theo dõi sức khỏe cho sinh viên',
                'Hệ thống khảo sát ý kiến giảng viên – sinh viên',
                'AI gợi ý lộ trình học lập trình',
                'Cổng thông tin việc làm bán thời gian',
                'IoT giám sát chất lượng không khí trong lớp',
                'Website học phát âm tiếng Anh bằng AI',
                'Quản lý câu lạc bộ và hoạt động ngoại khóa',
                'Sàn mua bán đồ cũ trong trường',
                'Chatbot giải đáp thủ tục học vụ',
                'Bản đồ phòng học và lịch trống theo thời gian thực',
                'Hệ thống điểm danh bằng nhận diện khuôn mặt',
            ];
            $ideasMade = 0;
            foreach ($ideaTitles as $title) {
                if ($ideasMade >= 10) break;
                $owner = $students->random();
                $slug = \Illuminate\Support\Str::slug($title);
                $idea = \App\Models\Idea::firstOrCreate([
                    'slug' => $slug,
                ], [
                    'owner_id' => $owner->id,
                    'title' => $title,
                    'summary' => 'Tóm tắt: '.$title,
                    'description' => '<p>Mô tả chi tiết cho ý tưởng: '.e($title).'.</p>',
                    'content' => 'Các module chức năng, kiến trúc, kế hoạch triển khai...',
                    'status' => 'approved_final',
                    'visibility' => 'public',
                    'faculty_id' => $facultyIds ? $facultyIds[array_rand($facultyIds)] : null,
                    'category_id' => $categoryIds ? $categoryIds[array_rand($categoryIds)] : null,
                    'like_count' => rand(0, 200),
                ]);
                if ($idea->wasRecentlyCreated) $ideasMade++;
            }

            // 3) Seed 10 competitions
            $admin = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::first();
            $compTitles = [
                'Hackathon Sáng tạo số', 'Cuộc thi Khởi nghiệp xanh', 'Makeathon IoT 2025', 'AI for Education Challenge',
                'Thiết kế 3D CAD 2025', 'Smart City Innovation', 'Fintech Student Cup', 'Robotics Cup VLUTE',
                'Digital Marketing Arena', 'Website Accessibility Contest', 'Open Data Hack 2025',
            ];
            $compMade = 0;
            foreach ($compTitles as $t) {
                if ($compMade >= 10) break;
                $start = now()->subDays(rand(0, 20));
                $end = (clone $start)->addDays(rand(15, 60));
                $status = rand(0, 10) < 8 ? 'open' : 'closed';
                $comp = \App\Models\Competition::firstOrCreate([
                    'slug' => \Illuminate\Support\Str::slug($t)
                ], [
                    'title' => $t,
                    'description' => '<p>Mô tả cuộc thi: '.e($t).'</p>',
                    'banner_url' => '/images/panel-truong.jpg',
                    'start_date' => $start,
                    'end_date' => $end,
                    'status' => $status,
                    'created_by' => optional($admin)->id,
                ]);
                if ($comp->wasRecentlyCreated) $compMade++;
            }

            // 4) Seed 10 challenges
            $orgNames = ['ACME Corp','GreenTech VN','EduAI Labs','CityWorks','MediCare'];
            $orgIds = [];
            foreach ($orgNames as $n) {
                $org = \App\Models\Organization::firstOrCreate(['name' => $n], ['type' => 'company']);
                $orgIds[] = $org->id;
            }
            $challengeTitles = [
                'Tối ưu lịch dạy học và phòng học',
                'Giảm thất thoát điện năng trong toà nhà',
                'Theo dõi chuỗi lạnh cho nông sản',
                'Hệ thống quản lý bãi xe thông minh',
                'Chatbot hỗ trợ tuyển dụng',
                'Phân loại rác thải bằng thị giác máy tính',
                'Giám sát chất lượng nước nuôi trồng',
                'Dashboard realtime cho nhà máy',
                'Dự báo nhu cầu vật tư y tế',
                'Quản lý tài sản số hoá bằng QR/NFC',
                'Truy xuất nguồn gốc thuỷ sản',
            ];
            $chalMade = 0;
            foreach ($challengeTitles as $ct) {
                if ($chalMade >= 10) break;
                $deadline = rand(0, 1) ? now()->addDays(rand(10, 60)) : null;
                $status = $deadline && $deadline->isFuture() ? 'open' : (rand(0, 1) ? 'closed' : 'draft');
                $c = \App\Models\Challenge::firstOrCreate([
                    'title' => $ct,
                    'organization_id' => $orgIds[array_rand($orgIds)],
                ], [
                    'description' => 'Mô tả: '.e($ct).'.',
                    'problem_statement' => 'Bối cảnh và vấn đề chi tiết cần giải quyết.',
                    'requirements' => 'Yêu cầu: mô tả giải pháp, lộ trình, sơ đồ kỹ thuật.',
                    'deadline' => $deadline,
                    'reward' => rand(0,1) ? (rand(10,50)*1000000).' VND' : null,
                    'status' => $status,
                ]);
                if ($c->wasRecentlyCreated) $chalMade++;
            }

            return response()->json([
                'ok' => true,
                'created' => [
                    'students' => $createdStudents,
                    'ideas' => $ideasMade,
                    'competitions' => $compMade,
                    'challenges' => $chalMade,
                ]
            ]);
        })->name('admin.tools.seed_demo');
    });

// ---- Khu người dùng (chỉ cần đăng nhập) ----
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/toast/dismiss', [ProfileController::class, 'dismissToast'])->name('profile.toast.dismiss');

    // Notifications: đánh dấu đã đọc
    Route::post('/notifications/read-all', function () {
        $user = auth()->user();
        if ($user !== null) {
            $user->unreadNotifications->markAsRead();
        }
        return back();
    })->name('notifications.readAll');

    Route::get('/notifications/{id}/read', function (string $id) {
        $user = auth()->user();
        $redirect = url()->previous();
        if ($user) {
            $notification = $user->notifications()->whereKey($id)->firstOrFail();
            if (is_null($notification->read_at)) {
                $notification->markAsRead();
            }
            $data = $notification->data ?? [];
            if (is_array($data)) {
                $target = $data['url'] ?? $data['link'] ?? null;
                if ($target) {
                    if (filter_var($target, FILTER_VALIDATE_URL)) {
                        $redirect = $target;
                    } elseif (is_string($target)) {
                        $redirect = url($target);
                    }
                }
            }
        }
        return redirect($redirect);
    })->name('notifications.read');
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
