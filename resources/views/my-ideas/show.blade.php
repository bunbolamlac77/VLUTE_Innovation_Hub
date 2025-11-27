@extends('layouts.main')

@section('title', $idea->title . ' - Ý tưởng của tôi')

@section('content')
    {{-- Breadcrumb --}}
    <section class="container" style="padding: 24px 0 16px;">
        <nav style="display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 14px;">
            <a href="/" style="color: var(--brand-navy);">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('my-ideas.index') }}" style="color: var(--brand-navy);">Ý tưởng của tôi</a>
            <span>/</span>
            <span>{{ Str::limit($idea->title, 50) }}</span>
        </nav>
    </section>

    {{-- Idea Detail --}}
    <section class="container" style="padding: 16px 0 64px;">
        {{-- Flash messages --}}
        @if (session('status'))
            <div class="my-4 p-3" style="margin-bottom: 16px; background: #ecfdf5; border-left: 4px solid #10b981; border-radius: 8px; color: #065f46;">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="my-4 p-3" style="margin-bottom: 16px; background: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px; color: #991b1b;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Yêu cầu Chỉnh sửa Alert --}}
        @if ($idea->needsChange())
            <div class="my-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700" role="alert"
                style="margin-bottom: 24px; border-radius: 8px;">
                <h3 class="font-bold text-lg" style="margin: 0 0 12px; font-size: 20px; font-weight: 700;">
                    ⚠️ Yêu cầu Chỉnh sửa
                </h3>
                <p class="mb-2" style="margin-bottom: 12px; line-height: 1.6;">
                    Ý tưởng của bạn đã bị trả về với các yêu cầu chỉnh sửa sau. Vui lòng cập nhật và nộp lại.
                </p>

                @php
                    // Lấy change request mới nhất chưa được giải quyết
                    $latestChangeRequest = $idea->changeRequests()->where('is_resolved', false)->latest()->first();
                @endphp

                @if ($latestChangeRequest)
                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-md"
                        style="margin-top: 12px; padding: 16px; background: rgba(254, 242, 242, 0.8); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px;">
                        <p class="text-sm font-semibold"
                            style="margin: 0 0 8px; font-size: 14px; font-weight: 600; color: #991b1b;">
                            Nội dung yêu cầu:
                        </p>
                        <p class="text-gray-800 italic"
                            style="margin: 0; color: #1f2937; font-style: italic; line-height: 1.6; white-space: pre-wrap;">
                            "{{ $latestChangeRequest->request_message }}"
                        </p>
                        @if ($latestChangeRequest->review && $latestChangeRequest->review->assignment && $latestChangeRequest->review->assignment->reviewer)
                            <p class="text-xs text-gray-600 mt-2" style="margin-top: 8px; font-size: 12px; color: #6b7280;">
                                Yêu cầu từ: {{ $latestChangeRequest->review->assignment->reviewer->name }}
                                ({{ $latestChangeRequest->created_at->format('d/m/Y H:i') }})
                            </p>
                        @endif
                    </div>
                @endif

                @if ($idea->isDraft() || $idea->needsChange())
                    <div class="mt-4" style="margin-top: 16px;">
                        <form method="POST" action="{{ route('my-ideas.submit', $idea) }}" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-primary"
                                style="padding: 12px 24px; font-weight: 600; background: var(--brand-navy); color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 16px;">
                                @if ($idea->isDraft())
                                    📤 Nộp để duyệt
                                @else
                                    📤 Nộp lại để duyệt (Sau khi đã sửa)
                                @endif
                            </button>
                        </form>
                        @if ($canEdit)
                            <a href="{{ route('my-ideas.edit', $idea->id) }}" class="btn btn-ghost"
                                style="margin-left: 12px; padding: 12px 24px; font-weight: 600; border: 1px solid var(--border); border-radius: 8px; text-decoration: none; display: inline-block;">
                                ✏️ Chỉnh sửa ngay
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- Status Badge & Actions --}}
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
                        'approved_final' => ['label' => 'Đã duyệt cuối', 'color' => '#10b981'],
                        'rejected' => ['label' => 'Từ chối', 'color' => '#ef4444'],
                    ];
                    $statusInfo = $statusLabels[$idea->status] ?? ['label' => $idea->status, 'color' => '#6b7280'];
                @endphp
                <span class="tag"
                    style="background: {{ $statusInfo['color'] }}15; color: {{ $statusInfo['color'] }}; border-color: {{ $statusInfo['color'] }}30; font-size: 16px; padding: 8px 16px; font-weight: 600;">
                    {{ $statusInfo['label'] }}
                </span>
                @if ($idea->visibility === 'public')
                    <span class="tag" style="background: rgba(10, 168, 79, 0.1); color: var(--brand-green);">
                        Công khai
                    </span>
                @elseif ($idea->visibility === 'team_only')
                    <span class="tag" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        Chỉ nhóm
                    </span>
                @else
                    <span class="tag" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
                        Riêng tư
                    </span>
                @endif
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                @if ($canEdit)
                    <a href="{{ route('my-ideas.edit', $idea->id) }}" class="btn btn-ghost"
                        style="padding: 10px 20px; font-weight: 600;">
                        ✏️ Chỉnh sửa
                    </a>
                @endif
                @if ($canDelete)
                    <form method="POST" action="{{ route('my-ideas.destroy', $idea->id) }}" style="margin: 0;"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa ý tưởng này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost"
                            style="padding: 10px 20px; font-weight: 600; color: #ef4444; border-color: #ef4444;">
                            🗑️ Xóa
                        </button>
                    </form>
                @endif
                @if ($idea->isDraft() || $idea->needsChange())
                    @can('submit', $idea)
                        <form method="POST" action="{{ route('my-ideas.submit', $idea->id) }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-weight: 600;">
                                📤 Nộp ý tưởng
                            </button>
                        </form>
                    @endcan
                @endif
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
            {{-- Main Content --}}
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

                {{-- Mentors --}}
                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-body" style="padding: 24px;">
                        <h3 style="margin: 0 0 20px; font-size: 20px; color: #0f172a; font-weight: 700;">
                            🧑‍🏫 Ban cố vấn / Giảng viên hướng dẫn
                        </h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            @forelse ($idea->members->where('role_in_team', 'mentor') as $member)
                                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: var(--brand-gray-50); border-radius: 8px;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--brand-navy); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        {{ strtoupper(substr($member->user->name, 0, 1)) }}
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: #0f172a;">{{ $member->user->name }}</div>
                                        <div style="font-size: 14px; color: var(--muted);">{{ $member->user->email }}</div>
                                    </div>
                                    <span class="tag" style="background: rgba(7, 26, 82, 0.1); color: var(--brand-navy);">Mentor</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Chưa có cố vấn nào.</p>
                            @endforelse
                        </div>

                        @if (auth()->id() === $idea->owner_id)
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                                <h4 style="margin: 0 0 12px; font-size: 16px; color: #0f172a; font-weight: 600;">
                                    Mời Giảng viên làm Cố vấn
                                </h4>
                                <form method="POST" action="{{ route('my-ideas.invite', $idea->id) }}" style="display: flex; gap: 8px;">
                                    @csrf
                                    <input type="hidden" name="role" value="mentor">
                                    <input type="email" name="email" placeholder="Nhập email giảng viên (@vlute.edu.vn)..." required
                                        style="flex: 1; padding: 10px 16px; border: 1px solid var(--border); border-radius: 8px; font-size: 15px;">
                                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-weight: 600;">
                                        Mời
                                    </button>
                                </form>
                                @error('email')
                                    <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Members --}}
                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-body" style="padding: 24px;">
                        <h3 style="margin: 0 0 20px; font-size: 20px; color: #0f172a; font-weight: 700;">
                            👥 Thành viên nhóm
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
                            @foreach ($idea->members->where('role_in_team', 'member') as $member)
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

                        @if ($canInvite)
                            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border);">
                                <h4 style="margin: 0 0 12px; font-size: 16px; color: #0f172a; font-weight: 600;">
                                    Mời Sinh viên tham gia
                                </h4>
                                <form method="POST" action="{{ route('my-ideas.invite', $idea->id) }}"
                                    style="display: flex; gap: 8px;">
                                    @csrf
                                    <input type="hidden" name="role" value="member">
                                    <input type="email" name="email" placeholder="Nhập email sinh viên..." required
                                        style="flex: 1; padding: 10px 16px; border: 1px solid var(--border); border-radius: 8px; font-size: 15px;">
                                    <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-weight: 600;">
                                        Mời
                                    </button>
                                </form>
                                @error('email')
                                    <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Internal Team Comments (Mentor & Members) --}}
                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-body" style="padding: 24px;">
                        <h3 style="margin: 0 0 10px; font-size: 20px; color: #0f172a; font-weight: 700;">
                            💬 Góp ý nội bộ (chỉ nhóm)
                        </h3>
                        <p style="margin: 0 0 16px; color: #6b7280; font-size: 14px;">Chỉ chủ sở hữu, thành viên và Mentor nhìn thấy phần này.</p>

                        {{-- New comment form --}}
                        <form method="POST" action="{{ route('my-ideas.comments.store', $idea->id) }}" style="margin-bottom: 16px;">
                            @csrf
                            <textarea name="body" rows="3" placeholder="Nhập góp ý cho nhóm..." required
                                style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; resize: vertical;"></textarea>
                            @error('body')
                                <div style="color: #ef4444; font-size: 12px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                            <div style="text-align: right; margin-top: 8px;">
                                <button type="submit" class="btn btn-primary" style="padding: 8px 16px; font-weight: 600;">Gửi góp ý</button>
                            </div>
                        </form>

                        {{-- Comments list --}}
                        @php
                            $comments = $idea->comments->where('visibility', 'team_only')->sortByDesc('created_at');
                        @endphp
                        @if ($comments->count() > 0)
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                @foreach ($comments as $c)
                                    <div style="padding: 12px; background: var(--brand-gray-50); border-radius: 8px;">
                                        <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                            <div style="font-weight: 600; color:#0f172a;">
                                                {{ $c->user->name }}
                                                <span style="color:#6b7280; font-weight: 400; font-size: 12px;">• {{ $c->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if (auth()->id() === $c->user_id || auth()->id() === $idea->owner_id)
                                                <form method="POST" action="{{ route('my-ideas.comments.destroy', [$idea->id, $c->id]) }}" onsubmit="return confirm('Xóa góp ý này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-ghost" style="font-size: 12px;">Xóa</button>
                                                </form>
                                            @endif
                                        </div>
                                        <div style="color:#374151; white-space: pre-wrap;">{{ $c->body }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="padding: 12px; background: var(--brand-gray-50); border-radius: 8px; color:#6b7280; font-size: 14px;">
                                Chưa có góp ý nào. Hãy là người đầu tiên để lại góp ý!
                            </div>
                        @endif
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
                                                    @if ($attachment->uploader)
                                                        • Upload bởi: {{ $attachment->uploader->name }}
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

                {{-- Pending Invitations --}}
                @if ($idea->invitations->where('status', 'pending')->count() > 0)
                    <div class="card" style="margin-bottom: 24px;">
                        <div class="card-body" style="padding: 24px;">
                            <h3 style="margin: 0 0 20px; font-size: 20px; color: #0f172a; font-weight: 700;">
                                Lời mời đang chờ ({{ $idea->invitations->where('status', 'pending')->count() }})
                            </h3>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                @foreach ($idea->invitations->where('status', 'pending') as $invitation)
                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: var(--brand-gray-50); border-radius: 8px;">
                                        <div>
                                            <div style="font-weight: 600; color: #0f172a;">{{ $invitation->email }}</div>
                                            <div style="font-size: 12px; color: var(--muted);">
                                                Vai trò: {{ $invitation->role === 'mentor' ? 'Mentor' : 'Thành viên' }} · Đã gửi: {{ $invitation->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                        <span class="tag" style="background: rgba(251, 191, 36, 0.1); color: #f59e0b;">
                                            Đang chờ
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Reviews & Comments --}}
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

            {{-- Sidebar --}}
            <aside>
                <div class="card" style="position: sticky; top: 100px; margin-bottom: 24px;">
                    <div class="card-body" style="padding: 24px;">
                        <h3 style="margin: 0 0 20px; font-size: 18px; color: #0f172a; font-weight: 700;">
                            Thông tin
                        </h3>

                        <div style="margin-bottom: 20px;">
                            <h4 style="margin: 0 0 8px; font-size: 14px; color: var(--muted); font-weight: 600;">
                                Người tạo
                            </h4>
                            <div style="color: #0f172a; font-weight: 600;">
                                {{ $idea->owner->name }}
                            </div>
                            <div style="font-size: 14px; color: var(--muted);">
                                {{ $idea->owner->email }}
                            </div>
                        </div>

                        <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
                            <h4 style="margin: 0 0 8px; font-size: 14px; color: var(--muted); font-weight: 600;">
                                Ngày tạo
                            </h4>
                            <div style="color: #0f172a;">
                                {{ $idea->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
                            <h4 style="margin: 0 0 8px; font-size: 14px; color: var(--muted); font-weight: 600;">
                                Cập nhật lần cuối
                            </h4>
                            <div style="color: #0f172a;">
                                {{ $idea->updated_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        @if ($idea->faculty || $idea->category)
                            <div>
                                @if ($idea->faculty)
                                    <div style="margin-bottom: 12px;">
                                        <h4 style="margin: 0 0 8px; font-size: 14px; color: var(--muted); font-weight: 600;">
                                            Khoa
                                        </h4>
                                        <div style="color: #0f172a;">{{ $idea->faculty->name }}</div>
                                    </div>
                                @endif
                                @if ($idea->category)
                                    <div>
                                        <h4 style="margin: 0 0 8px; font-size: 14px; color: var(--muted); font-weight: 600;">
                                            Danh mục
                                        </h4>
                                        <div style="color: #0f172a;">{{ $idea->category->name }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Đánh dấu menu active nếu có
            const myIdeasLink = document.querySelector('a[href="/ideas/my"]');
            if (myIdeasLink) {
                myIdeasLink.classList.add('active');
            }
        });
    </script>
@endpush