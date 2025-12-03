<div class="card">
  <div class="card-title">Phê duyệt tài khoản</div>

  @if ($pending->isEmpty())
    <div class="card-body">
      <div class="empty bg-slate-50 border border-slate-200 rounded-xl">Hiện không có tài khoản chờ phê duyệt.</div>
    </div>
  @else
    <div class="card-body">
      <div class="table-wrap">
        <table class="tbl">
          <thead>
            <tr>
              <th>Email</th>
              <th>Họ tên</th>
              <th>Thông tin doanh nghiệp</th>
              <th>Role gợi ý</th>
              <th>Chọn role</th>
              <th class="text-center">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($pending as $row)
              @php 
                $u = $row['model']; 
                $suggested=$row['suggested'];
                $domain = str($u->email)->after('@')->lower()->toString();
                $isEnterprise = !in_array($domain, ['vlute.edu.vn', 'st.vlute.edu.vn'], true);
              @endphp
              <tr>
                <td>
                  <div class="font-medium">{{ $u->email }}</div>
                  <div class="sub">Tạo: {{ $u->created_at?->format('d/m/Y H:i') }}</div>
                </td>
                <td>{{ $u->name ?? '—' }}</td>
                <td>
                  @if($isEnterprise && ($u->company || $u->position || $u->interest))
                    <div class="enterprise-info">
                      @if($u->company)
                        <div class="info-row">
                          <span class="info-label">Công ty:</span>
                          <span class="info-value">{{ $u->company }}</span>
                        </div>
                      @endif
                      @if($u->position)
                        <div class="info-row">
                          <span class="info-label">Vị trí:</span>
                          <span class="info-value">{{ $u->position }}</span>
                        </div>
                      @endif
                      @if($u->interest)
                        <div class="info-row">
                          <span class="info-label">Lĩnh vực:</span>
                          <span class="info-value">
                            @switch($u->interest)
                              @case('it') Công nghệ thông tin @break
                              @case('agritech') Nông nghiệp công nghệ cao @break
                              @case('mechanics') Cơ khí / Tự động hóa @break
                              @case('other') Khác @break
                              @default {{ $u->interest }}
                            @endswitch
                          </span>
                        </div>
                      @endif
                    </div>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
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
    </div>
  @endif
</div>
