@extends('layouts.admin-shell')

@section('main')
<div class="card">
  <div class="card-title">Tạo cuộc thi</div>

  <form method="POST" action="{{ route('admin.competitions.store') }}" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="md:col-span-2">
        <label class="lbl">Tiêu đề</label>
        <input class="ipt" type="text" name="title" value="{{ old('title') }}" required />
        @error('title')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="lbl">Mô tả</label>
        <textarea class="ipt" name="description" rows="6">{{ old('description') }}</textarea>
        @error('description')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2" x-data="{ type: '{{ old('image_type', 'url') }}' }">
        <label class="lbl">Banner cuộc thi</label>

        <div style="display:flex; gap:12px; margin-bottom:6px; font-size:13px; color:#4b5563;">
          <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
            <input type="radio" name="image_type" value="file" x-model="type">
            <span>Tải ảnh lên</span>
          </label>
          <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
            <input type="radio" name="image_type" value="url" x-model="type">
            <span>Dùng link ảnh online</span>
          </label>
        </div>

        {{-- Upload file --}}
        <div x-show="type === 'file'">
          <input class="ipt" type="file" name="banner_file" accept="image/*" />
          <p class="text-xs text-slate-500 mt-1">Khuyến nghị tỉ lệ 16:9, dung lượng ≤ 5MB.</p>
        </div>

        {{-- URL --}}
        <div x-show="type === 'url'" style="display:none; margin-top:4px;">
          <input class="ipt" type="url" name="banner_url" value="{{ old('banner_url') }}" placeholder="https://..." />
        </div>

        @error('banner_file')<div class="err">{{ $message }}</div>@enderror
        @error('banner_url')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="lbl">Bắt đầu</label>
        <input class="ipt" type="datetime-local" name="start_date" value="{{ old('start_date') }}" />
        @error('start_date')<div class="err">{{ $message }}</div>@enderror
      </div>
      <div>
        <label class="lbl">Kết thúc</label>
        <input class="ipt" type="datetime-local" name="end_date" value="{{ old('end_date') }}" />
        @error('end_date')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="lbl">Trạng thái</label>
        <select class="sel" name="status" required>
          @foreach (['draft' => 'Nháp', 'open' => 'Mở đăng ký', 'judging' => 'Đang chấm', 'closed' => 'Đã đóng', 'archived' => 'Lưu trữ'] as $val => $label)
            <option value="{{ $val }}" @selected(old('status')===$val)>{{ $label }}</option>
          @endforeach
        </select>
        @error('status')<div class="err">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="flex justify-end gap-2">
      <a class="btn btn-ghost" href="{{ route('admin.competitions.index') }}">Hủy</a>
      <button class="btn btn-primary" type="submit">Lưu</button>
    </div>
  </form>
</div>
@endsection

