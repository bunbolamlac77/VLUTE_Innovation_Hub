@extends('layouts.main')

@section('title', 'Các cuộc thi của tôi')

@section('content')
  <section class="container py-8">
    <h1 class="text-2xl font-extrabold mb-4">Các cuộc thi của tôi</h1>

    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-300 bg-emerald-50 text-emerald-800 px-4 py-3">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="mb-4 rounded-xl border border-rose-300 bg-rose-50 text-rose-800 px-4 py-3">{{ session('error') }}</div>
    @endif
    @if (session('info'))
      <div class="mb-4 rounded-xl border border-sky-300 bg-sky-50 text-sky-800 px-4 py-3">{{ session('info') }}</div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-card">
      <div class="p-6">
        <form method="GET" class="mb-4 grid gap-3 md:flex md:items-center md:justify-between">
          <div class="flex items-center gap-3">
            <label class="text-sm text-slate-600">Trạng thái</label>
            <select name="status" class="rounded-md border-slate-300 text-sm" onchange="this.form.submit()">
              <option value="" {{ ($filters['status'] ?? null) === null ? 'selected' : '' }}>Tất cả</option>
              <option value="open" {{ ($filters['status'] ?? '') === 'open' ? 'selected' : '' }}>Đang mở</option>
              <option value="closed" {{ ($filters['status'] ?? '') === 'closed' ? 'selected' : '' }}>Đã đóng/Hết hạn</option>
            </select>
          </div>
          <div class="flex items-center gap-3">
            <label class="text-sm text-slate-600">Sắp xếp</label>
            <select name="sort" class="rounded-md border-slate-300 text-sm" onchange="this.form.submit()">
              <option value="recent" {{ ($filters['sort'] ?? 'recent') === 'recent' ? 'selected' : '' }}>Mới nhất</option>
              <option value="deadline_asc" {{ ($filters['sort'] ?? '') === 'deadline_asc' ? 'selected' : '' }}>Hạn chót tăng dần</option>
              <option value="deadline_desc" {{ ($filters['sort'] ?? '') === 'deadline_desc' ? 'selected' : '' }}>Hạn chót giảm dần</option>
            </select>
          </div>
        </form>

        @if ($registrations->count() > 0)
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
              <thead class="bg-slate-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tên cuộc thi</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tên nhóm</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Trạng thái</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Hạn chót</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Hành động</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-slate-200">
                @foreach ($registrations as $reg)
                  @php
                    $competition = $reg->competition;
                    $end = $competition->end_date;
                    $isOpen = $competition->status === 'open' && (!$end || $end->isFuture());
                    $submitted = (int) ($reg->submissions_count ?? 0);
                  @endphp
                  <tr>
                    <td class="px-6 py-4 text-sm">
                      <div class="font-semibold text-slate-900">{{ $competition->title }}</div>
                      @if ($submitted > 0)
                        <div class="mt-1 text-xs text-emerald-700 inline-flex items-center gap-1 bg-emerald-50 border border-emerald-200 rounded-full px-2 py-0.5">Đã nộp: {{ $submitted }}</div>
                      @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $reg->team_name ?? '(Cá nhân)' }}</td>
                    <td class="px-6 py-4 text-sm">
                      <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $competition->status === 'open' ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-700' }}">{{ $competition->status }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                      @if ($end)
                        <div>{{ $end->format('d/m/Y H:i') }}</div>
                        <div class="text-xs text-slate-400">{{ $isOpen ? 'Còn ' . $end->diffForHumans(null, true) : 'Đã kết thúc ' . $end->diffForHumans() }}</div>
                      @else
                        <span class="text-xs text-slate-400">Chưa đặt hạn</span>
                      @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                      @if ($isOpen)
                        <a href="{{ route('competitions.submit.create', $reg->id) }}" class="text-brand-navy hover:brightness-110 font-bold">{{ $submitted > 0 ? 'Nộp thêm' : 'Nộp bài ngay' }}</a>
                      @else
                        <span class="inline-flex items-center text-slate-400">{{ $competition->status === 'open' ? 'Hết hạn' : 'Đã đóng' }}</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="mt-4">{{ $registrations->links() }}</div>
        @else
          <div class="text-center py-16">
            <div class="text-2xl font-semibold mb-2">Bạn chưa đăng ký cuộc thi nào</div>
            <p class="text-slate-500 mb-6">Khám phá các cuộc thi và sự kiện đang mở để tham gia và nộp bài dự thi.</p>
            <a href="{{ route('competitions.index') }}" class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-5 py-2.5 font-bold hover:brightness-110">Khám phá cuộc thi</a>
          </div>
        @endif
      </div>
    </div>
  </section>
@endsection

