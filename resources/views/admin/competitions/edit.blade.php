@extends('layouts.admin-shell')

@section('main')
<div class="card">
  <div class="card-title">Sửa cuộc thi</div>

  <form method="POST" action="{{ route('admin.competitions.update', $competition) }}" class="space-y-6">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="md:col-span-2">
        <label class="lbl">Tiêu đề</label>
        <input class="ipt" type="text" name="title" value="{{ old('title', $competition->title) }}" required />
        @error('title')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="lbl">Mô tả</label>
        <textarea class="ipt" name="description" rows="6">{{ old('description', $competition->description) }}</textarea>
        @error('description')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="lbl">Ảnh bìa (URL)</label>
        <input class="ipt" type="url" name="banner_url" value="{{ old('banner_url', $competition->banner_url) }}" placeholder="https://..." />
      </div>

      <div>
        <label class="lbl">Bắt đầu</label>
        <input class="ipt" type="datetime-local" name="start_date" value="{{ old('start_date', optional($competition->start_date)->format('Y-m-d\TH:i')) }}" />
      </div>

      <div>
        <label class="lbl">Kết thúc</label>
        <input class="ipt" type="datetime-local" name="end_date" value="{{ old('end_date', optional($competition->end_date)->format('Y-m-d\TH:i')) }}" />
      </div>

      <div>
        <label class="lbl">Trạng thái</label>
        <select class="ipt" name="status" required>
          @php($statuses = ['draft'=>'Nháp','open'=>'Mở đăng ký','judging'=>'Chấm','closed'=>'Đóng','archived'=>'Lưu trữ'])
          @foreach($statuses as $val => $label)
            <option value="{{ $val }}" {{ old('status', $competition->status) === $val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="lbl">Slug (không thay đổi)</label>
        <input class="ipt" type="text" value="{{ $competition->slug }}" disabled />
      </div>
    </div>

    <div class="flex justify-between gap-2">
      <a class="btn btn-ghost" href="{{ route('admin.competitions.index') }}">Quay lại</a>
      <div class="flex gap-2">
        <button class="btn btn-primary" type="submit">Lưu</button>
      </div>
    </div>
  </form>
</div>
@endsection

