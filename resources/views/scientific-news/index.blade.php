@extends('layouts.main')

@section('title', 'Bản tin Nghiên cứu Khoa học - VLUTE Innovation Hub')

@section('content')
    {{-- Hero Section --}}
    <section class="relative text-white">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/panel-truong.jpg') }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-navy/90 to-brand-green/85"></div>
        <div class="relative">
            <div class="container py-14">
                <div class="flex items-center gap-6 mb-2">
                    <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
                        class="h-16 w-auto object-contain bg-white/95 p-2 rounded-lg shadow" />
                    <div>
                        <h1 class="m-0 text-4xl font-extrabold">Bản tin Nghiên cứu Khoa học</h1>
                        <p class="max-w-3xl text-white/90 text-lg m-0">Cập nhật nghiên cứu, phát hiện và công nghệ mới nhất trong và ngoài trường</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter & Search Section --}}
    <section class="container py-8">
        <form method="GET" action="{{ route('scientific-news.index') }}" id="filterForm">
            <div class="grid md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="search" class="block mb-2 font-semibold text-slate-900">Tìm kiếm</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="Nhập từ khóa..."
                           class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
                <div>
                    <label for="category" class="block mb-2 font-semibold text-slate-900">Chủ đề</label>
                    <select name="category" id="category"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Tất cả chủ đề</option>
                        @foreach(($categories ?? []) as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:self-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-5 py-3 font-bold border border-transparent hover:brightness-95">🔍 Tìm kiếm</button>
                    @if (request()->hasAny(['search','category']))
                        <a href="{{ route('scientific-news.index') }}"
                           class="ml-2 inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-3 font-bold hover:bg-slate-50">✕ Xóa bộ lọc</a>
                    @endif
                </div>
            </div>
        </form>
    </section>

    {{-- News Grid --}}
    <section class="container pb-16">
        @if ($news->count() > 0)
            <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($news as $item)
                    <article class="flex flex-col border border-slate-200 bg-white rounded-2xl shadow-card overflow-hidden">
                        <div class="h-[180px] bg-slate-100">
                            @if ($item->image_url)
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" />
                            @endif
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex items-center justify-between text-slate-500 text-xs mb-1.5">
                                <span class="inline-block bg-brand-gray-100 text-slate-700 px-2.5 py-1 rounded-full">{{ $item->category ?? 'Chưa phân loại' }}</span>
                                <span>{{ optional($item->published_date)->format('d/m/Y') }}</span>
                            </div>
                            <h5 class="font-bold text-slate-900 leading-snug mb-1 line-clamp-2">{{ $item->title }}</h5>
                            @if ($item->description)
                                <p class="text-sm text-slate-600 mt-1 line-clamp-3">{{ $item->description }}</p>
                            @endif
                            <div class="mt-auto pt-3 flex gap-2">
                                <a class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50"
                                   href="{{ route('scientific-news.show', $item) }}">Đọc thêm</a>
                                @if ($item->source)
                                    <a class="inline-flex items-center gap-2 rounded-full text-indigo-600 px-3 py-1.5 text-sm font-semibold hover:bg-indigo-50"
                                       href="{{ $item->source }}" target="_blank" rel="noopener">Nguồn</a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($news->hasPages())
                <div class="mt-10 flex justify-center">{{ $news->links() }}</div>
            @endif
        @else
            <div class="text-center bg-white border border-slate-200 rounded-2xl p-16">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="m-0 mb-3 text-slate-900">Chưa có bản tin nào</h3>
                <p class="text-slate-500 m-0">Hãy thử thay đổi bộ lọc hoặc quay lại sau.</p>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('category');
        if (categorySelect) categorySelect.addEventListener('change', () => document.getElementById('filterForm').submit());
    });
</script>
@endpush
