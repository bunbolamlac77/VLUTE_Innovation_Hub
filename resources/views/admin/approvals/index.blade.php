@extends('layouts.app') {{-- hoặc layout khác bạn đang dùng --}}

@section('content')
<div class="container" style="max-width:1000px">
  <h1 style="margin:8px 0 16px;font-weight:800">Phê duyệt tài khoản</h1>

  @if (session('status'))
    <div style="margin:10px 0;padding:10px 12px;border-radius:10px;background:#ecfdf5;color:#065f46;font-weight:700">
      {{ session('status') }}
    </div>
  @endif

  @if (empty($pending) || count($pending) === 0)
    <p>Hiện không có tài khoản chờ phê duyệt.</p>
  @else
    <div style="overflow:auto;border:1px solid #e5e7eb;border-radius:12px">
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr style="background:#f9fafb">
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">Email</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">Họ tên</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">Gợi ý role</th>
            <th style="text-align:left;padding:10px;border-bottom:1px solid #e5e7eb">Chọn role</th>
            <th style="padding:10px;border-bottom:1px solid #e5e7eb">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pending as $u)
            <tr>
              <td style="padding:10px">{{ $u['email'] }}</td>
              <td style="padding:10px">{{ $u['name'] }}</td>
              <td style="padding:10px"><code>{{ $u['suggested'] }}</code></td>
              <td style="padding:10px">
                <form action="{{ route('admin.approvals.updateRole', $u['id']) }}" method="POST" style="display:flex;gap:8px;align-items:center">
                  @csrf
                  <select name="role" style="padding:8px 10px;border:1px solid #e5e7eb;border-radius:8px">
                    @foreach (['student','staff','enterprise','admin'] as $r)
                      <option value="{{ $r }}" @selected(($u['role'] ?? $u['suggested']) === $r)>{{ ucfirst($r) }}</option>
                    @endforeach
                  </select>
                  <button type="submit" style="padding:8px 12px;border:0;border-radius:8px;background:#e5e7eb;cursor:pointer">Cập nhật</button>
                </form>
              </td>
              <td style="padding:10px;white-space:nowrap">
                <form action="{{ route('admin.approvals.approve', $u['id']) }}" method="POST" style="display:inline-block;margin-right:6px">
                  @csrf
                  <input type="hidden" name="role" value="{{ $u['role'] ?: $u['suggested'] }}">
                  <button type="submit" style="padding:8px 12px;border:0;border-radius:8px;background:#22c55e;color:#fff;cursor:pointer">Duyệt</button>
                </form>
                <form action="{{ route('admin.approvals.reject', $u['id']) }}" method="POST" style="display:inline-block">
                  @csrf
                  <button type="submit" style="padding:8px 12px;border:0;border-radius:8px;background:#ef4444;color:#fff;cursor:pointer">Từ chối</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif

  <div style="margin-top:14px">
    <a href="{{ url('/admin') }}">← Về trang Admin</a>
  </div>
</div>
@endsection