@extends('layouts.main')

@section('title', 'Nộp bài dự thi: ' . $competition->title)

@section('content')
  <section class="container py-8">
    {{-- Breadcrumbs --}}
    <nav class="text-sm text-slate-500 mb-4 flex items-center gap-2">
      <a href="{{ route('welcome') }}" class="text-brand-navy">Trang chủ</a>
      <span>/</span>
      <a href="{{ route('my-competitions.index') }}" class="text-brand-navy">Cuộc thi của tôi</a>
      <span>/</span>
      <span>Nộp bài</span>
    </nav>

    {{-- Title --}}
    <h1 class="text-2xl font-extrabold mb-4">Nộp bài dự thi: {{ $competition->title }}</h1>

    {{-- Flash messages --}}
    @if ($errors->any())
      <div class="mb-4 rounded-xl border border-rose-300 bg-rose-50 text-rose-800 px-4 py-3">
        <div class="font-semibold mb-1">Vui lòng kiểm tra lại:</div>
        <ul class="list-disc list-inside text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 text-emerald-800 px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="mb-4 rounded-xl border border-rose-300 bg-rose-50 text-rose-800 px-4 py-3">{{ session('error') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
      {{-- Main form --}}
      <div class="lg:col-span-2">
        <article class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-card">
          <div class="p-6 text-slate-900">
            <form method="POST" action="{{ route('competitions.submit.store', $registration->id) }}" enctype="multipart/form-data" class="space-y-6">
              @csrf

              <div>
                <label class="block text-sm font-medium text-slate-700">Tiêu đề bài nộp <span class="text-rose-600">*</span></label>
                <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-brand-navy focus:border-brand-navy" placeholder="VD: Bản thuyết minh ý tưởng" />
                @error('title')
                  <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700">Tóm tắt (tuỳ chọn)</label>
                <textarea name="abstract" rows="5" class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-brand-navy focus:border-brand-navy" placeholder="Mô tả ngắn gọn nội dung bài nộp...">{{ old('abstract') }}</textarea>
                @error('abstract')
                  <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-medium text-slate-700">Tập tin đính kèm <span class="text-rose-600">*</span></label>
                <input type="file" name="files[]" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx" multiple required class="mt-1 block w-full" />
                <p class="mt-1 text-xs text-slate-500">Tối đa 20MB mỗi file. Hỗ trợ: pdf, doc, docx, zip, ppt, pptx. Có thể chọn nhiều file.</p>
                @error('files')
                  <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
                @error('files.*')
                  <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                @enderror
              </div>

              <div class="flex justify-end gap-2">
                <a href="{{ route('my-competitions.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-full">Hủy</a>
                <button type="submit" class="px-4 py-2 bg-brand-navy text-white rounded-full font-bold hover:brightness-110">Nộp bài</button>
              </div>
            </form>
          </div>
        </article>

        @if(($submissions ?? collect())->count() > 0)
          <article class="mt-6 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-card">
            <div class="p-6">
              <h3 class="text-lg font-semibold mb-4">Các lần đã nộp</h3>
              <ul class="divide-y divide-slate-200">
                @foreach ($submissions as $s)
                  <li class="py-3 flex items-start justify-between gap-4">
                    <div>
                      <div class="font-medium text-slate-900">{{ $s->title }}</div>
                      <div class="text-xs text-slate-500">Nộp lúc {{ $s->created_at->format('d/m/Y H:i') }}</div>
                      @if ($s->attachments && $s->attachments->count())
                        <div class="mt-1 text-sm">
                          @foreach ($s->attachments as $att)
                            <a href="{{ route('attachments.download', $att->id) }}" class="text-brand-navy hover:underline">{{ $att->filename }}</a>@if(!$loop->last), @endif
                          @endforeach
                        </div>
                      @endif
                    </div>
                    <div class="flex items-center gap-3">
                      <a class="text-brand-navy hover:underline text-sm" href="{{ route('competitions.submit.edit', $s->id) }}">Sửa</a>
                      <form method="POST" action="{{ route('competitions.submit.destroy', $s->id) }}" onsubmit="return confirm('Xoá bài nộp này?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:underline text-sm">Xoá</button>
                      </form>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          </article>
        @endif
      </div>

      {{-- Sidebar --}}
      <aside>
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-card">
          <div class="p-6">
            @php
              $end = $competition->end_date;
              $isOpen = $competition->status === 'open' && (!$end || $end->isFuture());
              $submitted = ($submissions ?? collect())->count();
            @endphp
            <div class="flex items-center justify-between mb-3">
              <h3 class="font-semibold">Thông tin cuộc thi</h3>
              <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $isOpen ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-700' }}">{{ $competition->status }}</span>
            </div>
            <ul class="text-sm text-slate-600 space-y-2">
              @if ($competition->start_date)
                <li>Bắt đầu: <strong>{{ $competition->start_date->format('d/m/Y H:i') }}</strong></li>
              @endif
              @if ($competition->end_date)
                <li>Hạn chót: <strong>{{ $competition->end_date->format('d/m/Y H:i') }}</strong></li>
                <li class="text-xs text-slate-400">{{ $isOpen ? 'Còn ' . $competition->end_date->diffForHumans(null, true) : 'Đã kết thúc ' . $competition->end_date->diffForHumans() }}</li>
              @endif
              <li>Đã nộp: <strong>{{ $submitted }}</strong></li>
              <li>Nhóm: <strong>{{ $registration->team_name ?? '(Cá nhân)' }}</strong></li>
            </ul>

            <div class="mt-6">
              <h4 class="font-semibold mb-2">Thành viên & Mentor</h4>
              <div class="rounded-lg border border-slate-200 p-3 text-sm text-slate-700">
                <div>- Thành viên: <strong>{{ auth()->user()->name }}</strong> (Bạn)</div>
                <div>- Mentor: <span class="text-slate-500">Chưa cập nhật</span></div>
              </div>
            </div>
          </div>
        </div>
      </aside>
    </div>
  </section>
@endsection
