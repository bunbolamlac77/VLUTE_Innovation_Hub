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
        $isReviewer = $user && ($user->hasRole('staff') || $user->hasRole('center') || $user->hasRole('board') || $user->hasRole('reviewer'));
        $reviewQueue = collect();
        if ($isReviewer) {
            $reviewQueue = \App\Models\Idea::whereIn('status', [
                'submitted_gv',
                'needs_change_gv',
                'submitted_center',
                'needs_change_center',
                'submitted_board',
                'needs_change_board',
            ])->with(['owner', 'faculty', 'category'])->orderBy('updated_at', 'asc')->limit(10)->get();
        }
        $myDrafts = collect();
        if ($user && $user->hasRole('student')) {
            $myDrafts = \App\Models\Idea::where('owner_id', $user->id)->whereIn('status', [
                'draft',
                'needs_change_gv',
                'needs_change_center',
                'needs_change_board',
                'submitted_gv',
                'submitted_center',
                'submitted_board',
                'approved_final',
            ])->latest()->limit(6)->get();
        }
    @endphp

    <section style="padding: 48px 0;">
        <div class="container" style="display: grid; grid-template-columns: 1fr; gap: 32px;">
            <style>
                .dash-table {
                    width: 100%;
                    border-collapse: separate;
                    border-spacing: 0;
                }

                .dash-table thead th {
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: .08em;
                    color: #7c8596;
                    background: linear-gradient(180deg, #fafbff, #f4f6fb);
                    padding: 13px 16px;
                    border-bottom: 1px solid #e6eaf0;
                }

                .dash-table thead th:first-child {
                    border-top-left-radius: 12px;
                }

                .dash-table thead th:last-child {
                    border-top-right-radius: 12px;
                }

                .dash-table tbody td {
                    padding: 16px 16px;
                    border-bottom: 1px solid #eef2f7;
                    vertical-align: middle;
                }

                .dash-table tbody tr:hover {
                    background: #f8fbff;
                    box-shadow: inset 0 1px 0 #eef2f7;
                }

                .dash-table thead th:nth-child(1),
                .dash-table tbody td:nth-child(1) {
                    text-align: left;
                    width: 32%;
                }

                .dash-table thead th:nth-child(2),
                .dash-table tbody td:nth-child(2) {
                    text-align: left;
                    width: 18%;
                }

                .dash-table thead th:nth-child(3),
                .dash-table tbody td:nth-child(3) {
                    text-align: left;
                    width: 20%;
                }

                .dash-table thead th:nth-child(4),
                .dash-table tbody td:nth-child(4) {
                    text-align: center;
                    width: 15%;
                }

                .dash-table thead th:nth-child(5),
                .dash-table tbody td:nth-child(5) {
                    text-align: center;
                    width: 10%;
                    white-space: nowrap;
                }

                .dash-table thead th:nth-child(6),
                .dash-table tbody td:nth-child(6) {
                    text-align: right;
                    width: 5%;
                }

                .dash-card {
                    background: #fff;
                    border: 1px solid #e6eaf0;
                    border-radius: 18px;
                    box-shadow: 0 12px 30px rgba(10, 21, 50, 0.06);
                    overflow: hidden;
                }

                .dash-card .card-body {
                    padding: 28px 28px;
                }

                .dash-title {
                    margin: 0;
                    font-size: 22px;
                    font-weight: 800;
                    color: #0f172a;
                    letter-spacing: -0.01em;
                }

                .dash-subtitle {
                    margin-top: 4px;
                    color: #64748b;
                    font-size: 13px;
                }

                .dash-actions .btn {
                    border-radius: 12px;
                    padding: 10px 18px;
                    font-weight: 800;
                    box-shadow: 0 6px 16px rgba(30, 64, 175, 0.18);
                }

                .dash-table {
                    width: 100%;
                    border-collapse: separate;
                    border-spacing: 0;
                }

                .dash-table thead th {
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: .08em;
                    color: #7c8596;
                    background: linear-gradient(180deg, #fafbff, #f4f6fb);
                    padding: 13px 16px;
                    border-bottom: 1px solid #e6eaf0;
                }

                .dash-table thead th:first-child {
                    border-top-left-radius: 12px;
                }

                .dash-table thead th:last-child {
                    border-top-right-radius: 12px;
                }

                .dash-table tbody td {
                    padding: 16px 16px;
                    border-bottom: 1px solid #eef2f7;
                    vertical-align: middle;
                }

                .dash-table tbody tr:hover {
                    background: #f8fbff;
                    box-shadow: inset 0 1px 0 #eef2f7;
                }

                .badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    border-radius: 999px;
                    padding: 7px 12px;
                    font-size: 12px;
                    font-weight: 800;
                    border: 1px solid rgba(0, 0, 0, 0.06);
                }

                .badge-blue {
                    background: rgba(59, 130, 246, .12);
                    color: #1d4ed8;
                    border-color: rgba(59, 130, 246, .25);
                }

                .badge-amber {
                    background: rgba(245, 158, 11, .12);
                    color: #b45309;
                    border-color: rgba(245, 158, 11, .25);
                }

                .badge-green {
                    background: rgba(16, 185, 129, .12);
                    color: #047857;
                    border-color: rgba(16, 185, 129, .25);
                }

                .muted {
                    color: #6b7280;
                    font-size: 12px;
                }

                .btn-ghost {
                    border: 1px solid #e6eaf0;
                    border-radius: 12px;
                    padding: 9px 14px;
                    font-weight: 800;
                    color: #0f172a;
                }

                .btn-ghost:hover {
                    background: #f1f5fb;
                }

                .cell-right {
                    text-align: right;
                }

                /* Ensure the review action renders as a button here */
                .btn-review {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    padding: 9px 14px;
                    border-radius: 12px;
                    background: #2563eb;
                    color: #fff !important;
                    font-weight: 800;
                    text-decoration: none;
                    border: 1px solid #1e40af12;
                    box-shadow: 0 6px 16px rgba(30, 64, 175, 0.18);
                }

                .btn-review:hover {
                    background: #1d4ed8;
                }
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
                                        <th>Cập nhật</th>
                                        <th></th>
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
                                                        'submitted_gv' => ['label' => 'Đã nộp (GV)', 'class' => 'badge-blue'],
                                                        'needs_change_gv' => ['label' => 'Cần chỉnh sửa (GV)', 'class' => 'badge-amber'],
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
                        <div class="table-responsive" style="border: 1px solid #eef2f7; border-radius: 14px; overflow: hidden;">
                            <table class="dash-table">
                                <thead>
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Trạng thái</th>
                                        <th>Cập nhật</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($myDrafts as $idea)
                                        <tr>
                                            <td style="font-weight: 700; color:#0f172a;">{{ $idea->title }}</td>
                                            <td>
                                                @php
                                                    $map = [
                                                        'draft' => ['label' => 'Nháp', 'class' => 'badge-amber'],
                                                        'needs_change_gv' => ['label' => 'Cần chỉnh sửa (GV)', 'class' => 'badge-amber'],
                                                        'needs_change_center' => ['label' => 'Cần chỉnh sửa (TTĐMST)', 'class' => 'badge-amber'],
                                                        'needs_change_board' => ['label' => 'Cần chỉnh sửa (BGH)', 'class' => 'badge-amber'],
                                                        'submitted_gv' => ['label' => 'Đã nộp (GV)', 'class' => 'badge-blue'],
                                                        'submitted_center' => ['label' => 'Đã nộp (TTĐMST)', 'class' => 'badge-blue'],
                                                        'submitted_board' => ['label' => 'Đã nộp (BGH)', 'class' => 'badge-blue'],
                                                        'approved_final' => ['label' => 'Đã duyệt (BGH)', 'class' => 'badge-green'],
                                                    ];
                                                    $info = $map[$idea->status] ?? ['label' => $idea->status, 'class' => 'badge-blue'];
                                                @endphp
                                                <span class="badge {{ $info['class'] }}">{{ $info['label'] }}</span>
                                            </td>
                                            <td>{{ $idea->updated_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('my-ideas.show', $idea->id) }}" class="btn-ghost">Chi tiết</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Chưa có ý tưởng nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@endif