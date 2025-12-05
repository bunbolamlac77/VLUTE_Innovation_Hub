@extends('layouts.main')

@section('title', ($news->title ?? 'Bản tin Khoa học') . ' - VLUTE Innovation Hub')

@section('content')
    {{-- Hero --}}
    <section class="relative text-white">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/panel-truong.jpg') }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-navy/90 to-brand-green/85"></div>
        <div class="relative">
            <div class="container py-12">
                <a href="{{ route('scientific-news.index') }}" class="inline-flex items-center gap-2 mb-4 text-white/90 hover:text-white">
                    ← Quay lại Bản tin
                </a>
                <h1 class="m-0 text-3xl md:text-4xl font-extrabold max-w-4xl">{{ $news->title }}</h1>
                <div class="mt-3 text-white/90 text-sm flex flex-wrap items-center gap-3">
                    <span class="inline-block bg-white/20 rounded-full px-3 py-1">{{ $news->category ?? 'Chưa phân loại' }}</span>
                    <span>Ngày đăng: {{ optional($news->published_date)->format('d/m/Y') }}</span>
                    @if($news->author)
                        <span>Tác giả: {{ $news->author }}</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="container py-10 grid lg:grid-cols-[1fr,320px] gap-8">
        <article class="bg-white border border-slate-200 rounded-2xl shadow-card overflow-hidden">
            @if ($news->image_url)
                <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-[320px] object-cover" />
            @endif
            <div class="p-6 prose max-w-none ck-content">
                {!! $news->content !!}
            </div>
        </article>
        <aside class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-4">
                <h3 class="m-0 mb-3 text-lg font-extrabold text-slate-900">Thông tin</h3>
                <ul class="m-0 p-0 text-sm text-slate-700 space-y-2">
                    <li><strong>Chủ đề:</strong> {{ $news->category ?? '—' }}</li>
                    <li><strong>Ngày đăng:</strong> {{ optional($news->published_date)->format('d/m/Y') }}</li>
                    <li><strong>Tác giả:</strong> {{ $news->author ?? '—' }}</li>
                    @if($news->source)
                        <li><strong>Nguồn:</strong> <a class="text-indigo-600" href="{{ $news->source }}" target="_blank" rel="noopener">Liên kết gốc</a></li>
                    @endif
                </ul>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-4">
                <h3 class="m-0 mb-3 text-lg font-extrabold text-slate-900">Bản tin mới</h3>
                <ul class="m-0 p-0 space-y-2">
                    @foreach(($recent ?? []) as $n)
                      <li>
                        <a href="{{ route('scientific-news.show', $n) }}" class="text-slate-800 hover:text-brand-navy font-semibold line-clamp-2">{{ $n->title }}</a>
                        <div class="text-xs text-slate-500">{{ optional($n->published_date)->format('d/m/Y') }}</div>
                      </li>
                    @endforeach
                </ul>
                <div class="mt-3"><a class="text-sm text-brand-navy font-semibold" href="{{ route('scientific-news.index') }}">Xem tất cả →</a></div>
            </div>
        </aside>
    </section>
@endsection
