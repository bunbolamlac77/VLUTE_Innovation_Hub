@extends('layouts.main')

@section('title', 'Nộp bài Challenge - VLUTE Innovation Hub')

@section('content')
  <section class="container py-10">
    <div class="max-w-2xl mx-auto bg-white border border-slate-200 rounded-2xl shadow-card p-6">
      <h1 class="m-0 text-2xl font-extrabold mb-1">Nộp bài cho: {{ $challenge->title }}</h1>
      <p class="text-slate-600 mb-6">Doanh nghiệp: {{ $challenge->organization->name ?? '—' }} · Hạn: {{ optional($challenge->deadline)->format('d/m/Y H:i') ?: '—' }}</p>

      <form method="POST" action="{{ route('challenges.submit.store', $challenge) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <div>
          <label class="block mb-2 font-semibold text-slate-900">Tiêu đề</label>
          <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3" />
          @error('title')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="block mb-2 font-semibold text-slate-900">Họ tên</label>
            <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3" />
            @error('full_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block mb-2 font-semibold text-slate-900">Số điện thoại</label>
            <input type="text" name="phone" value="{{ old('phone', auth()->user()->profile->phone ?? '') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-3" />
            @error('phone')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block mb-2 font-semibold text-slate-900">Địa chỉ</label>
            <input type="text" name="address" value="{{ old('address', auth()->user()->profile->address ?? '') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" />
            @error('address')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block mb-2 font-semibold text-slate-900">Lớp</label>
            <input type="text" name="class_name" value="{{ old('class_name', auth()->user()->profile->class_name ?? '') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" />
            @error('class_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="md:col-span-2">
            <label class="block mb-2 font-semibold text-slate-900">Trường</label>
            <input type="text" name="school_name" value="{{ old('school_name', 'ĐH SPKT Vĩnh Long (VLUTE)') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" />
            @error('school_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
          </div>
        </div>

        <div>
          <label class="block mb-2 font-semibold text-slate-900">Thành viên nhóm tham gia (mời)</label>
          <textarea name="team_members" rows="3" class="w-full rounded-xl border border-slate-300 px-4 py-3" placeholder="Nhập email từng thành viên, cách nhau bởi dấu phẩy (,) hoặc xuống dòng"></textarea>
          @error('team_members')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
          <p class="text-xs text-slate-500 mt-1">Gợi ý: Bạn cũng có thể mời sau ở trang “Bài nộp của tôi”.</p>
        </div>

        <div>
          <label class="block mb-2 font-semibold text-slate-900">Giáo viên hướng dẫn (nếu có)</label>
          <input type="text" name="mentor_name" value="{{ old('mentor_name') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3" />
          @error('mentor_name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="block mb-2 font-semibold text-slate-900">Mô tả giải pháp (tuỳ chọn)</label>
          <textarea name="solution_description" rows="6" class="w-full rounded-xl border border-slate-300 px-4 py-3">{{ old('solution_description') }}</textarea>
          @error('solution_description')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
          <label class="block mb-2 font-semibold text-slate-900">Tệp đính kèm (bắt buộc)</label>
          <input type="file" name="file" required accept=".pdf,.doc,.docx,.zip,.ppt,.pptx" class="block" />
          <div class="text-xs text-slate-500 mt-1">Hỗ trợ: pdf, doc, docx, zip, ppt, pptx. Tối đa 30MB.</div>
          @error('file')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex justify-between">
          <a href="{{ route('challenges.show', $challenge) }}" class="rounded-full border border-slate-300 bg-white px-4 py-2 font-semibold hover:bg-slate-50">Quay lại</a>
          <button type="submit" class="rounded-full bg-indigo-600 text-white px-5 py-2 font-bold shadow hover:shadow-lg">Nộp bài</button>
        </div>
      </form>
    </div>
  </section>
@endsection

