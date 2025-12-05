@extends('layouts.admin-shell')

@section('main')
<div class="card">
  <div class="card-title flex items-center justify-between">
    <span>Cuộc thi</span>
    <a class="btn btn-primary" href="{{ route('admin.competitions.create') }}">+ Tạo cuộc thi</a>
  </div>

  @if (session('status'))
    <div class="flash-success">{{ session('status') }}</div>
  @endif

  <div class="table-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Tiêu đề</th>
          <th>Trạng thái</th>
          <th>Bắt đầu</th>
          <th>Kết thúc</th>
          <th>Slug</th>
          <th class="text-right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($competitions as $c)
          <tr>
            <td class="font-medium">{{ $c->title }}</td>
            <td><span class="chip">{{ $c->status }}</span></td>
            <td>{{ optional($c->start_date)->format('d/m/Y H:i') }}</td>
            <td>{{ optional($c->end_date)->format('d/m/Y H:i') }}</td>
            <td class="text-xs text-gray-500">{{ $c->slug }}</td>
            <td class="text-right">
              <div class="flex gap-2 justify-end">
                <a class="btn btn-ghost" href="{{ route('competitions.show', $c->slug) }}" target="_blank">Xem</a>
                <a class="btn btn-ghost" href="{{ route('admin.competitions.edit', $c) }}">Sửa</a>
                <a class="btn btn-ghost text-green-600 hover:text-green-900" href="{{ route('admin.competitions.export', $c->id) }}" title="Xuất danh sách đăng ký">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                  Excel
                </a>
                <form method="POST" action="{{ route('admin.competitions.destroy', $c) }}" onsubmit="return confirm('Xoá cuộc thi này?');" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">Xoá</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="empty">Chưa có cuộc thi nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $competitions->links() }}
  </div>
</div>
@endsection

