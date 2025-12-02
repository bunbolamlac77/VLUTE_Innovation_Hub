@extends('layouts.main')

@section('title', 'Challenges - VLUTE Innovation Hub')

@section('content')
  {{-- Hero --}}
  <section class="relative text-white">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/panel-truong.jpg') }}')"></div>
    <div class="absolute inset-0 bg-gradient-to-tr from-brand-navy/90 to-brand-green/85"></div>
    <div class="relative">
      <div class="container py-12">
        <h1 class="m-0 text-3xl md:text-4xl font-extrabold">Challenges từ Doanh nghiệp</h1>
        <p class="m-0 mt-2 text-white/90 max-w-3xl">Tham gia giải quyết bài toán thực tế từ doanh nghiệp, kết nối PoC và cơ hội nghề nghiệp.</p>
      </div>
    </div>
  </section>

  {{-- Filters --}}
  <section class="container py-8">
    <form method="GET" action="{{ route('challenges.index') }}" class="grid md:grid-cols-3 gap-4 items-end">
      <div>
        <label class="block mb-2 font-semibold text-slate-900">Từ khóa</label>
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm challenge..."
               class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500" />
      </div>
      <div>
        <label class="block mb-2 font-semibold text-slate-900">Trạng thái</label>
        <select name="status" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
          @php($status = request('status'))
          <option value="open" {{ $status==='open' || $status===null ? 'selected' : '' }}>Đang mở</option>
          <option value="closed" {{ $status==='closed' ? 'selected' : '' }}>Đã đóng</option>
          <option value="draft" {{ $status==='draft' ? 'selected' : '' }}>Nháp</option>
        </select>
      </div>
      <div class="md:self-end">
        <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-5 py-3 font-bold border border-transparent hover:brightness-95">🔍 Tìm</button>
        @if (request()->hasAny(['q','status']) && (request('q') || request('status')!=='open'))
          <a href="{{ route('challenges.index') }}" class="ml-2 inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-3 font-bold hover:bg-slate-50">✕ Xóa</a>
        @endif
      </div>
    </form>
  </section>

  {{-- Grid --}}
  <section class="container pb-16">
    @if ($challenges->count() > 0)
      <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach ($challenges as $c)
          <article class="flex flex-col border border-slate-200 bg-white rounded-2xl shadow-card overflow-hidden">
            <div class="h-[160px] bg-gradient-to-br from-indigo-200 to-emerald-200"></div>
            <div class="p-4 flex-1 flex flex-col">
              <div class="flex items-center justify-between text-slate-500 text-xs mb-1.5">
                <span class="inline-block bg-brand-gray-100 text-slate-700 px-2.5 py-1 rounded-full">{{ $c->organization->name ?? 'Doanh nghiệp' }}</span>
                <span class="chip">{{ $c->status }}</span>
              </div>
              <h5 class="font-bold text-slate-900 leading-snug mb-1 line-clamp-2">
                <a class="no-underline text-slate-900" href="{{ route('challenges.show', $c) }}">{{ $c->title }}</a>
              </h5>
              <p class="text-sm text-slate-600 mt-1 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($c->description), 160) }}</p>
              <div class="mt-auto pt-3 flex items-center justify-between text-xs text-slate-500">
                <span>Hạn: {{ optional($c->deadline)->format('d/m/Y H:i') ?: '—' }}</span>
                <span>Thưởng: {{ $c->reward ?: '—' }}</span>
              </div>
            </div>
          </article>
        @endforeach
      </div>
      @if ($challenges->hasPages())
        <div class="mt-10 flex justify-center">{{ $challenges->links() }}</div>
      @endif
    @else
      <div class="text-center bg-white border border-slate-200 rounded-2xl p-16">
        <div class="text-5xl mb-4">🔍</div>
        <h3 class="m-0 mb-3 text-slate-900">Chưa có challenge nào</h3>
        <p class="text-slate-500 m-0">Hãy thử thay đổi bộ lọc hoặc quay lại sau.</p>
      </div>
    @endif
  </section>
@endsection

