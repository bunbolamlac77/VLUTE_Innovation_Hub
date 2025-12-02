@extends('layouts.admin-shell')

@section('main')
<div class="card">
  <div class="card-title flex items-center justify-between">
    <span>Tin tức khoa học</span>
    <a class="btn btn-primary" href="{{ route('admin.news.create') }}">+ Thêm tin</a>
  </div>

  @if (session('status'))
    <div class="flash-success">{{ session('status') }}</div>
  @endif

  <div class="table-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Tiêu đề</th>
          <th>Ngày đăng</th>
          <th>Tác giả</th>
          <th>Danh mục</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($news as $n)
          <tr>
            <td class="font-medium">{{ $n->title }}</td>
            <td>{{ optional($n->published_date)->format('d/m/Y') }}</td>
            <td>{{ $n->author ?? '—' }}</td>
            <td>{{ $n->category ?? '—' }}</td>
          </tr>
          <tr>
            <td colspan="4" class="text-right">
              <div class="flex gap-2 justify-end">
                <a class="btn btn-ghost" href="{{ route('scientific-news.show', $n) }}" target="_blank">Xem</a>
                <a class="btn btn-ghost" href="{{ route('admin.news.edit', $n) }}">Sửa</a>
                <form method="POST" action="{{ route('admin.news.destroy', $n) }}" onsubmit="return confirm('Xoá bản tin này?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">Xoá</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="empty">Chưa có bản tin nào.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    @if(method_exists($news, 'links'))
      {{ $news->links() }}
    @endif
  </div>
</div>
@endsection

