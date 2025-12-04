@extends('layouts.admin-shell')

@section('main')
  {{-- Breadcrumbs --}}
  <nav class="text-sm text-slate-500 mb-4 flex items-center gap-2">
    <a href="/" class="text-brand-navy font-semibold">Trang chủ</a>
    <span>/</span>
    <a href="{{ route('admin.home') }}" class="hover:underline">Quản trị</a>
    <span>/</span>
    <a href="{{ route('admin.news.index') }}" class="hover:underline">Bản tin khoa học</a>
    <span>/</span>
    <span class="text-slate-700">Thêm mới</span>
  </nav>

  {{-- Error summary --}}
  @if ($errors->any())
    <div class="flash-success border-rose-200 bg-rose-50 text-rose-800">
      <div class="font-semibold mb-1">Có lỗi cần kiểm tra:</div>
      <ul class="list-disc pl-6 space-y-0.5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.news.store') }}" class="grid gap-6 md:grid-cols-3">
    @csrf

    {{-- LEFT: Main content --}}
    <div class="md:col-span-2 grid gap-6">
      <div class="card">
        <div class="card-title">Thông tin bài viết</div>
        <div class="card-body grid gap-5">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Tiêu đề <span class="text-rose-600">*</span></label>
            <input class="ipt w-full" type="text" name="title" value="{{ old('title') }}" placeholder="Nhập tiêu đề nổi bật cho bài viết" required />
            @error('title')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
      </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Mô tả ngắn</label>
            <textarea class="ipt w-full" name="description" rows="3" placeholder="Tóm tắt nội dung chính, 1-2 câu">{{ old('description') }}</textarea>
            @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Nội dung</div>
        <div class="card-body">
          <textarea class="ipt w-full min-h-[320px]" name="content" rows="12" placeholder="Soạn thảo nội dung chi tiết" required>{{ old('content') }}</textarea>
          @error('content')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
          <p class="mt-2 text-xs text-slate-500">Gợi ý: Bạn có thể dán nội dung từ trình soạn thảo khác. Hỗ trợ đoạn văn, liên kết, và định dạng cơ bản.</p>
        </div>
      </div>
      </div>

    {{-- RIGHT: Meta & publish --}}
    <aside class="grid gap-6">
      <div class="card">
        <div class="card-title">Xuất bản</div>
        <div class="card-body grid gap-4">
      <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Ngày đăng <span class="text-rose-600">*</span></label>
            <input class="ipt w-full" type="date" name="published_date" value="{{ old('published_date', now()->format('Y-m-d')) }}" required />
      </div>
      <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Danh mục</label>
            <input class="ipt w-full" type="text" name="category" value="{{ old('category') }}" placeholder="Ví dụ: AI, Khoa học dữ liệu" />
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Thông tin thêm</div>
        <div class="card-body grid gap-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Tác giả</label>
            <input class="ipt w-full" type="text" name="author" value="{{ old('author') }}" placeholder="Tên tác giả hoặc nhóm" />
      </div>
      <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Nguồn</label>
            <input class="ipt w-full" type="text" name="source" value="{{ old('source') }}" placeholder="Tạp chí, hội thảo, website..." />
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-title">Ảnh đại diện</div>
        <div class="card-body grid gap-3">
          <input class="ipt w-full" type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." />
          @if (old('image_url'))
            <div class="rounded-xl overflow-hidden border border-slate-200">
              <img src="{{ old('image_url') }}" alt="Xem trước ảnh" class="w-full h-40 object-cover" onerror="this.closest('div').remove();" />
            </div>
          @else
            <div class="text-xs text-slate-500">Dán liên kết ảnh để hiển thị hình đại diện cho bài viết.</div>
          @endif
      </div>
    </div>

      <div class="flex items-center justify-end gap-2">
      <a class="btn btn-ghost" href="{{ route('admin.news.index') }}">Hủy</a>
      <button class="btn btn-primary" type="submit">Lưu</button>
    </div>
    </aside>
  </form>
@endsection
