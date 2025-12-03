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
        $isReviewer = $user && ($user->hasRole('center') || $user->hasRole('board') || $user->hasRole('reviewer'));
        $reviewQueue = collect();
        if ($isReviewer) {
            $reviewQueue = \App\Models\Idea::whereIn('status', [
                'submitted_center',
                'needs_change_center',
                'approved_center',
                'submitted_board',
                'needs_change_board',
            ])->with(['owner', 'faculty', 'category'])->orderBy('updated_at', 'asc')->limit(10)->get();
        }
        // Lời mời Mentor cho giảng viên (đồng bộ với email)
        $mentorInvites = collect();
        if ($user && $user->hasRole('staff')) {
            $mentorInvites = \App\Models\IdeaInvitation::with(['idea','inviter'])
                ->where('email', $user->email)
                ->where('status', 'pending')
                ->where('role', 'mentor')
                ->where(function($q){ $q->whereNull('expires_at')->orWhere('expires_at','>', now()); })
                ->latest()
                ->limit(10)
                ->get();
        }
        $myDrafts = collect();
        if ($user && $user->hasRole('student')) {
            $myDrafts = \App\Models\Idea::where('owner_id', $user->id)->whereIn('status', [
                'draft',
                'needs_change_center',
                'needs_change_board',
                'submitted_center',
                'submitted_board',
                'approved_final',
            ])->latest()->limit(6)->get();
        }
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
                /* Đơn giản hoá kiểu dáng dashboard */
                .dash-card {
                    background: #fff;
                    border: 1px solid #e5e7eb; /* slate-200 */
                    border-radius: 12px;
                    box-shadow: none;
                    overflow: hidden;
                }
                .dash-card .card-body { padding: 20px; }

                .dash-title { margin: 0; font-size: 18px; font-weight: 700; color: #0f172a; }
                .dash-subtitle { margin-top: 4px; color: #64748b; font-size: 12px; }

                .dash-table { width: 100%; border-collapse: separate; border-spacing: 0; }
                .dash-table thead th {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: .06em;
                    color: #64748b; /* slate-500 */
                    background: #f8fafc; /* slate-50 */
                    padding: 10px 12px;
                    border-bottom: 1px solid #e5e7eb;
                }
                .dash-table tbody td { padding: 12px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
                .dash-table tbody tr:hover { background: #f9fafb; }

                .badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 4px 8px; font-size: 12px; font-weight: 600; border: 1px solid rgba(0,0,0,.06); }
                .badge-blue { background: rgba(59,130,246,.10); color: #1d4ed8; border-color: rgba(59,130,246,.2); }
                .badge-amber { background: rgba(245,158,11,.10); color: #b45309; border-color: rgba(245,158,11,.2); }
                .badge-green { background: rgba(16,185,129,.10); color: #047857; border-color: rgba(16,185,129,.2); }

                .muted { color: #6b7280; font-size: 12px; }
                .cell-right { text-align: right; }

                .btn-ghost { border: 1px solid #e5e7eb; border-radius: 10px; padding: 8px 12px; font-weight: 600; color: #0f172a; text-decoration: none; }
                .btn-ghost:hover { background: #f1f5f9; }

                .btn-review { display:inline-flex; align-items:center; justify-content:center; padding:8px 12px; border-radius:10px; background:#1d4ed8; color:#fff !important; font-weight:600; text-decoration:none; border:1px solid transparent; box-shadow:none; }
                .btn-review:hover { background:#1e40af; }
            </style>
            @if ($isReviewer)
                <div class="dash-card">
                    <div class="card-body">
                        <div
                            style="display:flex; justify-content: space-between; align-items:flex-end; gap: 16px; margin-bottom: 12px;">
                            <div>
                                <h2 class="dash-title">Hàng chờ phản biện</h2>
                                <div class="dash-subtitle">10 ý tưởng mới nhất bạn có thể xử lý ngay</div>
                            </div>
                            <div class="dash-actions"><a href="{{ route('manage.review-queue.index') }}"
                                    class="btn btn-primary">Xem tất cả</a></div>
                        </div>
                        <div class="table-responsive" style="border: 1px solid #eef2f7; border-radius: 14px; overflow: hidden;">
                            <table class="dash-table">
                                <thead>
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Chủ sở hữu</th>
                                        <th>Khoa</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày</th>
                                        <th class="cell-right">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reviewQueue as $idea)
                                        <tr>
                                            <td style="font-weight: 700; color:#0f172a;">{{ $idea->title }}</td>
                                            <td>{{ $idea->owner->name }}</td>
                                            <td>{{ $idea->faculty->name ?? 'N/A' }}</td>
                                            <td style="text-align:center;">
                                                @php
                                                    $map = [
                                                        'submitted_center' => ['label' => 'Đã nộp (TTĐMST)', 'class' => 'badge-blue'],
                                                        'needs_change_center' => ['label' => 'Cần chỉnh sửa (TTĐMST)', 'class' => 'badge-amber'],
                                                        'submitted_board' => ['label' => 'Đã nộp (BGH)', 'class' => 'badge-blue'],
                                                        'needs_change_board' => ['label' => 'Cần chỉnh sửa (BGH)', 'class' => 'badge-amber'],
                                                        'approved_final' => ['label' => 'Đã duyệt (BGH)', 'class' => 'badge-green'],
                                                    ];
                                                    $info = $map[$idea->status] ?? ['label' => $idea->status, 'class' => 'badge-blue'];
                                                @endphp
                                                <span class="badge {{ $info['class'] }}">{{ $info['label'] }}</span>
                                            </td>
                                            <td>{{ $idea->updated_at->diffForHumans() }}</td>
                                            <td class="cell-right"><a href="{{ route('manage.review.form', $idea) }}"
                                                    class="btn-review">Xem</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">Không có ý tưởng nào trong hàng chờ.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if ($user && $user->hasRole('staff'))
                <div class="dash-card">
                    <div class="card-body">
                        <div style="display:flex; justify-content: space-between; align-items:flex-end; gap: 16px; margin-bottom: 12px;">
                            <div>
                                <h2 class="dash-title">Lời mời Mentor</h2>
                                <div class="dash-subtitle">Các lời mời tham gia cố vấn dành cho bạn</div>
                            </div>
                        </div>
                        <div class="table-responsive" style="border: 1px solid #eef2f7; border-radius: 14px; overflow: hidden;">
                            <table class="dash-table">
                                <thead>
                                    <tr>
                                        <th>Ý tưởng</th>
                                        <th>Người mời</th>
                                        <th>Thời gian</th>
                                        <th class="cell-right">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($mentorInvites as $inv)
                                        <tr>
                                            <td>
                                                <div style="font-weight:700; color:#0f172a;">{{ $inv->idea?->title ?? 'Ý tưởng (đã xóa)' }}</div>
                                                <div class="muted">{{ $inv->email }}</div>
                                            </td>
                                            <td>
                                                <div style="font-weight:600; color:#0f172a;">{{ $inv->inviter?->name ?? 'N/A' }}</div>
                                                <div class="muted">{{ $inv->inviter?->email ?? '' }}</div>
                                            </td>
                                            <td class="muted">{{ $inv->created_at?->diffForHumans() }}</td>
                                            <td class="cell-right">
                                                <a href="{{ route('invitations.accept', $inv->token) }}" class="btn btn-primary" style="margin-right:8px;">Chấp nhận</a>
                                                <a href="{{ route('invitations.decline', $inv->token) }}" class="btn btn-ghost">Từ chối</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="muted" style="padding:16px 12px;">Không có lời mời nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Cuộc thi tôi đã tham gia --}}
            <div class="dash-card">
                <div class="card-body">
                    <div style="display:flex; justify-content: space-between; align-items:flex-end; gap: 16px; margin-bottom: 12px;">
                        <div>
                            <h2 class="dash-title">Cuộc thi tôi đã tham gia</h2>
                            <div class="dash-subtitle">6 đăng ký gần đây nhất</div>
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
                                    <div class="muted truncate">{{ $reg->team_name ?? '(Cá nhân)' }}@if ($submitted > 0) • Đã nộp: {{ $submitted }}@endif</div>
                                </div>
                                <div>
                                    <span class="badge {{ $isOpen ? 'badge-green' : 'badge-blue' }}">{{ $comp?->status ?? 'n/a' }}</span>
                                </div>
                                <div class="text-right">
                                    @if ($end)
                                        <div class="text-sm">{{ $end->format('d/m/Y H:i') }}</div>
                                        <div class="muted">{{ $isOpen ? 'Còn ' . $end->diffForHumans(null, true) : 'Kết thúc ' . $end->diffForHumans() }}</div>
                                    @else
                                        <span class="muted">Chưa đặt hạn</span>
                                    @endif
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
                            <div class="muted">Bạn chưa đăng ký cuộc thi nào. <a href="{{ route('competitions.index') }}" class="text-indigo-600 font-semibold">Khám phá cuộc thi</a></div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if ($user && $user->hasRole('student'))
                <div class="dash-card">
                    <div class="card-body">
                        <div
                            style="display:flex; justify-content: space-between; align-items:flex-end; gap: 16px; margin-bottom: 12px;">
                            <div>
                                <h2 class="dash-title">Ý tưởng của tôi</h2>
                                <div class="dash-subtitle">Các ý tưởng ở trạng thái nháp, đã nộp, hoặc cần chỉnh sửa</div>
                            </div>
                            <div class="dash-actions"><a href="{{ route('my-ideas.index') }}" class="btn btn-primary">Xem tất
                                    cả</a></div>
                        </div>
                        <div class="grid gap-3">
                            @forelse ($myDrafts as $idea)
                                @php
                                    $map = [
                                        'draft' => ['label' => 'Nháp', 'class' => 'badge-amber'],
                                        'needs_change_center' => ['label' => 'Cần chỉnh sửa (TTĐMST)', 'class' => 'badge-amber'],
                                        'needs_change_board' => ['label' => 'Cần chỉnh sửa (BGH)', 'class' => 'badge-amber'],
                                        'submitted_center' => ['label' => 'Đã nộp (TTĐMST)', 'class' => 'badge-blue'],
                                        'submitted_board' => ['label' => 'Đã nộp (BGH)', 'class' => 'badge-blue'],
                                        'approved_final' => ['label' => 'Đã duyệt (BGH)', 'class' => 'badge-green'],
                                    ];
                                    $info = $map[$idea->status] ?? ['label' => $idea->status, 'class' => 'badge-blue'];
                                @endphp
                                <article class="flex items-center gap-3 border border-slate-200 rounded-xl p-3 bg-white">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-slate-900 truncate">{{ $idea->title }}</div>
                                        <div class="muted truncate">Cập nhật {{ $idea->updated_at->diffForHumans() }}</div>
                                    </div>
                                    <div>
                                        <span class="badge {{ $info['class'] }}">{{ $info['label'] }}</span>
                                    </div>
                                    <div class="cell-right">
                                        <a href="{{ route('my-ideas.show', $idea->id) }}" class="btn-ghost">Chi tiết</a>
                                    </div>
                                </article>
                            @empty
                                <div>Chưa có ý tưởng nào. <a href="{{ route('my-ideas.create') }}" class="text-indigo-600 font-semibold">Tạo ý tưởng</a></div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@endif