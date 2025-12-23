@extends('layouts.main')

@section('title', 'Cuộc thi & Sự kiện')

@section('content')
  <section class="container py-8">
    <h1 class="text-3xl font-extrabold mb-6">Các Cuộc thi & Sự kiện</h1>

        @if ($competitions->count() > 0)
      <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($competitions as $competition)
          @php
            $end = $competition->end_date;
            $isOpen = $competition->status === 'open' && (!$end || $end->isFuture());
          @endphp
          <article class="border border-slate-200 rounded-2xl overflow-hidden shadow-card bg-white">
            @php
                $imageUrl = null;
                // Sử dụng banner_url (link ảnh)
                if ($competition->banner_url) {
                    $imageUrl = trim($competition->banner_url);
                    // Nếu không phải URL đầy đủ hoặc đường dẫn tuyệt đối, giả sử là đường dẫn tương đối
                    if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://') && !str_starts_with($imageUrl, '/')) {
                        $imageUrl = asset('storage/' . ltrim($imageUrl, '/'));
                    }
                }
            @endphp
            @if($imageUrl)
            <a href="{{ route('competitions.show', $competition->slug) }}" class="block h-48 bg-slate-100 overflow-hidden">
              <img src="{{ $imageUrl }}" alt="{{ $competition->title }}" class="w-full h-full object-cover" onerror="this.parentElement.style.display='none';" />
            </a>
            @endif
            <div class="p-5">
              <div class="flex items-start justify-between gap-3 mb-2">
                <h3 class="text-base font-semibold leading-tight">
                <a href="{{ route('competitions.show', $competition->slug) }}" class="no-underline text-slate-900">{{ $competition->title }}</a>
                            </h3>
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $isOpen ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">{{ $competition->status }}</span>
              </div>

              <div class="text-xs text-slate-500 mb-4">
                @if ($competition->start_date)
                  <span>Bắt đầu: {{ $competition->start_date->format('d/m/Y') }}</span>
                @endif
                @if ($competition->end_date)
                  <span class="ml-3">Hạn chót: <strong>{{ $competition->end_date->format('d/m/Y') }}</strong></span>
                  <span class="ml-2 text-slate-400">{{ $isOpen ? 'Còn ' . $competition->end_date->diffForHumans(null, true) : 'Đã kết thúc ' . $competition->end_date->diffForHumans() }}</span>
                @else
                  <span class="ml-3 text-slate-400">Chưa đặt hạn</span>
                @endif
              </div>

              <a href="{{ route('competitions.show', $competition->slug) }}" class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-4 py-2 font-bold border border-slate-200 hover:bg-slate-50">{{ $isOpen ? 'Xem & đăng ký' : 'Xem chi tiết' }}</a>
                        </div>
          </article>
                @endforeach
            </div>

      <div class="mt-8">{{ $competitions->links() }}</div>
        @else
      <div class="bg-white border border-slate-200 rounded-2xl p-8 text-center">
        <h3 class="text-xl font-semibold mb-2">Chưa có cuộc thi nào</h3>
        <p class="text-slate-500">Vui lòng quay lại sau để cập nhật các cuộc thi mới nhất.</p>
      </div>
        @endif
  </section>
@endsection
