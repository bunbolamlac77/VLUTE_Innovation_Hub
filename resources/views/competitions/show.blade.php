@extends('layouts.main')

@section('title', $competition->title)

@section('content')
  <section class="container py-8">
    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 text-emerald-800 px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="mb-4 rounded-xl border border-rose-300 bg-rose-50 text-rose-800 px-4 py-3">{{ session('error') }}</div>
    @endif
    @if (session('info'))
      <div class="mb-4 rounded-xl border border-sky-300 bg-sky-50 text-sky-800 px-4 py-3">{{ session('info') }}</div>
    @endif

    <article class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-card">
      <div class="w-full h-60 bg-slate-100 overflow-hidden">
        <img src="{{ $competition->banner_url ?? asset('images/panel-truong.jpg') }}" alt="{{ $competition->title }}" class="w-full h-full object-cover" />
      </div>
      <div class="p-6">
        <h1 class="text-2xl font-extrabold m-0 mb-2">{{ $competition->title }}</h1>
        <div class="text-slate-600 mb-4">
          <span>Trạng thái: <strong>{{ $competition->status }}</strong></span>
          @if($competition->end_date)
            <span class="ml-4">Hạn chót: <strong>{{ $competition->end_date->format('d/m/Y H:i') }}</strong></span>
          @endif
        </div>

        <div class="prose max-w-none">
          {!! $competition->description !!}
        </div>

        <div class="mt-6 flex items-center gap-3 flex-wrap">
          @php
            $isOpen = $competition->status === 'open' && (!$competition->end_date || $competition->end_date->isFuture());
            $hasRegistered = auth()->check() && $competition->registrations()->where('user_id', auth()->id())->exists();
          @endphp

          @if ($isOpen)
            @auth
              @if ($hasRegistered)
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 font-semibold text-slate-700 cursor-default">Bạn đã đăng ký cuộc thi này</span>
                <a class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-4 py-2 font-bold border border-transparent hover:brightness-95" href="{{ route('my-competitions.index') }}">Xem cuộc thi của tôi</a>
              @else
                <form method="POST" action="{{ route('competitions.register', $competition) }}">
                  @csrf
                  <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-4 py-2 font-bold border border-transparent hover:brightness-95">Đăng ký ngay</button>
                </form>
              @endif
            @else
              <a class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-4 py-2 font-bold border border-transparent hover:brightness-95" href="{{ route('login') }}">Đăng nhập để đăng ký</a>
            @endauth
          @else
            <button class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 font-semibold text-slate-400 cursor-not-allowed" disabled>Cuộc thi đã đóng hoặc chưa mở</button>
          @endif

          <a class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 font-semibold hover:bg-slate-50" href="{{ route('competitions.index') }}">← Quay lại danh sách</a>
        </div>
      </div>
    </article>
  </section>
@endsection
