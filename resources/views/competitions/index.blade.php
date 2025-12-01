@extends('layouts.main')

@section('title', 'Cuộc thi & Sự kiện')

@section('content')
  <section class="container py-8">
    <h1 class="text-3xl font-bold mb-6">Các Cuộc thi & Sự kiện</h1>

        @if ($competitions->count() > 0)
      <div class="grid md:grid-cols-3 gap-6">
                @foreach ($competitions as $competition)
          <article class="border border-slate-200 rounded-2xl overflow-hidden shadow-card bg-white">
            <a href="{{ route('competitions.show', $competition->slug) }}" class="block h-52 bg-slate-100 overflow-hidden">
              <img src="{{ $competition->banner_url ?? asset('images/panel-truong.jpg') }}" alt="{{ $competition->title }}" class="w-full h-full object-cover" />
                        </a>
            <div class="p-5">
              <h3 class="text-lg font-semibold mb-2">
                <a href="{{ route('competitions.show', $competition->slug) }}" class="no-underline text-slate-900">{{ $competition->title }}</a>
                            </h3>
              <p class="text-sm text-slate-500 mb-4">⏳ Kết thúc: {{ optional($competition->end_date)->format('d/m/Y') }}</p>
              <a href="{{ route('competitions.show', $competition->slug) }}" class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-4 py-2 font-bold border border-transparent hover:brightness-95">Xem chi tiết</a>
                        </div>
          </article>
                @endforeach
            </div>

      <div class="mt-8">{{ $competitions->links() }}</div>
        @else
            <p>Hiện tại không có cuộc thi nào đang mở.</p>
        @endif
  </section>
@endsection
