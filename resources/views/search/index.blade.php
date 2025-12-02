@extends('layouts.main')

@section('title', 'Tìm kiếm - VLUTE Innovation Hub')

@section('content')
<section class="container py-7">
  <h1 class="text-2xl font-extrabold mb-3">Tìm kiếm</h1>
  <p class="text-slate-600 mb-4">Tìm ý tưởng, cuộc thi, mentor một cách nhanh chóng.</p>

  <form method="GET" action="{{ route('search.index') }}" id="searchForm" class="grid grid-cols-1 md:grid-cols-[1.6fr,1fr,1fr,1fr] gap-3 items-end">
        <div>
      <label for="q" class="block font-semibold mb-2">Từ khóa</label>
      <div class="flex gap-2">
        <input type="search" id="q" name="q" value="{{ $q }}" placeholder="Tìm ý tưởng, cuộc thi, mentor…"
               class="flex-1 rounded-full border border-slate-300 px-4 py-3 text-[15px]" />
        <button type="submit" class="rounded-full bg-white text-brand-navy px-4 py-3 font-extrabold border hover:brightness-95">🔍 Tìm</button>
            </div>
        </div>
        <div>
      <label for="type" class="block font-semibold mb-2">Loại</label>
      <select id="type" name="type" class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white">
                <option value="all" {{ $type==='all' ? 'selected' : '' }}>Tất cả</option>
                <option value="ideas" {{ $type==='ideas' ? 'selected' : '' }}>Ý tưởng</option>
                <option value="competitions" {{ $type==='competitions' ? 'selected' : '' }}>Cuộc thi</option>
                <option value="mentors" {{ $type==='mentors' ? 'selected' : '' }}>Mentor</option>
            </select>
        </div>
        <div>
      <label for="faculty" class="block font-semibold mb-2">Khoa</label>
      <select id="faculty" name="faculty" class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white">
                <option value="">Tất cả khoa</option>
                @foreach ($faculties as $f)
                    <option value="{{ $f->id }}" {{ (string)$facultyId === (string)$f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
      <label for="category" class="block font-semibold mb-2">Lĩnh vực (Ý tưởng)</label>
      <select id="category" name="category" class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white">
                <option value="">Tất cả lĩnh vực</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" {{ (string)$categoryId === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </form>
</section>

<section class="container pb-16">
    @if ($type === 'all' || $type === 'ideas')
    <div class="mb-7">
      <div class="flex items-end justify-between gap-4 mb-3">
        <h2 class="text-2xl font-extrabold m-0">Ý tưởng</h2>
                @if ($type === 'all')
          <a class="text-brand-navy font-semibold" href="{{ route('search.index', array_merge(request()->query(), ['type'=>'ideas'])) }}">Xem tất cả →</a>
                @endif
            </div>
            @if ($ideas && $ideas->count())
        <div class="grid md:grid-cols-4 gap-4">
                    @foreach ($ideas as $idea)
            <article class="border border-slate-200 bg-white rounded-2xl shadow-card overflow-hidden">
              <a href="{{ route('ideas.show', $idea->slug) }}" class="block no-underline text-inherit">
                <div class="h-[140px] bg-gradient-to-br from-indigo-200 to-emerald-200"></div>
                <div class="p-4">
                  <div class="flex items-center justify-between text-slate-500 text-xs mb-1.5">
                    <span class="inline-block bg-brand-gray-100 text-slate-700 px-2.5 py-1 rounded-full">{{ $idea->faculty->name ?? 'Chưa phân khoa' }}</span>
                    <span class="inline-block bg-emerald-50 text-brand-green px-2.5 py-1 rounded-full">{{ $idea->category->name ?? '—' }}</span>
                                    </div>
                  <h5 class="font-bold text-slate-900 leading-snug">{{ $idea->title }}</h5>
                                    @if ($idea->summary)
                    <p class="text-sm text-slate-600 mt-2 leading-snug max-h-[3.2em] overflow-hidden">{{ $idea->summary }}</p>
                                    @endif
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
                @if ($ideas instanceof \Illuminate\Contracts\Pagination\Paginator || $ideas instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
          <div class="mt-4 flex justify-center">{{ $ideas->links() }}</div>
                @endif
            @else
        <div class="bg-white border border-slate-200 rounded-2xl p-6">Không có ý tưởng phù hợp.</div>
            @endif
        </div>
    @endif

    @if ($type === 'all' || $type === 'competitions')
    <div class="mb-7">
      <div class="flex items-end justify-between gap-4 mb-3">
        <h2 class="text-2xl font-extrabold m-0">Cuộc thi & Sự kiện</h2>
                @if ($type === 'all')
          <a class="text-brand-navy font-semibold" href="{{ route('search.index', array_merge(request()->query(), ['type'=>'competitions'])) }}">Xem tất cả →</a>
                @endif
            </div>
            @if ($competitions && $competitions->count())
        <div class="grid md:grid-cols-4 gap-4">
                    @foreach ($competitions as $comp)
            <article class="border border-slate-200 bg-white rounded-2xl shadow-card overflow-hidden">
              <a href="{{ route('competitions.show', $comp->slug) }}" class="block no-underline text-inherit">
                <div class="h-32 bg-gradient-to-br from-amber-200 to-indigo-200"></div>
                <div class="p-4">
                  <h5 class="font-bold text-slate-900 leading-snug">{{ $comp->title }}</h5>
                                    @if ($comp->start_date || $comp->end_date)
                    <div class="flex items-center justify-between text-slate-500 text-xs mt-1">
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
          <div class="mt-4 flex justify-center">{{ $competitions->links() }}</div>
                @endif
            @else
        <div class="bg-white border border-slate-200 rounded-2xl p-6">Không có cuộc thi phù hợp.</div>
            @endif
        </div>
    @endif

    @if ($type === 'all' || $type === 'mentors')
    <div class="mb-7">
      <div class="flex items-end justify-between gap-4 mb-3">
        <h2 class="text-2xl font-extrabold m-0">Mentor (Giảng viên)</h2>
                @if ($type === 'all')
          <a class="text-brand-navy font-semibold" href="{{ route('search.index', array_merge(request()->query(), ['type'=>'mentors'])) }}">Xem tất cả →</a>
                @endif
            </div>
            @if ($mentors && $mentors->count())
        <div class="grid md:grid-cols-3 gap-4">
                    @foreach ($mentors as $m)
            <article class="border border-slate-200 bg-white rounded-2xl shadow-card p-4">
              <div class="flex items-center gap-3">
                <img src="{{ $m->avatar_url ? asset($m->avatar_url) : asset('images/avatar-default.svg') }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 100%27%3E%3Ccircle cx=%2750%27 cy=%2750%27 r=%2740%27 fill=%27%230a0f5a%27/%3E%3Ctext x=%2750%27 y=%2755%27 font-size=%2740%27 fill=%27white%27 text-anchor=%27middle%27%3E{{ strtoupper(substr($m->name, 0, 1)) }}%3C/text%3E%3C/svg%3E'" />
                <div class="flex-1">
                  <div class="font-bold">{{ $m->name }}</div>
                  <div class="text-xs text-slate-500">{{ $m->email }}</div>
                                </div>
                <a href="mailto:{{ $m->email }}" class="rounded-full bg-white text-brand-navy px-4 py-2 font-bold border border-slate-200 hover:bg-slate-50">Liên hệ</a>
                            </div>
                            @if (optional($m->profile)->department || optional($m->profile)->interest_field)
                <div class="mt-3 text-sm text-slate-700">
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
          <div class="mt-4 flex justify-center">{{ $mentors->links() }}</div>
                @endif
            @else
        <div class="bg-white border border-slate-200 rounded-2xl p-6">Không tìm thấy mentor phù hợp.</div>
            @endif
        </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('searchForm');
    ['type','faculty','category'].forEach(id => { const el = document.getElementById(id); if (el) el.addEventListener('change', () => form.submit()); });
    });
</script>
@endpush
