@extends('layouts.main')

@section('title', 'Phản biện: ' . $idea->title)

@section('content')
    {{-- Breadcrumb --}}
    <section class="container" style="padding: 24px 0 16px;">
        <nav style="display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 14px;">
            <a href="/" style="color: var(--brand-navy);">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('manage.review-queue.index') }}" style="color: var(--brand-navy);">Hàng chờ phản biện</a>
            <span>/</span>
            <span>{{ Str::limit($idea->title, 50) }}</span>
        </nav>
    </section>

    {{-- Review Form --}}
    <section class="container" style="padding: 16px 0 64px;">
        {{-- Status Badge --}}
        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                @php
                    $statusLabels = [
                        'draft' => ['label' => 'Nháp', 'color' => '#6b7280'],
                        
                        'submitted_center' => ['label' => 'Đã nộp (TTĐMST)', 'color' => '#3b82f6'],
                        'needs_change_center' => ['label' => 'Cần chỉnh sửa (TTĐMST)', 'color' => '#f59e0b'],
                        'approved_center' => ['label' => 'Đã duyệt (TTĐMST)', 'color' => '#10b981'],
                        'submitted_board' => ['label' => 'Đã nộp (BGH)', 'color' => '#3b82f6'],
                        'needs_change_board' => ['label' => 'Cần chỉnh sửa (BGH)', 'color' => '#f59e0b'],
                        'approved_final' => ['label' => 'Đã duyệt (BGH)', 'color' => '#10b981'],
                        'rejected' => ['label' => 'Từ chối', 'color' => '#ef4444'],
                    ];
                    $statusInfo = $statusLabels[$idea->status] ?? ['label' => $idea->status, 'color' => '#6b7280'];
                @endphp
                <span class="tag"
                    style="background: {{ $statusInfo['color'] }}15; color: {{ $statusInfo['color'] }}; border-color: {{ $statusInfo['color'] }}30; font-size: 16px; padding: 8px 16px; font-weight: 600;">
                    {{ $statusInfo['label'] }}
                </span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
            {{-- Main Content (70%) --}}
            <div>
                {{-- Idea Info --}}
                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-body" style="padding: 32px;">
                        <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                            @if ($idea->faculty)
                                <span class="tag">{{ $idea->faculty->name }}</span>
                            @endif
                            @if ($idea->category)
                                <span class="tag" style="background: rgba(10, 168, 79, 0.1); color: var(--brand-green);">
                                    {{ $idea->category->name }}
                                </span>
                            @endif
                        </div>

                        <h1 style="margin: 0 0 16px; font-size: 32px; line-height: 1.3; color: #0f172a;">
                            {{ $idea->title }}
                        </h1>

                        <p class="mt-1 text-sm text-gray-600"
                            style="margin-top: 8px; margin-bottom: 24px; font-size: 14px; color: #6b7280;">
                            Tác giả: <strong>{{ $idea->owner->name }}</strong> ({{ $idea->owner->email }})
                        </p>

                        @if ($idea->description)
                            <div style="margin-bottom: 24px;">
                                <h3 style="margin: 0 0 12px; font-size: 18px; color: #0f172a; font-weight: 700;">
                                    Mô tả ý tưởng
                                </h3>
                                <div style="color: #374151; line-height: 1.8; white-space: pre-wrap;">
                                    {{ $idea->description }}
                                </div>
                            </div>
                        @endif

                        @if ($idea->content)
                            <div>
                                <h3 style="margin: 0 0 12px; font-size: 18px; color: #0f172a; font-weight: 700;">
                                    Nội dung chi tiết
                                </h3>
                                <div style="color: #374151; line-height: 1.8; white-space: pre-wrap;">
                                    {!! nl2br(e($idea->content)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Members --}}
                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-body" style="padding: 24px;">
                        <h3 style="margin: 0 0 20px; font-size: 20px; color: #0f172a; font-weight: 700;">
                            Thành viên nhóm
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div
                                style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--brand-gray-50); border-radius: 8px;">
                                <div
                                    style="width: 40px; height: 40px; border-radius: 50%; background: var(--brand-navy); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    {{ strtoupper(substr($idea->owner->name, 0, 1)) }}
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #0f172a;">{{ $idea->owner->name }}</div>
                                    <div style="font-size: 14px; color: var(--muted);">{{ $idea->owner->email }}</div>
                                </div>
                                <span class="tag" style="background: rgba(7, 26, 82, 0.1); color: var(--brand-navy);">
                                    Người tạo
                                </span>
                            </div>
                            @foreach ($idea->members as $member)
                                @if ($member->user)
                                    <div
                                        style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--brand-gray-50); border-radius: 8px;">
                                        <div
                                            style="width: 40px; height: 40px; border-radius: 50%; background: var(--brand-green); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                            {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                        </div>
                                        <div style="flex: 1;">
                                            <div style="font-weight: 600; color: #0f172a;">{{ $member->user->name }}</div>
                                            <div style="font-size: 14px; color: var(--muted);">{{ $member->user->email }}</div>
                                        </div>
                                        <span class="tag" style="background: rgba(10, 168, 79, 0.1); color: var(--brand-green);">
                                            Thành viên
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Attachments --}}
                @if ($idea->attachments && $idea->attachments->count() > 0)
                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-body" style="padding: 24px;">
                            <h3 style="margin: 0 0 20px; font-size: 20px; color: #0f172a; font-weight: 700;">
                                File đính kèm ({{ $idea->attachments->count() }})
                            </h3>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                @foreach ($idea->attachments as $attachment)
                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: var(--brand-gray-50); border-radius: 8px;">
                                        <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                                            @php
                                                $fileIcon = '📄';
                                                if (str_contains($attachment->mime_type ?? '', 'image')) {
                                                    $fileIcon = '🖼️';
                                                } elseif (str_contains($attachment->mime_type ?? '', 'pdf')) {
                                                    $fileIcon = '📕';
                                                } elseif (str_contains($attachment->mime_type ?? '', 'word') || str_contains($attachment->mime_type ?? '', 'document')) {
                                                    $fileIcon = '📘';
                                                } elseif (str_contains($attachment->mime_type ?? '', 'zip') || str_contains($attachment->mime_type ?? '', 'archive')) {
                                                    $fileIcon = '📦';
                                                }
                                            @endphp
                                            <span style="font-size: 24px;">{{ $fileIcon }}</span>
                                            <div style="flex: 1;">
                                                <div style="font-weight: 600; color: #0f172a; font-size: 14px;">
                                                    {{ $attachment->filename }}
                                                </div>
                                                <div style="font-size: 12px; color: var(--muted);">
                                                    @if ($attachment->mime_type)
                                                        {{ $attachment->mime_type }} •
                                                    @endif
                                                    @if ($attachment->size)
                                                        {{ number_format($attachment->size / 1024, 2) }} KB
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('attachments.download', $attachment->id) }}" class="btn btn-primary"
                                            style="padding: 8px 16px; font-size: 14px; font-weight: 600;" target="_blank">
                                            📥 Tải xuống
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Previous Reviews --}}
                @if ($idea->reviewAssignments->count() > 0)
                    <div class="card">
                        <div class="card-body" style="padding: 24px;">
                            <h3 style="margin: 0 0 20px; font-size: 20px; color: #0f172a; font-weight: 700;">
                                Lịch sử duyệt và nhận xét
                            </h3>
                            <div style="display: flex; flex-direction: column; gap: 20px;">
                                @foreach ($idea->reviewAssignments as $assignment)
                                    <div style="padding: 16px; background: var(--brand-gray-50); border-radius: 8px;">
                                        <div
                                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                            <div>
                                                <div style="font-weight: 600; color: #0f172a;">
                                                    {{ $assignment->reviewer->name ?? 'Chưa được gán' }}
                                                </div>
                                                <div style="font-size: 12px; color: var(--muted);">
                                                    Cấp duyệt: {{ $assignment->review_level }}
                                                    @if ($assignment->review)
                                                        · {{ $assignment->review->created_at->format('d/m/Y H:i') }}
                                                    @endif
                                                </div>
                                            </div>
                                            @if ($assignment->review)
                                                @php
                                                    $decisionColors = [
                                                        'approved' => ['bg' => 'rgba(16, 185, 129, 0.1)', 'color' => '#10b981', 'label' => 'Đã duyệt'],
                                                        'needs_change' => ['bg' => 'rgba(245, 158, 11, 0.1)', 'color' => '#f59e0b', 'label' => 'Cần chỉnh sửa'],
                                                        'rejected' => ['bg' => 'rgba(239, 68, 68, 0.1)', 'color' => '#ef4444', 'label' => 'Từ chối'],
                                                    ];
                                                    $decisionInfo = $decisionColors[$assignment->review->decision] ?? ['bg' => 'rgba(107, 114, 128, 0.1)', 'color' => '#6b7280', 'label' => $assignment->review->decision];
                                                @endphp
                                                <span class="tag"
                                                    style="background: {{ $decisionInfo['bg'] }}; color: {{ $decisionInfo['color'] }};">
                                                    {{ $decisionInfo['label'] }}
                                                </span>
                                            @else
                                                <span class="tag" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
                                                    Đang chờ
                                                </span>
                                            @endif
                                        </div>
                                        @if ($assignment->review && $assignment->review->overall_comment)
                                            <div
                                                style="margin-top: 12px; padding: 12px; background: #fff; border-radius: 6px; border-left: 3px solid var(--brand-navy);">
                                                <div style="font-size: 14px; color: #374151; line-height: 1.6; white-space: pre-wrap;">
                                                    {{ $assignment->review->overall_comment }}
                                                </div>
                                            </div>
                                        @endif
                                        @if ($assignment->review && $assignment->review->changeRequests && $assignment->review->changeRequests->count() > 0)
                                            <div style="margin-top: 12px;">
                                                <div style="font-size: 14px; font-weight: 600; color: #0f172a; margin-bottom: 8px;">
                                                    Yêu cầu chỉnh sửa:
                                                </div>
                                                @foreach ($assignment->review->changeRequests as $changeRequest)
                                                    <div
                                                        style="padding: 10px; background: #fff; border-radius: 6px; margin-bottom: 8px; border-left: 3px solid #f59e0b;">
                                                        <div style="font-size: 14px; color: #374151; line-height: 1.6;">
                                                            {{ $changeRequest->request_message }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Review Form Sidebar (30%) --}}
            <aside>
                <div class="card" style="position: sticky; top: 100px;">
                    <div class="card-body" style="padding: 24px;">
                        <h3 style="margin: 0 0 20px; font-size: 18px; color: #0f172a; font-weight: 700;">
                            Đánh giá & Phản biện
                        </h3>

                        <form method="POST" action="{{ route('manage.review.store', $idea) }}">
                            @csrf

                            <div style="margin-bottom: 20px;">
                                <label for="comment"
                                    style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #0f172a;">
                                    Nhận xét chung
                                </label>
                                <textarea id="comment" name="comment" rows="5"
                                    style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"
                                    placeholder="Nhập nhận xét của bạn về ý tưởng này..."></textarea>
                                @error('comment')
                                    <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 24px;">
                                <button type="submit" name="action" value="approve" class="btn btn-primary"
                                    style="width: 100%; padding: 12px 20px; font-weight: 600; background: #10b981; border-color: #10b981; justify-content: center;"
                                    onclick="return confirm('Bạn có chắc chắn muốn duyệt ý tưởng này?');">
                                    ✓ Duyệt (Chuyển lên cấp Trung tâm)
                                </button>

                                <button type="submit" name="action" value="request_changes" class="btn btn-ghost"
                                    style="width: 100%; padding: 12px 20px; font-weight: 600; border-color: #f59e0b; color: #f59e0b; justify-content: center;"
                                    onclick="return confirm('Bạn có chắc chắn muốn yêu cầu chỉnh sửa?');">
                                    ✏️ Yêu cầu Chỉnh sửa
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Form validation nếu cần
        });
    </script>
@endpush