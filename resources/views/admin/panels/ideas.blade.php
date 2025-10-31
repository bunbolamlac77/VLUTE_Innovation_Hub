<div class="card">
  <div class="card-title">Ý tưởng (MVP)</div>
  <div class="empty">Bạn sẽ kết nối bảng <code>ideas</code> sau. Hiện panel này hiển thị khung thao tác:</div>

  <div class="table-wrap mt-4">
    <table class="tbl">
      <thead>
        <tr>
          <th>Tiêu đề</th>
          <th>Trạng thái</th>
          <th>Chủ ý tưởng</th>
          <th>Reviewer</th>
          <th class="text-center">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($ideas as $idea)
          <tr>
            <td class="font-medium">{{ $idea->title }}</td>
            <td><span class="chip">{{ $idea->status }}</span></td>
            <td>{{ $idea->owner?->name ?? '—' }}</td>
            <td>{{ $idea->reviewer?->name ?? '—' }}</td>
            <td class="text-center">
              <form class="inline-flex gap-2" method="POST" action="{{ route('admin.ideas.status', $idea) }}">
                @csrf
                <select class="sel" name="status">
                  @foreach (['submitted','under_review','approved','rejected'] as $s)
                    <option value="{{ $s }}" @selected($idea->status===$s)>{{ $s }}</option>
                  @endforeach
                </select>
                <button class="btn btn-ghost" type="submit">Đổi trạng thái</button>
              </form>
              <form class="inline-flex gap-2 mt-2" method="POST" action="{{ route('admin.ideas.reviewer', $idea) }}">
                @csrf
                <select class="sel" name="reviewer_id">
                  <option value="">-- Chọn reviewer --</option>
                  @foreach ($reviewers as $r)
                    <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->email }})</option>
                  @endforeach
                </select>
                <button class="btn btn-primary" type="submit">Gán reviewer</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="empty">Chưa có dữ liệu</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>