@extends('layouts.admin-shell')

@section('main')
<div class="card">
    <div class="card-title flex justify-between items-center">
        <span>Quản lý Banner Slider</span>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">+ Thêm Banner</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-wrap">
        <table class="tbl">
            <thead>
                <tr>
                    <th width="100">Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Link</th>
                    <th>Thứ tự</th>
                    <th>Trạng thái</th>
                    <th class="text-right">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>
                            <img src="{{ Storage::url($banner->image_path) }}" class="h-12 w-20 object-cover rounded border">
                        </td>
                        <td class="font-bold">{{ $banner->title ?? '(Không tiêu đề)' }}</td>
                        <td class="text-sm text-blue-600 truncate max-w-xs">{{ $banner->link_url }}</td>
                        <td>{{ $banner->order }}</td>
                        <td>
                            <span class="px-2 py-1 rounded text-xs {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $banner->is_active ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td class="text-right">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-ghost text-sm">Sửa</a>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn chắc chắn muốn xoá?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline text-sm ml-2">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-gray-500">Chưa có banner nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $banners->links() }}
    </div>
</div>
@endsection

