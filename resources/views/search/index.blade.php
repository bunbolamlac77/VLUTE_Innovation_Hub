@extends('layouts.main')

@section('title', 'Tìm kiếm - VLUTE Innovation Hub')

@section('content')
<section class="container" style="padding: 28px 0 16px;">
    <h1 class="section-title" style="margin-bottom: 12px;">Tìm kiếm</h1>
    <p style="color: var(--muted); margin: 0 0 16px;">Tìm ý tưởng, cuộc thi, mentor một cách nhanh chóng.</p>

    <form method="GET" action="{{ route('search.index') }}" id="searchForm" style="display: grid; grid-template-columns: 1.6fr 1fr 1fr 1fr; gap: 12px; align-items: end;">
        <div>
            <label for="q" style="display:block; font-weight:600; margin-bottom:8px;">Từ khóa</label>
            <div style="display:flex; gap:8px;">
                <input type="search" id="q" name="q" value="{{ $q }}" placeholder="Tìm ý tưởng, cuộc thi, mentor…" style="flex:1; padding: 12px 16px; border: 1px solid var(--border); border-radius: 999px; font-size:15px;" />
                <button type="submit" class="btn btn-primary" style="padding: 12px 18px; border-radius: 999px; font-weight: 800;">🔍 Tìm</button>
            </div>
        </div>
        <div>
            <label for="type" style="display:block; font-weight:600; margin-bottom:8px;">Loại</label>
            <select id="type" name="type" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background:#fff;">
                <option value="all" {{ $type==='all' ? 'selected' : '' }}>Tất cả</option>
                <option value="ideas" {{ $type==='ideas' ? 'selected' : '' }}>Ý tưởng</option>
                <option value="competitions" {{ $type==='competitions' ? 'selected' : '' }}>Cuộc thi</option>
                <option value="mentors" {{ $type==='mentors' ? 'selected' : '' }}>Mentor</option>
            </select>
        </div>
        <div>
            <label for="faculty" style="display:block; font-weight:600; margin-bottom:8px;">Khoa</label>
            <select id="faculty" name="faculty" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background:#fff;">
                <option value="">Tất cả khoa</option>
                @foreach ($faculties as $f)
                    <option value="{{ $f->id }}" {{ (string)$facultyId === (string)$f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="category" style="display:block; font-weight:600; margin-bottom:8px;">Lĩnh vực (Ý tưởng)</label>
            <select id="category" name="category" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; background:#fff;">
                <option value="">Tất cả lĩnh vực</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" {{ (string)$categoryId === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </form>
</section>

<section class="container" style="padding: 8px 0 64px;">
    @if ($type === 'all' || $type === 'ideas')
        <div style="margin-bottom: 28px;">
            <div class="section-header">
                <h2 class="section-title" style="margin:0;">Ý tưởng</h2>
                @if ($type === 'all')
                    <a class="muted-link" href="{{ route('search.index', array_merge(request()->query(), ['type'=>'ideas'])) }}">Xem tất cả →</a>
                @endif
            </div>
            @if ($ideas && $ideas->count())
                <div class="grid-4">
                    @foreach ($ideas as $idea)
                        <article class="item" style="overflow:hidden;">
                            <a href="{{ route('ideas.show', $idea->slug) }}" style="display:block; text-decoration:none; color:inherit;">
                                <div class="thumb" style="height: 140px;"></div>
                                <div class="meta">
                                    <div class="row">
                                        <span class="tag">{{ $idea->faculty->name ?? 'Chưa phân khoa' }}</span>
                                        <span class="tag" style="background: rgba(10,168,79,.1); color: var(--brand-green);">{{ $idea->category->name ?? '—' }}</span>
                                    </div>
                                    <h5>{{ $idea->title }}</h5>
                                    @if ($idea->summary)
                                        <p style="color:#6b7280; font-size: 14px; margin: 6px 0 0; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">{{ $idea->summary }}</p>
                                    @endif
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
                @if ($ideas instanceof \Illuminate\Contracts\Pagination\Paginator || $ideas instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                    <div style="margin-top: 16px; display:flex; justify-content:center;">{{ $ideas->links() }}</div>
                @endif
            @else
                <div class="card">Không có ý tưởng phù hợp.</div>
            @endif
        </div>
    @endif

    @if ($type === 'all' || $type === 'competitions')
        <div style="margin-bottom: 28px;">
            <div class="section-header">
                <h2 class="section-title" style="margin:0;">Cuộc thi & Sự kiện</h2>
                @if ($type === 'all')
                    <a class="muted-link" href="{{ route('search.index', array_merge(request()->query(), ['type'=>'competitions'])) }}">Xem tất cả →</a>
                @endif
            </div>
            @if ($competitions && $competitions->count())
                <div class="grid-4">
                    @foreach ($competitions as $comp)
                        <article class="item">
                            <a href="{{ route('competitions.show', $comp->slug) }}" style="display:block; text-decoration:none; color:inherit;">
                                <div class="thumb" style="height: 120px; background: linear-gradient(135deg,#fde68a,#93c5fd);"></div>
                                <div class="meta">
                                    <h5>{{ $comp->title }}</h5>
                                    @if ($comp->start_date || $comp->end_date)
                                        <div class="row" style="font-size: 13px; color: var(--muted);">
                                            <span>Bắt đầu: {{ optional($comp->start_date)->format('d/m/Y') }}</span>
                                            <span>Kết thúc: {{ optional($comp->end_date)->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
                @if ($competitions instanceof \Illuminate\Contracts\Pagination\Paginator || $competitions instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                    <div style="margin-top: 16px; display:flex; justify-content:center;">{{ $competitions->links() }}</div>
                @endif
            @else
                <div class="card">Không có cuộc thi phù hợp.</div>
            @endif
        </div>
    @endif

    @if ($type === 'all' || $type === 'mentors')
        <div style="margin-bottom: 28px;">
            <div class="section-header">
                <h2 class="section-title" style="margin:0;">Mentor (Giảng viên)</h2>
                @if ($type === 'all')
                    <a class="muted-link" href="{{ route('search.index', array_merge(request()->query(), ['type'=>'mentors'])) }}">Xem tất cả →</a>
                @endif
            </div>
            @if ($mentors && $mentors->count())
                <div class="grid-3">
                    @foreach ($mentors as $m)
                        <article class="item" style="padding: 14px;">
                            <div style="display:flex; gap:12px; align-items:center;">
                                <img src="{{ $m->avatar_url ? asset($m->avatar_url) : asset('images/avatar-default.jpg') }}" alt="Avatar" style="width:48px;height:48px;border-radius:999px;object-fit:cover;border:1px solid var(--border);" />
                                <div style="flex:1;">
                                    <div style="font-weight:700;">{{ $m->name }}</div>
                                    <div style="font-size: 13px; color: var(--muted);">{{ $m->email }}</div>
                                </div>
                                <a href="mailto:{{ $m->email }}" class="btn btn-primary">Liên hệ</a>
                            </div>
                            @if (optional($m->profile)->department || optional($m->profile)->interest_field)
                                <div style="margin-top: 10px; font-size: 14px; color:#374151;">
                                    @if (optional($m->profile)->department)
                                        <div><strong>Đơn vị:</strong> {{ $m->profile->department }}</div>
                                    @endif
                                    @if (optional($m->profile)->interest_field)
                                        <div><strong>Quan tâm:</strong> {{ $m->profile->interest_field }}</div>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
                @if ($mentors instanceof \Illuminate\Contracts\Pagination\Paginator || $mentors instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                    <div style="margin-top: 16px; display:flex; justify-content:center;">{{ $mentors->links() }}</div>
                @endif
            @else
                <div class="card">Không tìm thấy mentor phù hợp.</div>
            @endif
        </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
    // Submit bằng Enter đã được form hỗ trợ tự nhiên.
    // Tự động submit khi đổi loại hoặc bộ lọc để tiện dùng.
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('searchForm');
        ['type','faculty','category'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('change', () => form.submit());
        });
    });
</script>
@endpush

