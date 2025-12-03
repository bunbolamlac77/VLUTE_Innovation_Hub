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

  <div class="card-body">
    <form class="filter-bar" method="GET" action="{{ route('admin.home') }}">
      <input type="hidden" name="tab" value="ideas">
      <input class="ipt" type="text" name="q" placeholder="Tìm tiêu đề..." value="{{ $ideaFilters['q'] ?? '' }}">
      <select class="sel" name="status">
        <option value="">-- Trạng thái --</option>
        @foreach (['draft'=>'Bản nháp','submitted_for_review'=>'Chờ duyệt','needs_change_by_staff'=>'GV yêu cầu sửa','approved_by_staff'=>'GV đã duyệt','submitted_for_approval'=>'Chờ TTDV duyệt','needs_change_by_center'=>'TTDV yêu cầu sửa','approved_by_center'=>'TTDV đã duyệt','approved_final'=>'Đã duyệt (công khai)','rejected'=>'Bị từ chối'] as $v=>$text)
          <option value="{{ $v }}" @selected(($ideaFilters['status'] ?? '')===$v)>{{ $text }}</option>
        @endforeach
      </select>
      <select class="sel" name="reviewer_id">
        <option value="">-- Reviewer --</option>
        @foreach ($reviewers as $r)
          <option value="{{ $r->id }}" @selected(($ideaFilters['reviewer_id'] ?? '')==(string)$r->id)>{{ $r->name }}</option>
        @endforeach
      </select>
      <button class="btn btn-ghost" type="submit">Lọc</button>
      @if(!empty($ideaFilters['q']) || !empty($ideaFilters['status']) || !empty($ideaFilters['reviewer_id']))
        <a class="btn btn-ghost" href="{{ route('admin.home', ['tab'=>'ideas']) }}">Xóa lọc</a>
      @endif
    </form>
  </div>

  <div class="table-wrap">
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
          @php
            $status = $idea->status;
            $label = $statusMap[$status] ?? $status;
            $badgeClass = match(true) {
              $status === 'approved_final' => 'badge-green',
              $status === 'rejected' => 'badge-red',
              str_starts_with($status, 'needs_change') => 'badge-amber',
              str_starts_with($status, 'submitted') || str_contains($status,'approved_by') => 'badge-blue',
              default => 'badge-amber',
            };
          @endphp
          <tr>
            <td class="font-medium max-w-[420px] truncate">
              @php($detailUrl = $idea->status === 'approved_final' ? route('ideas.show', $idea->slug) : route('manage.review.form', $idea))
              <a href="{{ $detailUrl }}" target="_blank" rel="noopener" class="text-slate-900 hover:underline">
                {{ $idea->title }}
              </a>
            </td>
            <td><span class="badge {{ $badgeClass }}">{{ $label }}</span></td>
            <td>{{ $idea->owner?->name ?? '—' }}</td>
            <td>
              @if ($idea->reviewAssignments->isNotEmpty())
                {{ $idea->reviewAssignments->map(fn($as) => $as->reviewer->name)->join(', ') }}
              @else
                —
              @endif
            </td>
            <td class="text-center">
              <div class="ideas-actions">
                <form class="inline-flex gap-2 justify-end" method="POST" action="{{ route('admin.ideas.status', $idea) }}">
                  @csrf
                  <select class="sel-sm" name="status">
                    @foreach ($adminStatusOptions as $value => $label)
                      <option value="{{ $value }}" @selected($idea->status === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                  <button class="btn btn-ghost btn-xs" type="submit">Đổi trạng thái</button>
                </form>
                <form class="inline-flex gap-2 justify-end" method="POST" action="{{ route('admin.ideas.reviewer', $idea) }}">
                  @csrf
                  <select class="sel-sm" name="reviewer_id">
                    <option value="">-- Chọn reviewer --</option>
                    @foreach ($reviewers as $r)
                      <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                  </select>
                  <button class="btn btn-primary btn-xs" type="submit">Gán reviewer</button>
                </form>
              </div>
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
