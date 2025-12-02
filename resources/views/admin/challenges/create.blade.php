@extends('layouts.admin-shell')

@section('main')
<div class="card">
  <div class="card-title">Tạo challenge</div>

  <form method="POST" action="{{ route('admin.challenges.store') }}" class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="md:col-span-2">
        <label class="lbl">Tiêu đề</label>
        <input class="ipt" type="text" name="title" value="{{ old('title') }}" required />
        @error('title')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div class="md:col-span-2">
        <label class="lbl">Mô tả</label>
        <textarea class="ipt" name="description" rows="6" required>{{ old('description') }}</textarea>
        @error('description')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="lbl">Doanh nghiệp</label>
        <select class="ipt" name="organization_id" required>
          <option value="">-- Chọn doanh nghiệp --</option>
          @foreach($organizations as $org)
            <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
          @endforeach
        </select>
        @error('organization_id')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="lbl">Hạn</label>
        <input class="ipt" type="datetime-local" name="deadline" value="{{ old('deadline') }}" />
        @error('deadline')<div class="err">{{ $message }}</div>@enderror
      </div>

      <div>
        <label class="lbl">Thưởng</label>
        <input class="ipt" type="text" name="reward" value="{{ old('reward') }}" />
      </div>

      <div>
        <label class="lbl">Trạng thái</label>
        <select class="ipt" name="status" required>
          <option value="draft" {{ old('status','draft')==='draft' ? 'selected' : '' }}>Nháp</option>
          <option value="open" {{ old('status')==='open' ? 'selected' : '' }}>Mở</option>
          <option value="closed" {{ old('status')==='closed' ? 'selected' : '' }}>Đóng</option>
        </select>
      </div>
    </div>

    <div class="flex justify-end gap-2">
      <a class="btn btn-ghost" href="{{ route('admin.challenges.index') }}">Hủy</a>
      <button class="btn btn-primary" type="submit">Lưu</button>
    </div>
  </form>
</div>
@endsection

