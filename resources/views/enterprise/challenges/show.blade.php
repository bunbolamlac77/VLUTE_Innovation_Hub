@extends('layouts.main')

@section('title', 'Chi tiết Thách thức - ' . ($challenge->title ?? ''))

@section('content')
<section class="container" style="padding: 32px 0;">
    {{-- Flash messages --}}
    @if (session('status'))
        <div class="my-4 p-3" style="background:#ecfdf5; border-left:4px solid #10b981; border-radius:8px; color:#065f46;">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="my-4 p-3" style="background:#fef2f2; border-left:4px solid #ef4444; border-radius:8px; color:#991b1b;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display:flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 16px; flex-wrap: wrap;">
        <div>
            <h1 style="margin:0; font-size: 24px; font-weight: 800; color:#0f172a;">{{ $challenge->title }}</h1>
            <div class="muted">Quản lý bài nộp của sinh viên cho thách thức này</div>
        </div>
        <div style="display:flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('enterprise.challenges.index') }}" class="btn btn-ghost">← Quay lại danh sách</a>
            @if ($challenge->status !== 'closed')
                <form method="POST" action="{{ route('enterprise.challenges.close', $challenge->id) }}" onsubmit="return confirm('Đóng challenge này? Sinh viên sẽ không thể nộp thêm.');">
                    @csrf
                    <button type="submit" class="btn btn-ghost" style="border-color:#ef4444; color:#ef4444;">Đóng challenge</button>
                </form>
            @endif
            @if ($challenge->status !== 'open')
                <form method="POST" action="{{ route('enterprise.challenges.reopen', $challenge->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Mở lại challenge</button>
                </form>
            @endif
        </div>
    </div>

    <div class="card" style="margin-bottom: 16px;">
        <div class="card-body">
            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div><strong>Hạn chót:</strong> {{ $challenge->deadline ? $challenge->deadline->format('d/m/Y H:i') : 'Chưa đặt' }}</div>
                <div><strong>Trạng thái:</strong>
                    @php
                        $badgeClass = $challenge->status === 'open' ? 'badge-green' : ($challenge->status === 'closed' ? 'badge-amber' : 'badge-blue');
                    @endphp
                    <span class="badge {{ $badgeClass }}" style="text-transform: uppercase;">{{ $challenge->status }}</span>
                </div>
                <div><strong>Phần thưởng:</strong> <span style="font-weight:700; color:#047857;">{{ $challenge->reward ?? '—' }}</span></div>
            </div>
            <div style="margin-top:12px; color:#374151; white-space: pre-wrap;">{{ $challenge->description }}</div>
        </div>
    </div>

    <h3 style="margin: 16px 0; font-size: 18px; font-weight: 800; color:#0f172a;">Danh sách giải pháp từ Sinh viên</h3>

    <div class="grid" style="display:grid; gap: 12px;">
        @forelse ($challenge->submissions as $sub)
            <div class="card">
                <div class="card-body" style="display:flex; flex-direction: column; gap: 16px;">
                    <div style="display:flex; justify-content: space-between; gap: 16px; align-items:flex-start;">
                        <div style="flex:1; min-width:0;">
                            <div style="font-size: 18px; font-weight: 800; color:#1e3a8a;">{{ $sub->title }}</div>
                            <div class="muted" style="margin-top: 2px;">Tác giả: {{ $sub->user->name ?? 'N/A' }} • {{ $sub->created_at?->format('d/m/Y H:i') }}</div>
                            @if ($sub->solution_description)
                                <div style="margin-top: 8px; color:#374151; white-space: pre-wrap;">{{ $sub->solution_description }}</div>
                            @endif
                        </div>
                        <div style="min-width: 260px;">
                            @forelse ($sub->attachments as $file)
                                <a href="{{ route('attachments.download', $file->id) }}" class="btn btn-ghost" style="display:block; width:100%; text-align:left; margin-bottom:6px;">
                                    📎 {{ $file->filename }}
                                </a>
                            @empty
                                <span class="muted">Không có tệp đính kèm</span>
                            @endforelse
                        </div>
                    </div>

                    {{-- Khối chấm điểm/nhận xét --}}
                    <div style="border-top:1px solid #eef2f7; padding-top: 12px;">
                        <form method="POST" action="{{ route('enterprise.challenges.submissions.review', [$challenge->id, $sub->id]) }}">
                            @csrf
                            <div style="display:grid; grid-template-columns: 140px 1fr; gap: 12px; align-items: start;">
                                <div>
                                    <label for="score_{{ $sub->id }}" class="form-label">Điểm (0-100)</label>
                                    <input id="score_{{ $sub->id }}" name="score" type="number" min="0" max="100" class="form-input" value="{{ old('score', $sub->score) }}" placeholder="Ví dụ: 85">
                                </div>
                                <div>
                                    <label for="feedback_{{ $sub->id }}" class="form-label">Nhận xét</label>
                                    <textarea id="feedback_{{ $sub->id }}" name="feedback" rows="3" class="form-textarea" placeholder="Nhận xét chi tiết cho bài nộp...">{{ old('feedback', $sub->feedback) }}</textarea>
                                </div>
                            </div>
                            <div style="display:flex; justify-content: space-between; align-items:center; gap: 8px; margin-top: 12px;">
                                <div class="muted">
                                    @if ($sub->reviewed_at)
                                        Đã đánh giá bởi {{ $sub->reviewer->name ?? 'N/A' }} • {{ $sub->reviewed_at->format('d/m/Y H:i') }}
                                    @else
                                        Chưa đánh giá
                                    @endif
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">Lưu đánh giá</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body muted">Chưa có bài nộp nào.</div>
            </div>
        @endforelse
    </div>
</section>
@endsection
