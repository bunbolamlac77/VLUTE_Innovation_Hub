@if (auth()->user()?->hasRole('admin'))

    {{-- Admin users: redirect to admin panel --}}

    <script>window.location.href = '{{ route('admin.home', ['tab' => 'approvals']) }}';</script>

@else

{{-- Regular users: use main layout with role widgets --}}

@extends('layouts.main')



@section('title', 'Bảng điều khiển - VLUTE Innovation Hub')



@section('content')

    @php

        $user = auth()->user();

        

        // --- 1. LOGIC CHO REVIEWER (Trung tâm, BGH, Reviewer) ---

        $isReviewer = $user && ($user->hasRole('center') || $user->hasRole('board') || $user->hasRole('reviewer'));

        $reviewQueue = collect();

        $stats = []; 

        

        if ($isReviewer) {

            // Hàng chờ duyệt

            $reviewQueue = \App\Models\Idea::whereIn('status', [

                'submitted_center', 'needs_change_center', 'approved_center',

                'submitted_board', 'needs_change_board',

            ])->with(['owner', 'faculty', 'category'])->orderBy('updated_at', 'asc')->limit(10)->get();



            // Thống kê (Chỉ dành cho Trung tâm & BGH)

            if ($user->hasRole('center') || $user->hasRole('board')) {

                $stats['total_ideas'] = \App\Models\Idea::count();

                $stats['pending_ideas'] = \App\Models\Idea::whereIn('status', ['submitted_center', 'submitted_board'])->count();

                $stats['active_competitions'] = \App\Models\Competition::where('status', 'open')->count();

            }

        }



        // --- 2. LOGIC CHO GIẢNG VIÊN (Staff/Mentor) ---

        $mentorInvites = collect();

        $mentoredIdeas = collect();

        

        if ($user && $user->hasRole('staff')) {

            // Lời mời Mentor đang chờ

            $mentorInvites = \App\Models\IdeaInvitation::with(['idea','inviter'])

                ->where('email', $user->email)

                ->where('status', 'pending')

                ->where('role', 'mentor')

                ->where(function($q){ $q->whereNull('expires_at')->orWhere('expires_at','>', now()); })

                ->latest()

                ->limit(5)

                ->get();



            // Các dự án đang hướng dẫn (Đã là thành viên với vai trò mentor)

            $mentoredIdeas = \App\Models\IdeaMember::where('user_id', $user->id)

                ->where('role', 'mentor')

                ->with('idea')

                ->latest()

                ->limit(5)

                ->get();

        }



        // --- 3. LOGIC CHO DOANH NGHIỆP (Enterprise) ---

        $myChallenges = collect();

        if ($user && $user->hasRole('enterprise')) {

            $myChallenges = \App\Models\Challenge::where('owner_id', $user->id)

                ->withCount('submissions')

                ->latest()

                ->limit(5)

                ->get();

        }



        // --- 4. LOGIC CHO SINH VIÊN (Student) ---

        $myDrafts = collect();

        if ($user && $user->hasRole('student')) {

            $myDrafts = \App\Models\Idea::where('owner_id', $user->id)->whereIn('status', [

                'draft', 'needs_change_center', 'needs_change_board',

                'submitted_center', 'submitted_board', 'approved_final',

            ])->latest()->limit(6)->get();

        }



        // --- 5. LOGIC CHUNG (Cuộc thi đã tham gia) ---

        $myRegistrations = collect();

        if ($user) {

            $myRegistrations = \App\Models\CompetitionRegistration::with(['competition'])

                ->withCount('submissions')

                ->where('user_id', $user->id)

                ->latest()

                ->limit(6)

                ->get();

        }

    @endphp



    <section style="padding: 48px 0;">

        <div class="container" style="display: grid; grid-template-columns: 1fr; gap: 32px;">

            <style>

                /* CSS Dashboard */

                .dash-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: none; overflow: hidden; }

                .dash-card .card-body { padding: 20px; }

                .dash-title { margin: 0; font-size: 18px; font-weight: 700; color: #0f172a; }

                .dash-subtitle { margin-top: 4px; color: #64748b; font-size: 13px; }

                

                /* Table Styles */

                .dash-table { width: 100%; border-collapse: separate; border-spacing: 0; }

                .dash-table thead th { font-size: 12px; text-transform: uppercase; letter-spacing: .06em; color: #64748b; background: #f8fafc; padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }

                .dash-table tbody td { padding: 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 14px; }

                .dash-table tbody tr:hover { background: #f9fafb; }

                

                /* Badges & Buttons */

                .badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 4px 8px; font-size: 11px; font-weight: 600; border: 1px solid rgba(0,0,0,.06); }

                .badge-blue { background: rgba(59,130,246,.10); color: #1d4ed8; border-color: rgba(59,130,246,.2); }

                .badge-amber { background: rgba(245,158,11,.10); color: #b45309; border-color: rgba(245,158,11,.2); }

                .badge-green { background: rgba(16,185,129,.10); color: #047857; border-color: rgba(16,185,129,.2); }

                .badge-red { background: rgba(239,68,68,.10); color: #b91c1c; border-color: rgba(239,68,68,.2); }

                

                .muted { color: #6b7280; font-size: 12px; }

                .cell-right { text-align: right; }

                

                .btn-ghost { border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px 12px; font-weight: 600; color: #334155; text-decoration: none; font-size: 13px; transition: all 0.2s; }

                .btn-ghost:hover { background: #f1f5f9; color: #0f172a; }

                

                .btn-review { display:inline-flex; align-items:center; justify-content:center; padding:6px 12px; border-radius:8px; background:#1d4ed8; color:#fff !important; font-weight:600; text-decoration:none; font-size: 13px; }

                .btn-review:hover { background:#1e40af; }



                /* Stats Grid for Admin/Center */

                .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }

                .stat-card { background: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid #e2e8f0; }

                .stat-value { font-size: 24px; font-weight: 700; color: #0f172a; }

                .stat-label { font-size: 13px; color: #64748b; font-weight: 500; }

            </style>



            {{-- --- KHỐI 1: DÀNH CHO TRUNG TÂM / BGH / REVIEWER --- --}}

            @if ($isReviewer)

                {{-- Thống kê nhanh (Chỉ hiện cho Center/Board) --}}

                @if(!empty($stats))

                    <div class="stats-grid">

                        <div class="stat-card">

                            <div class="stat-value">{{ $stats['total_ideas'] }}</div>

                            <div class="stat-label">Tổng ý tưởng</div>

                        </div>

                        <div class="stat-card">

                            <div class="stat-value" style="color: #eab308">{{ $stats['pending_ideas'] }}</div>

                            <div class="stat-label">Đang chờ duyệt</div>

                        </div>

                        <div class="stat-card">

                            <div class="stat-value" style="color: #22c55e">{{ $stats['active_competitions'] }}</div>

                            <div class="stat-label">Cuộc thi đang mở</div>

                        </div>

                    </div>

                @endif



                {{-- Hàng chờ duyệt --}}

                <div class="dash-card">

                    <div class="card-body">

                        <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 16px;">

                            <div>

                                <h2 class="dash-title">Hàng chờ phản biện</h2>

                                <div class="dash-subtitle">Các ý tưởng cần xử lý gần đây</div>

                            </div>

                            <div class="dash-actions"><a href="{{ route('manage.review-queue.index') }}" class="btn btn-primary">Xem tất cả</a></div>

                        </div>

                        <div class="table-responsive" style="border: 1px solid #eef2f7; border-radius: 8px; overflow: hidden;">

                            <table class="dash-table">

                                <thead>

                                    <tr>

                                        <th>Tiêu đề</th>

                                        <th>Chủ sở hữu</th>

                                        <th>Trạng thái</th>

                                        <th class="cell-right">Hành động</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @forelse ($reviewQueue as $idea)

                                        <tr>

                                            <td style="font-weight: 600; color:#0f172a; max-width: 300px;">

                                                <div class="truncate">{{ $idea->title }}</div>

                                                <div class="muted">{{ $idea->faculty->name ?? 'Chưa phân khoa' }}</div>

                                            </td>

                                            <td>{{ $idea->owner->name }}</td>

                                            <td>

                                                @php

                                                    $map = [

                                                        'submitted_center' => ['label' => 'Đã nộp (TT)', 'class' => 'badge-blue'],

                                                        'needs_change_center' => ['label' => 'Sửa (TT)', 'class' => 'badge-amber'],

                                                        'submitted_board' => ['label' => 'Đã nộp (BGH)', 'class' => 'badge-blue'],

                                                        'needs_change_board' => ['label' => 'Sửa (BGH)', 'class' => 'badge-amber'],

                                                        'approved_final' => ['label' => 'Đã duyệt', 'class' => 'badge-green'],

                                                    ];

                                                    $info = $map[$idea->status] ?? ['label' => $idea->status, 'class' => 'badge-blue'];

                                                @endphp

                                                <span class="badge {{ $info['class'] }}">{{ $info['label'] }}</span>

                                            </td>

                                            <td class="cell-right">

                                                <a href="{{ route('manage.review.form', $idea) }}" class="btn-review">Duyệt</a>

                                            </td>

                                        </tr>

                                    @empty

                                        <tr><td colspan="4" class="muted" style="text-align: center; padding: 20px;">Không có ý tưởng nào cần duyệt.</td></tr>

                                    @endforelse

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            @endif



            {{-- --- KHỐI 2: DÀNH CHO DOANH NGHIỆP --- --}}

            @if ($user && $user->hasRole('enterprise'))

                <div class="dash-card">

                    <div class="card-body">

                        <div style="display:flex; justify-content: space-between; align-items:flex-end; gap: 16px; margin-bottom: 20px;">

                            <div>

                                <h2 class="dash-title">Doanh nghiệp & Thách thức</h2>

                                <div class="dash-subtitle">Quản lý các vấn đề đã đăng tải</div>

                            </div>

                            <a href="{{ route('enterprise.challenges.create') }}" class="btn-review">+ Đăng Thách thức</a>

                        </div>

                        

                        {{-- Danh sách Challenges --}}

                        <div class="grid gap-4">

                            @forelse ($myChallenges as $challenge)

                                <div style="display:flex; align-items:center; justify-content:space-between; padding: 12px; border: 1px solid #f1f5f9; border-radius: 8px;">

                                    <div>

                                        <div style="font-weight: 600; color: #0f172a;">{{ $challenge->title }}</div>

                                        <div class="muted">Hết hạn: {{ \Carbon\Carbon::parse($challenge->valid_until)->format('d/m/Y') }}</div>

                                    </div>

                                    <div style="display:flex; gap: 12px; align-items:center;">

                                        <div style="text-align: right;">

                                            <div style="font-weight: 700; font-size: 16px;">{{ $challenge->submissions_count }}</div>

                                            <div class="muted" style="font-size: 10px;">GIẢI PHÁP</div>

                                        </div>

                                        <a href="{{ route('enterprise.challenges.show', $challenge->id) }}" class="btn-ghost">Chi tiết</a>

                                    </div>

                                </div>

                            @empty

                                <div class="muted">Bạn chưa đăng tải thách thức nào.</div>

                            @endforelse

                        </div>

                        <div style="margin-top: 16px; text-align: center;">

                            <a href="{{ route('enterprise.challenges.index') }}" style="font-size: 13px; font-weight: 600; text-decoration: none;">Xem tất cả quản lý &rarr;</a>

                        </div>

                    </div>

                </div>

            @endif



            {{-- --- KHỐI 3: DÀNH CHO GIẢNG VIÊN (MENTOR) --- --}}

            @if ($user && $user->hasRole('staff'))

                <div class="grid md:grid-cols-2 gap-6">

                    {{-- 3.1 Dự án đang hướng dẫn --}}

                    <div class="dash-card">

                        <div class="card-body">

                            <h2 class="dash-title">Dự án đang hướng dẫn</h2>

                            <div class="dash-subtitle" style="margin-bottom: 12px;">Các nhóm sinh viên bạn đang hỗ trợ</div>

                            

                            <div class="grid gap-3">

                                @forelse ($mentoredIdeas as $membership)

                                    <div style="display: flex; gap: 10px; align-items: flex-start; padding-bottom: 10px; border-bottom: 1px solid #f1f5f9;">

                                        <div style="flex:1;">

                                            <a href="{{ route('my-ideas.show', $membership->idea->id) }}" style="font-weight: 600; color: #0f172a; text-decoration: none; display:block;">

                                                {{ $membership->idea->title }}

                                            </a>

                                            <div class="muted">Sinh viên: {{ $membership->idea->owner->name ?? 'N/A' }}</div>

                                        </div>

                                        <span class="badge badge-blue">Mentor</span>

                                    </div>

                                @empty

                                    <div class="muted">Bạn chưa hướng dẫn nhóm nào.</div>

                                @endforelse

                            </div>

                            @if($mentoredIdeas->count() > 0)

                                <div style="margin-top: 12px;"><a href="{{ route('mentor.ideas') }}" class="btn-ghost" style="width:100%; display:block; text-align:center;">Xem tất cả</a></div>

                            @endif

                        </div>

                    </div>



                    {{-- 3.2 Lời mời Mentor --}}

                    <div class="dash-card">

                        <div class="card-body">

                            <h2 class="dash-title">Lời mời mới</h2>

                            <div class="dash-subtitle" style="margin-bottom: 12px;">Các yêu cầu chờ bạn phản hồi</div>

                            

                            <div class="grid gap-3">

                                @forelse ($mentorInvites as $inv)

                                    <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px;">

                                        <div style="font-size: 13px; font-weight: 600; margin-bottom: 4px;">{{ $inv->idea?->title }}</div>

                                        <div class="muted" style="margin-bottom: 8px;">Từ: {{ $inv->inviter?->name }}</div>

                                        <div style="display:flex; gap: 8px;">

                                            <a href="{{ route('invitations.accept', $inv->token) }}" class="badge badge-green" style="text-decoration:none; cursor:pointer;">Đồng ý</a>

                                            <a href="{{ route('invitations.decline', $inv->token) }}" class="badge badge-red" style="text-decoration:none; cursor:pointer;">Từ chối</a>

                                        </div>

                                    </div>

                                @empty

                                    <div class="muted">Không có lời mời nào mới.</div>

                                @endforelse

                            </div>

                        </div>

                    </div>

                </div>

            @endif



            {{-- --- KHỐI 4: DÀNH CHO SINH VIÊN --- --}}

            @if ($user && $user->hasRole('student'))

                <div class="dash-card">

                    <div class="card-body">

                        <div style="display:flex; justify-content: space-between; align-items:flex-end; gap: 16px; margin-bottom: 12px;">

                            <div>

                                <h2 class="dash-title">Ý tưởng của tôi</h2>

                                <div class="dash-subtitle">Theo dõi trạng thái các ý tưởng sáng tạo</div>

                            </div>

                            <div class="dash-actions"><a href="{{ route('my-ideas.index') }}" class="btn btn-primary">Xem tất cả</a></div>

                        </div>

                        <div class="grid gap-3">

                            @forelse ($myDrafts as $idea)

                                @php

                                    $map = [

                                        'draft' => ['label' => 'Nháp', 'class' => 'badge-amber'],

                                        'needs_change_center' => ['label' => 'Sửa (TT)', 'class' => 'badge-amber'],

                                        'needs_change_board' => ['label' => 'Sửa (BGH)', 'class' => 'badge-amber'],

                                        'submitted_center' => ['label' => 'Đã nộp (TT)', 'class' => 'badge-blue'],

                                        'submitted_board' => ['label' => 'Đã nộp (BGH)', 'class' => 'badge-blue'],

                                        'approved_final' => ['label' => 'Đã duyệt', 'class' => 'badge-green'],

                                    ];

                                    $info = $map[$idea->status] ?? ['label' => $idea->status, 'class' => 'badge-blue'];

                                @endphp

                                <article class="flex items-center gap-3 border border-slate-200 rounded-xl p-3 bg-white">

                                    <div class="flex-1 min-w-0">

                                        <div class="font-bold text-slate-900 truncate">{{ $idea->title }}</div>

                                        <div class="muted truncate">Cập nhật {{ $idea->updated_at->diffForHumans() }}</div>

                                    </div>

                                    <div><span class="badge {{ $info['class'] }}">{{ $info['label'] }}</span></div>

                                    <div class="cell-right">

                                        <a href="{{ route('my-ideas.show', $idea->id) }}" class="btn-ghost">Chi tiết</a>

                                    </div>

                                </article>

                            @empty

                                <div>Chưa có ý tưởng nào. <a href="{{ route('my-ideas.create') }}" class="text-indigo-600 font-semibold">Tạo ý tưởng mới</a></div>

                            @endforelse

                        </div>

                    </div>

                </div>

            @endif



            {{-- --- KHỐI 5: CHUNG CHO MỌI NGƯỜI (CUỘC THI) --- --}}

            <div class="dash-card">

                <div class="card-body">

                    <div style="display:flex; justify-content: space-between; align-items:flex-end; gap: 16px; margin-bottom: 12px;">

                        <div>

                            <h2 class="dash-title">Cuộc thi tôi tham gia</h2>

                            <div class="dash-subtitle">Các cuộc thi bạn đã đăng ký</div>

                        </div>

                        <div class="dash-actions"><a href="{{ route('my-competitions.index') }}" class="btn btn-primary">Xem tất cả</a></div>

                    </div>



                    <div class="grid gap-3">

                        @forelse ($myRegistrations as $reg)

                            @php

                                $comp = $reg->competition;

                                $end = $comp?->end_date;

                                $isOpen = $comp && $comp->status === 'open' && (!$end || $end->isFuture());

                                $submitted = (int) ($reg->submissions_count ?? 0);

                            @endphp

                            <article class="flex items-center gap-3 border border-slate-200 rounded-xl p-3 bg-white">

                                <div class="flex-1 min-w-0">

                                    <div class="font-bold text-slate-900 truncate">{{ $comp?->title ?? 'Cuộc thi (đã xóa)' }}</div>

                                    <div class="muted truncate">{{ $reg->team_name ?? '(Cá nhân)' }} @if($submitted > 0) <span style="color:#16a34a">• Đã nộp bài</span> @endif</div>

                                </div>

                                <div>

                                    <span class="badge {{ $isOpen ? 'badge-green' : 'badge-blue' }}">{{ $comp?->status == 'open' ? 'Đang mở' : 'Đã đóng' }}</span>

                                </div>

                                <div class="cell-right">

                                    @if ($isOpen)

                                        <a href="{{ route('competitions.submit.create', $reg->id) }}" class="btn-review">Nộp bài</a>

                                    @else

                                        <a href="{{ route('competitions.show', $comp?->slug ?? '') }}" class="btn-ghost">Xem</a>

                                    @endif

                                </div>

                            </article>

                        @empty

                            <div class="muted">Bạn chưa đăng ký cuộc thi nào. <a href="{{ route('competitions.index') }}" class="text-indigo-600 font-semibold">Khám phá ngay</a></div>

                        @endforelse

                    </div>

                </div>

            </div>



        </div>

    </section>

@endsection

@endif
