@extends('layouts.admin-shell')

@section('main')
<div class="card">
  <div class="card-title flex items-center justify-between">
    <span>Challenges (Doanh nghiệp)</span>
    <a class="btn btn-primary" href="{{ route('admin.challenges.create') }}">+ Tạo challenge</a>
  </div>

  @if (session('status'))
    <div class="flash-success">{{ session('status') }}</div>
  @endif

  <div class="table-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Tiêu đề</th>
          <th>Doanh nghiệp</th>
          <th>Trạng thái</th>
          <th>Hạn</th>
          <th class="text-right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($challenges as $c)
          <tr>
            <td class="font-medium">{{ $c->title }}</td>
            <td>{{ $c->organization->name ?? '—' }}</td>
            <td><span class="chip">{{ $c->status }}</span></td>
            <td>{{ optional($c->deadline)->format('d/m/Y H:i') }}</td>
            <td class="text-right">
              <div class="flex gap-2 justify-end">
                <a class="btn btn-ghost" href="{{ route('challenges.show', $c) }}" target="_blank">Xem</a>
                <a class="btn btn-ghost" href="{{ route('admin.challenges.edit', $c) }}">Sửa</a>
                <form method="POST" action="{{ route('admin.challenges.destroy', $c) }}" onsubmit="return confirm('Xoá challenge này?');" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">Xoá</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="empty">Chưa có challenge nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $challenges->links() }}
  </div>
</div>
@endsection

