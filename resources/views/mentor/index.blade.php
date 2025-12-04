@extends('layouts.main')

@section('title', 'Dự án đang hướng dẫn - VLUTE Innovation Hub')

@section('content')
  {{-- Breadcrumb --}}
  <section class="container pt-6 pb-4">
    <nav class="text-sm text-slate-500 flex items-center gap-2">
      <a href="/" class="text-brand-navy font-semibold">Trang chủ</a>
      <span>/</span>
      <span>Dự án đang hướng dẫn</span>
    </nav>
  </section>

  {{-- Header --}}
  <section class="container py-4">
    <div class="flex items-start justify-between gap-4 flex-wrap">
      <div>
        <h1 class="m-0 text-2xl font-extrabold text-slate-900">Dự án đang hướng dẫn</h1>
        <p class="m-0 mt-1 text-slate-500">Danh sách các ý tưởng mà bạn đang là giảng viên hướng dẫn.</p>
      </div>
    </div>
  </section>

  {{-- Table --}}
  <section class="container pb-16">
    <div class="card">
      <div class="card-body">
                    @if($mentoredIdeas->isEmpty())
          <div class="text-center py-16">
            <div class="text-2xl font-semibold mb-2">Chưa có dự án nào bạn đang hướng dẫn</div>
            <p class="text-slate-500">Khi sinh viên mời bạn làm mentor và bạn chấp nhận, dự án sẽ xuất hiện tại đây.</p>
          </div>
                    @else
                        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
              <thead class="bg-slate-50">
                                    <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase tracking-wider">Tên dự án</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase tracking-wider">Sinh viên thực hiện</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase tracking-wider">Khoa</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase tracking-wider">Danh mục</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase tracking-wider">Trạng thái</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-slate-600 uppercase tracking-wider">Hành động</th>
                                    </tr>
                                </thead>
              <tbody class="bg-white divide-y divide-slate-200">
                                    @foreach($mentoredIdeas as $idea)
                  @php
                    $statusMap = [
                      'draft' => ['label' => 'Bản nháp', 'class' => ''],
                      'submitted_center' => ['label' => 'Chờ duyệt (TT)', 'class' => 'badge-blue'],
                      'needs_change_center' => ['label' => 'Cần sửa (TT)', 'class' => 'badge-amber'],
                      'approved_center' => ['label' => 'Đã duyệt (TT)', 'class' => 'badge-green'],
                      'submitted_board' => ['label' => 'Chờ duyệt (BGH)', 'class' => 'badge-blue'],
                      'needs_change_board' => ['label' => 'Cần sửa (BGH)', 'class' => 'badge-amber'],
                      'approved_board' => ['label' => 'Đã duyệt (BGH)', 'class' => 'badge-green'],
                    ];
                    $statusInfo = $statusMap[$idea->status] ?? ['label' => $idea->status, 'class' => ''];
                  @endphp
                                        <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">
                      <div class="font-semibold">{{ $idea->title }}</div>
                      @if ($idea->category)
                        <div class="mt-1 text-xs text-slate-500">Danh mục: {{ $idea->category->name }}</div>
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                      <div class="font-medium">{{ $idea->owner->name }}</div>
                      <div class="text-xs text-slate-500">{{ $idea->owner->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $idea->faculty->name ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $idea->category->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                      <span class="badge {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                      <a href="{{ route('my-ideas.show', $idea->id) }}" class="btn btn-primary btn-sm">Vào cố vấn</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $mentoredIdeas->links() }}
                        </div>
                    @endif
                </div>
            </div>
  </section>
@endsection
