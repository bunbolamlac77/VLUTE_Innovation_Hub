<div class="card">
  <div class="card-title">Người dùng</div>

  <form class="filter-bar" method="GET" action="{{ route('admin.home') }}">
    <input type="hidden" name="tab" value="users">
    <input class="ipt" type="text" name="q" placeholder="Tìm email hoặc tên..." value="{{ $filters['q'] ?? '' }}">
    <select class="sel" name="role">
      <option value="">-- Vai trò --</option>
      @foreach (['student', 'staff', 'center', 'board', 'enterprise', 'admin'] as $r)
        <option value="{{ $r }}" @selected(($filters['role'] ?? '') === $r)>{{ \App\Models\User::roleLabel($r) }}</option>
      @endforeach
    </select>
    <select class="sel" name="status">
      <option value="">-- Trạng thái duyệt --</option>
      <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Chờ duyệt</option>
      <option value="approved" @selected(($filters['status'] ?? '') === 'approved')>Đã duyệt</option>
    </select>
    <button class="btn btn-ghost" type="submit">Lọc</button>
  </form>

  <div class="table-wrap">
    <table class="tbl">
      <thead>
        <tr>
          <th>Email</th>
          <th>Họ tên</th>
          <th>Vai trò</th>
          <th>Trạng thái</th>
          <th class="text-center">Đổi vai</th>
          <th class="text-center">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($users as $u)
          @php
            $domain = str($u->email)->after('@')->lower()->toString();
            // Chỉ cho phép đổi vai cho staff/center từ domain vlute.edu.vn
            // Sinh viên (student) và doanh nghiệp (enterprise) không được đổi vai
            $canChangeRole = $domain === 'vlute.edu.vn' && in_array($u->role, ['staff', 'center', 'board'], true);
            $allowed = $canChangeRole ? ['staff', 'center', 'board'] : [];
          @endphp
          <tr>
            <td class="font-medium">{{ $u->email }}</td>
            <td>{{ $u->name ?? '—' }}</td>
            <td><span class="chip">{{ $u->role_label }}</span></td>
            <td class="status-cell">
              <span
                class="badge {{ $u->email_verified_at ? 'ok' : 'warn' }}">{{ $u->email_verified_at ? 'Đã xác thực' : 'Chưa xác thực' }}</span>
              <span
                class="badge {{ $u->approval_status === 'approved' ? 'ok' : 'warn' }}">{{ $u->approval_status === 'approved' ? 'Đã duyệt' : 'Chưa duyệt' }}</span>
              @if(!$u->is_active)
                <span class="badge warn">🔒 Đã khóa</span>
              @endif
            </td>
            <td class="text-center">
              @if($canChangeRole && !empty($allowed))
                <form class="inline-flex gap-2" method="POST" action="{{ route('admin.users.role', $u) }}">
                  @csrf
                  <select class="sel" name="role">
                    @foreach ($allowed as $r)
                      <option value="{{ $r }}" @selected($u->role === $r)>{{ \App\Models\User::roleLabel($r) }}</option>
                    @endforeach
                  </select>
                  <button class="btn btn-primary" type="submit">Lưu</button>
                </form>
              @else
                <span class="text-muted" style="color: #9ca3af; font-size: 0.875rem;">—</span>
              @endif
            </td>
            <td class="text-center">
              <div class="inline-flex gap-2">
                @if($u->is_active)
                  <form method="POST" action="{{ route('admin.users.lock', $u) }}"
                    onsubmit="return confirm('Khóa tài khoản này?')">
                    @csrf
                    <button class="btn btn-ghost" type="submit" title="Khóa tài khoản">🔒</button>
                  </form>
                @else
                  <form method="POST" action="{{ route('admin.users.unlock', $u) }}">
                    @csrf
                    <button class="btn btn-ghost" type="submit" title="Mở khóa tài khoản">🔓</button>
                  </form>
                @endif
                <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                  onsubmit="return confirm('Bạn có chắc muốn XÓA tài khoản này? Hành động này không thể hoàn tác!')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger" type="submit" title="Xóa tài khoản">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="empty">Không có dữ liệu</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($users, 'links'))
    <div class="mt-4">{{ $users->links() }}</div>
  @endif
</div>