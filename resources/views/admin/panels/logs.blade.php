<div class="card">
  <div class="card-title">Nhật ký</div>

  <form class="filter-bar" method="GET" action="{{ route('admin.home') }}">
    <input type="hidden" name="tab" value="logs">
    <select class="sel" name="action">
      <option value="">-- Hành động --</option>
      @foreach (['user_registered','email_verified','user_approved','user_rejected','role_changed'] as $a)
        <option value="{{ $a }}" @selected(($logFilters['action']??'')===$a)>{{ $a }}</option>
      @endforeach
    </select>
    <input class="ipt" type="text" name="q" placeholder="Tìm trong meta..." value="{{ $logFilters['q'] ?? '' }}">
    <button class="btn btn-ghost" type="submit">Lọc</button>
  </form>

  <div class="table-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Thời gian</th>
          <th>Hành động</th>
          <th>Người thực hiện</th>
          <th>Meta</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($logs as $log)
          <tr>
            <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
            <td><span class="chip">{{ $log->action }}</span></td>
            <td>{{ $log->actor?->name ?? 'system' }}</td>
            <td><pre class="pre">{{ is_array($log->meta)?json_encode($log->meta, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE):$log->meta }}</pre></td>
          </tr>
        @empty
          <tr><td colspan="4" class="empty">Chưa có log</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($logs,'links'))
    <div class="mt-4">{{ $logs->links() }}</div>
  @endif
</div>