@extends('layouts.admin-shell')

@section('main')
<div class="card max-w-2xl mx-auto">
    <div class="card-title">Thêm Banner Mới</div>
    
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block font-bold mb-1">Hình ảnh (Bắt buộc)</label>
            <input type="file" name="image" required class="w-full border p-2 rounded" accept="image/*">
            @error('image')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Kích thước khuyên dùng: 1920x600px</p>
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1">Tiêu đề (Tuỳ chọn)</label>
            <input type="text" name="title" class="w-full border p-2 rounded" placeholder="Nhập tiêu đề hiển thị trên ảnh...">
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1">Link liên kết (Tuỳ chọn)</label>
            <input type="url" name="link_url" class="w-full border p-2 rounded" placeholder="https://...">
            @error('link_url')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-1">Mô tả (Tuỳ chọn)</label>
            <textarea name="description" class="w-full border p-2 rounded" rows="3" placeholder="Nhập mô tả ngắn..."></textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4 mb-4">
            <div class="flex-1">
                <label class="block font-bold mb-1">Thứ tự hiển thị</label>
                <input type="number" name="order" value="0" class="w-full border p-2 rounded">
                @error('order')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center pt-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 font-bold">Kích hoạt ngay</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-6">
            <a href="{{ route('admin.banners.index') }}" class="btn btn-ghost">Hủy</a>
            <button type="submit" class="btn btn-primary">Lưu Banner</button>
        </div>
    </form>
</div>
@endsection

