@php
$statusMap = [
    'draft' => 'Bản nháp',
    'submitted_for_review' => 'Chờ duyệt',
    'needs_change_by_staff' => 'GV yêu cầu sửa',
    'approved_by_staff' => 'GV đã duyệt',
    'submitted_for_approval' => 'Chờ TTDV duyệt',
    'needs_change_by_center' => 'TTDV yêu cầu sửa',
    'approved_by_center' => 'TTDV đã duyệt',
    'approved_final' => 'Đã duyệt (công khai)',
    'rejected' => 'Bị từ chối',
];

// For the admin status change dropdown, we use a simpler set
$adminStatusOptions = [
    'submitted_for_review' => 'Chờ duyệt',
    'approved_final' => 'Duyệt (công khai)',
    'rejected' => 'Từ chối',
];
@endphp

<div class="card">
  <div class="card-title">Ý tưởng (MVP)</div>

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
            <td><span class="chip">{{ $statusMap[$idea->status] ?? $idea->status }}</span></td>
            <td>{{ $idea->owner?->name ?? '—' }}</td>
            <td>
              @if ($idea->reviewAssignments->isNotEmpty())
                {{ $idea->reviewAssignments->map(fn($as) => $as->reviewer->name)->join(', ') }}
              @else
                —
              @endif
            </td>
            <td class="text-center">
              <form class="inline-flex gap-2" method="POST" action="{{ route('admin.ideas.status', $idea) }}">
                @csrf
                <select class="sel" name="status">
                  @foreach ($adminStatusOptions as $value => $label)
                    <option value="{{ $value }}" @selected($idea->status === $value)>{{ $label }}</option>
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

    <div class="mt-4">
        {{ $ideas->links() }}
    </div>
  </div>
</div>
