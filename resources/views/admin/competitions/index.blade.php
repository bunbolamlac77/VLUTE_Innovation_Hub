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
              <a class="btn btn-ghost" href="{{ route('competitions.show', $c->slug) }}" target="_blank">Xem</a>
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

