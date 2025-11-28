@extends('layouts.main')

@section('title', 'Cuộc thi & Sự kiện - VLUTE Innovation Hub')

@section('content')
    {{-- Hero Section --}}
    <section class="hero"
        style="background: linear-gradient(120deg, rgba(7, 26, 82, 0.9), rgba(10, 168, 79, 0.85)), url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat;">
        <div class="container" style="padding: 56px 0">
            <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 16px;">
                <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
                    style="height: 80px; width: auto; object-fit: contain; background: rgba(255, 255, 255, 0.95); padding: 8px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);" />
                <div>
                    <h1 style="color: #fff; margin: 0 0 8px; font-size: 40px;">Cuộc thi & Sự kiện</h1>
                    <p class="sub" style="max-width: 820px; color: rgba(255, 255, 255, 0.92); font-size: 18px; margin: 0;">
                        Danh sách các cuộc thi đang mở và sắp diễn ra dành cho sinh viên VLUTE.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter & Search Section --}}
    <section class="container" style="padding: 32px 0 16px;">
        <form method="GET" action="{{ route('events.index') }}" id="filterForm" class="filter-section">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; margin-bottom: 16px;">
                {{-- Search Box --}}
                <div>
                    <label for="q" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                        Tìm kiếm
                    </label>
                    <input type="text" name="q" id="q" value="{{ $q }}"
                        placeholder="Nhập từ khóa (tiêu đề, mô tả)..."
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 15px;">
                </div>

                {{-- Status Filter --}}
                <div>
                    <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                        Trạng thái
                    </label>
                    <select name="status" id="status"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 15px; background: #fff;">
                        <option value="">Đang mở</option>
                        <option value="ended" @selected($status === 'ended')>Đã kết thúc</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: flex-end;">
                <button type="submit" class="btn btn-primary"
                    style="padding: 12px 24px; font-weight: 700; cursor: pointer;">
                    🔍 Tìm kiếm
                </button>
                @if ($q || $status)
                    <a href="{{ route('events.index') }}" class="btn btn-ghost"
                        style="padding: 12px 24px; font-weight: 700; border-color: var(--brand-navy); color: var(--brand-navy);">
                        ✕ Xóa bộ lọc
                    </a>
                @endif
                <div style="margin-left: auto; color: var(--muted); font-size: 14px;">
                    Tìm thấy <strong>{{ $competitions->total() }}</strong> cuộc thi
                </div>
            </div>
        </form>
    </section>

    {{-- Competitions Grid --}}
    <section class="container" style="padding: 16px 0 64px;">
        @if ($competitions->count() > 0)
            <div class="grid-4" id="eventsGrid">
                @foreach ($competitions as $c)
                    @php
                        $isOpen = $c->status === 'open' && (!$c->end_date || $c->end_date->isFuture());
                        $hasRegistered = auth()->check() && $c->registrations()->where('user_id', auth()->id())->exists();
                    @endphp
                    <article class="item">
                        <div class="thumb"></div>
                        <div class="meta">
                            <div class="row">
                                <span class="tag">{{ strtoupper($c->status) }}</span>
                                <span style="font-size:12px;color:#6b7280">{{ optional($c->end_date)->format('d/m/Y H:i') }}</span>
                            </div>
                            <h5>
                                <a href="{{ route('competitions.show', $c->slug) }}" style="text-decoration:none; color:#0f172a;">
                                    {{ $c->title }}
                                </a>
                            </h5>
                            <div class="actions">
                                <a class="btn btn-ghost" href="{{ route('competitions.show', $c->slug) }}">Xem chi tiết</a>
                                @if ($isOpen)
                                    @auth
                                        @if ($hasRegistered)
                                            <a class="btn btn-primary" href="{{ route('my-competitions.index') }}">Đã đăng ký</a>
                                        @else
                                            <form method="POST" action="{{ route('competitions.register', $c) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Đăng ký</button>
                                            </form>
                                        @endif
                                    @else
                                        <a class="btn btn-primary" href="{{ route('login') }}">Đăng nhập để đăng ký</a>
                                    @endauth
                                @else
                                    <button class="btn btn-ghost" disabled>Đã đóng</button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($competitions->hasPages())
                <div style="margin-top: 48px; display: flex; justify-content: center;">
                    {{ $competitions->links() }}
                </div>
            @endif
        @else
            <div class="card" style="text-align: center; padding: 64px 32px;">
                <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                <h3 style="margin: 0 0 12px; color: #0f172a;">Không tìm thấy cuộc thi nào</h3>
                <p style="color: var(--muted); margin: 0 0 24px;">
                    @if ($q || $status)
                        Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.
                    @else
                        Hiện tại chưa có cuộc thi nào đang mở.
                    @endif
                </p>
                @if ($q || $status)
                    <a href="{{ route('events.index') }}" class="btn btn-primary">
                        Xem mặc định (Đang mở)
                    </a>
                @endif
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Đánh dấu menu active
            const link = document.querySelector('nav.menu a[data-key="events"]');
            if (link) link.classList.add('active');

            // Tự submit khi chọn trạng thái
            const sel = document.getElementById('status');
            if (sel) sel.addEventListener('change', function () {
                document.getElementById('filterForm').submit();
            });
        });
    </script>
@endpush

