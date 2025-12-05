@extends('layouts.admin-shell')

@section('main')
<div class="card max-w-2xl mx-auto">
    <div class="card-title">Chỉnh sửa Banner</div>
    
    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block font-bold mb-1">Hình ảnh hiện tại</label>
            <img src="{{ Storage::url($banner->image_path) }}" class="h-32 w-48 object-cover rounded border mb-2">
            <p class="text-sm text-gray-500 mb-2">Chọn ảnh mới để thay đổi</p>
            <input type="file" name="image" class="w-full border p-2 rounded" accept="image/*">
            @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Kích thước khuyên dùng: 1920x600px</p>
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1">Tiêu đề (Tuỳ chọn)</label>
            <input type="text" name="title" value="{{ $banner->title }}" class="w-full border p-2 rounded" placeholder="Nhập tiêu đề hiển thị trên ảnh...">
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1">Link liên kết (Tuỳ chọn)</label>
            <input type="url" name="link_url" value="{{ $banner->link_url }}" class="w-full border p-2 rounded" placeholder="https://...">
            @error('link_url')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1">Mô tả (Tuỳ chọn)</label>
            <textarea name="description" class="w-full border p-2 rounded" rows="3" placeholder="Nhập mô tả ngắn...">{{ $banner->description }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="block font-bold mb-1">Thứ tự hiển thị</label>
                <input type="number" name="order" value="{{ $banner->order }}" class="w-full border p-2 rounded">
                @error('order')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center pt-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 font-bold">Kích hoạt</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-6">
            <a href="{{ route('admin.banners.index') }}" class="btn btn-ghost">Hủy</a>
            <button type="submit" class="btn btn-primary">Cập nhật Banner</button>
        </div>
    </form>
</div>
@endsection

