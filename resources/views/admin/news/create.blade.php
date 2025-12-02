@extends('layouts.admin-shell')

@section('main')
<div class="card">
  <div class="card-title">Thêm tin khoa học</div>

  <form method="POST" action="{{ route('admin.news.store') }}" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="md:col-span-2">
        <label class="lbl">Tiêu đề</label>
        <input class="ipt" type="text" name="title" value="{{ old('title') }}" required />
        @error('title')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="lbl">Mô tả ngắn</label>
        <textarea class="ipt" name="description" rows="3">{{ old('description') }}</textarea>
        @error('description')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="lbl">Nội dung</label>
        <textarea class="ipt" name="content" rows="8" required>{{ old('content') }}</textarea>
        @error('content')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="lbl">Tác giả</label>
        <input class="ipt" type="text" name="author" value="{{ old('author') }}" />
      </div>
      <div>
        <label class="lbl">Nguồn</label>
        <input class="ipt" type="text" name="source" value="{{ old('source') }}" />
      </div>

      <div class="md:col-span-2">
        <label class="lbl">Ảnh (URL)</label>
        <input class="ipt" type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://..." />
      </div>

      <div>
        <label class="lbl">Ngày đăng</label>
        <input class="ipt" type="date" name="published_date" value="{{ old('published_date', now()->format('Y-m-d')) }}" required />
      </div>

      <div>
        <label class="lbl">Danh mục</label>
        <input class="ipt" type="text" name="category" value="{{ old('category') }}" />
      </div>
    </div>

    <div class="flex justify-end gap-2">
      <a class="btn btn-ghost" href="{{ route('admin.news.index') }}">Hủy</a>
      <button class="btn btn-primary" type="submit">Lưu</button>
    </div>
  </form>
</div>
@endsection

