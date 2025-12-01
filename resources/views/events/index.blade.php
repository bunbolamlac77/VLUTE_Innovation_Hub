@extends('layouts.main')

@section('title', 'Cuộc thi & Sự kiện - VLUTE Innovation Hub')

@section('content')
    {{-- Hero Section --}}
    <section class="relative text-white">
        <div class="absolute inset-0 bg-cover bg-center filter brightness-50 saturate-90 blur-sm"
            style="background-image: url('{{ asset('images/panel-truong.jpg') }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-navy/70 to-brand-green/50"></div>
        <div class="relative">
            <div class="container py-14">
                <div class="flex items-center gap-6 mb-2">
                    <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
                        class="h-20 w-auto object-contain bg-white/95 p-2 rounded-lg shadow" />
                    <div>
                        <h1 class="m-0 text-4xl font-extrabold">Cuộc thi & Sự kiện</h1>
                        <p class="max-w-3xl text-white/90 text-lg m-0">Danh sách các cuộc thi đang mở và sắp diễn ra dành
                            cho sinh viên VLUTE.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter & Search Section --}}
    <section class="container py-8">
        <form method="GET" action="{{ route('events.index') }}" id="filterForm">
            <div class="grid md:grid-cols-2 gap-4 mb-4">
                {{-- Search Box --}}
                <div>
                    <label for="q" class="block mb-2 font-semibold text-slate-900">Tìm kiếm</label>
                    <input type="text" name="q" id="q" value="{{ $q }}" placeholder="Nhập từ khóa (tiêu đề, mô tả)..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
                {{-- Status Filter --}}
                <div>
                    <label for="status" class="block mb-2 font-semibold text-slate-900">Trạng thái</label>
                    <select name="status" id="status"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Đang mở</option>
                        <option value="ended" @selected($status === 'ended')>Đã kết thúc</option>
                    </select>
                </div>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-5 py-3 font-bold border border-transparent hover:brightness-95">🔍
                    Tìm kiếm</button>
                @if ($q || $status)
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-3 font-bold hover:bg-slate-50">✕
                        Xóa bộ lọc</a>
                @endif
                <div class="ml-auto text-slate-500 text-sm">Tìm thấy <strong
                        class="text-slate-900">{{ $competitions->total() }}</strong> cuộc thi</div>
            </div>
        </form>
    </section>

    {{-- Competitions Grid --}}
    <section class="container pb-16">
        @if ($competitions->count() > 0)
            <div class="grid md:grid-cols-4 gap-4" id="eventsGrid">
                @foreach ($competitions as $c)
                    @php
                        $isOpen = $c->status === 'open' && (!$c->end_date || $c->end_date->isFuture());
                        $hasRegistered = auth()->check() && $c->registrations()->where('user_id', auth()->id())->exists();
                    @endphp
                    <article class="flex flex-col border border-slate-200 bg-white rounded-2xl shadow-card overflow-hidden">
                        <div class="h-[160px] bg-gradient-to-br from-indigo-200 to-emerald-200"></div>
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex items-center justify-between text-slate-500 text-xs mb-1.5">
                                <span
                                    class="inline-block bg-brand-gray-100 text-slate-700 px-2.5 py-1 rounded-full">{{ strtoupper($c->status) }}</span>
                                <span>{{ optional($c->end_date)->format('d/m/Y H:i') }}</span>
                            </div>
                            <h5 class="font-bold text-slate-900 leading-snug mb-2">
                                <a href="{{ route('competitions.show', $c->slug) }}"
                                    class="no-underline text-slate-900">{{ $c->title }}</a>
                            </h5>
                            <div class="mt-auto pt-3 flex items-center gap-2">
                                <a class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50"
                                    href="{{ route('competitions.show', $c->slug) }}">Xem chi tiết</a>
                                @if ($isOpen)
                                    @auth
                                        @if ($hasRegistered)
                                            <a class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-3 py-1.5 text-sm font-bold border border-transparent hover:brightness-95"
                                                href="{{ route('my-competitions.index') }}">Đã đăng ký</a>
                                        @else
                                            <form method="POST" action="{{ route('competitions.register', $c) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-3 py-1.5 text-sm font-bold border border-transparent hover:brightness-95">Đăng
                                                    ký</button>
                                            </form>
                                        @endif
                                    @else
                                        <a class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-3 py-1.5 text-sm font-bold border border-transparent hover:brightness-95"
                                            href="{{ route('login') }}">Đăng nhập để đăng ký</a>
                                    @endauth
                                @else
                                    <button
                                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-400 cursor-not-allowed"
                                        disabled>Đã đóng</button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($competitions->hasPages())
                <div class="mt-12 flex justify-center">{{ $competitions->links() }}</div>
            @endif
        @else
            <div class="text-center bg-white border border-slate-200 rounded-2xl p-16">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="m-0 mb-3 text-slate-900">Không tìm thấy cuộc thi nào</h3>
                <p class="text-slate-500 m-0 mb-6">
                    @if ($q || $status)
                        Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.
                    @else
                        Hiện tại chưa có cuộc thi nào đang mở.
                    @endif
                </p>
                @if ($q || $status)
                    <a href="{{ route('events.index') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-5 py-3 font-bold border border-transparent hover:brightness-95">Xem
                        mặc định (Đang mở)</a>
                @endif
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sel = document.getElementById('status');
            if (sel) sel.addEventListener('change', function () { document.getElementById('filterForm').submit(); });
        });
    </script>
@endpush