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

            @if($challenge->image)
                <div style="margin-top:12px;">
                    <img src="{{ asset('storage/' . $challenge->image) }}" alt="Ảnh bìa" style="width:100%; height:220px; object-fit:cover; border-radius:12px; border:1px solid #eef2f7;" />
                </div>
            @endif

            <div style="margin-top:12px; color:#374151;">
                @if($challenge->problem_statement)
                    <h3 style="margin:8px 0 4px; font-weight:800;">Bối cảnh & vấn đề</h3>
                    {!! $challenge->problem_statement !!}
                @endif
                @if($challenge->requirements)
                    <h3 style="margin:12px 0 4px; font-weight:800;">Yêu cầu & phạm vi</h3>
                    {!! $challenge->requirements !!}
                @endif
                @if(!$challenge->problem_statement && !$challenge->requirements)
                    <div style="white-space: pre-wrap;">{{ $challenge->description }}</div>
                @endif
            </div>

            <div style="margin-top:12px;">
                <h4 style="margin:0 0 8px; font-weight:800;">Tệp đính kèm (đề bài/dữ liệu)</h4>
                @if($challenge->attachments->isNotEmpty())
                    <div style="display:grid; gap:6px;">
                        @foreach($challenge->attachments as $file)
                            <a href="{{ route('attachments.download', $file->id) }}" class="btn btn-ghost" style="justify-content:flex-start;">📎 {{ $file->filename }}</a>
                        @endforeach
                    </div>
                @else
                    <div class="muted">Chưa có tệp đính kèm.</div>
                @endif
            </div>
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

                            <div style="margin-top:8px; display:grid; grid-template-columns: 1fr 1fr; gap:8px; font-size: 13px;">
                                @if($sub->full_name)
                                    <div><strong>Họ tên:</strong> {{ $sub->full_name }}</div>
                                @endif
                                @if($sub->phone)
                                    <div><strong>Điện thoại:</strong> {{ $sub->phone }}</div>
                                @endif
                                @if($sub->address)
                                    <div class="md:col-span-2"><strong>Địa chỉ:</strong> {{ $sub->address }}</div>
                                @endif
                                @if($sub->class_name)
                                    <div><strong>Lớp:</strong> {{ $sub->class_name }}</div>
                                @endif
                                @if($sub->school_name)
                                    <div><strong>Trường:</strong> {{ $sub->school_name }}</div>
                                @endif
                                @if($sub->mentor_name)
                                    <div class="md:col-span-2"><strong>GV hướng dẫn:</strong> {{ $sub->mentor_name }}</div>
                                @endif
                                @if($sub->team_members)
                                    <div class="md:col-span-2"><strong>Thành viên nhóm:</strong> {{ $sub->team_members }}</div>
                                @endif
                            </div>

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
