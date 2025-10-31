<div class="card">
  <div class="card-title">Phê duyệt tài khoản</div>
  @if ($pending->isEmpty())
    <div class="empty">Hiện không có tài khoản chờ phê duyệt.</div>
  @else
    <div class="table-wrap">
      <table class="tbl">
        <thead>
          <tr>
            <th>Email</th>
            <th>Họ tên</th>
            <th>Role gợi ý</th>
            <th>Chọn role</th>
            <th class="text-center">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pending as $row)
            @php $u = $row['model']; $suggested=$row['suggested']; @endphp
            <tr>
              <td>
                <div class="font-medium">{{ $u->email }}</div>
                <div class="sub">Tạo: {{ $u->created_at?->format('d/m/Y H:i') }}</div>
              </td>
              <td>{{ $u->name ?? '—' }}</td>
              <td>
                <div class="chips">
                  @foreach ($suggested as $s)
                    <span class="chip">{{ \App\Models\User::roleLabel($s) }}</span>
                  @endforeach
                </div>
              </td>
              <td>
                <form class="inline-flex gap-2" method="POST" action="{{ route('admin.approvals.approve',$u) }}">
                  @csrf
                  <select name="role" class="sel">
                    @foreach ($suggested as $s)
                      <option value="{{ $s }}">{{ \App\Models\User::roleLabel($s) }}</option>
                    @endforeach
                  </select>
                  <button class="btn btn-primary" type="submit">Duyệt</button>
                </form>
              </td>
              <td class="text-center">
                <form method="POST" action="{{ route('admin.approvals.reject',$u) }}" onsubmit="return window.confirmDelete(event, 'Từ chối tài khoản này?')">
                  @csrf
                  <button class="btn btn-ghost" type="submit">Từ chối</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>