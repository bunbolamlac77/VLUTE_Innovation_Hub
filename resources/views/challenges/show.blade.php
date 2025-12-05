@extends('layouts.main')

@section('title', ($challenge->title ?? 'Challenge') . ' - VLUTE Innovation Hub')

@section('content')
  {{-- Hero --}}
  @php($hero = $challenge->image ? asset('storage/' . $challenge->image) : asset('images/panel-truong.jpg'))
  <section class="relative text-white">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $hero }}')"></div>
    <div class="absolute inset-0 bg-gradient-to-tr from-brand-navy/90 to-brand-green/85"></div>
    <div class="relative">
      <div class="container py-12">
        <a href="{{ route('challenges.index') }}" class="inline-flex items-center gap-2 mb-4 text-white/90 hover:text-white">← Quay lại Challenges</a>
        <h1 class="m-0 text-3xl md:text-4xl font-extrabold max-w-4xl">{{ $challenge->title }}</h1>
        <div class="mt-3 text-white/90 text-sm flex flex-wrap items-center gap-3">
          <span class="inline-block bg-white/20 rounded-full px-3 py-1">{{ $challenge->organization->name ?? 'Doanh nghiệp' }}</span>
          <span class="inline-block bg-white/20 rounded-full px-3 py-1">Trạng thái: {{ $challenge->status }}</span>
          <span>Hạn: {{ optional($challenge->deadline)->format('d/m/Y H:i') ?: '—' }}</span>
          @if($challenge->reward)
            <span>Thưởng: {{ $challenge->reward }}</span>
          @endif
        </div>
      </div>
    </div>
  </section>

  {{-- Content --}}
  <section class="container py-10 grid lg:grid-cols-[1fr,320px] gap-8">
    <article class="bg-white border border-slate-200 rounded-2xl shadow-card overflow-hidden">
      <div class="p-6 prose max-w-none">
        @if($challenge->problem_statement)
          <h2>Bối cảnh & vấn đề</h2>
          {!! $challenge->problem_statement !!}
        @endif
        @if($challenge->requirements)
          <h2>Yêu cầu & phạm vi</h2>
          {!! $challenge->requirements !!}
        @endif
        @if(!$challenge->problem_statement && !$challenge->requirements)
        {!! nl2br(e($challenge->description)) !!}
        @endif
      </div>
    </article>
    <aside class="space-y-4">
      @if($challenge->status === 'open')
        <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-4">
          <h3 class="m-0 mb-3 text-lg font-extrabold text-slate-900">Tham gia Challenge</h3>
          @auth
            <a href="{{ route('challenges.submit.create', $challenge) }}" class="inline-flex items-center gap-2 rounded-full bg-indigo-600 text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition">Nộp bài</a>
          @else
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-4 py-2 font-bold hover:bg-slate-50">Đăng nhập để nộp bài</a>
          @endauth
        </div>
      @endif
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-4">
        <h3 class="m-0 mb-3 text-lg font-extrabold text-slate-900">Thông tin</h3>
        <ul class="m-0 p-0 text-sm text-slate-700 space-y-2">
          <li><strong>Doanh nghiệp:</strong> {{ $challenge->organization->name ?? '—' }}</li>
          <li><strong>Trạng thái:</strong> {{ $challenge->status }}</li>
          <li><strong>Hạn:</strong> {{ optional($challenge->deadline)->format('d/m/Y H:i') ?: '—' }}</li>
          <li><strong>Thưởng:</strong> {{ $challenge->reward ?: '—' }}</li>
        </ul>
      </div>
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-4">
        <h3 class="m-0 mb-3 text-lg font-extrabold text-slate-900">Tài liệu/Đề bài</h3>
        @if($challenge->attachments->isNotEmpty())
          <ul class="m-0 p-0 text-sm space-y-2">
            @foreach($challenge->attachments as $file)
              <li>
                <a class="inline-flex items-center gap-2 text-indigo-600 hover:underline" href="{{ route('attachments.download', $file->id) }}">📎 {{ $file->filename }}</a>
              </li>
            @endforeach
          </ul>
        @else
          <p class="m-0 text-sm text-slate-600">Chưa có tệp đính kèm.</p>
        @endif
      </div>
    </aside>
  </section>
@endsection

