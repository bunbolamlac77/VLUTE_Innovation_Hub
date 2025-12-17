@extends('layouts.main')

@section('title', 'Ngân hàng Ý tưởng - VLUTE Innovation Hub')

@section('content')
    {{-- Hero Section --}}
    <section class="relative text-white">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('images/panel-truong.jpg') }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-navy/90 to-brand-green/85"></div>
        <div class="relative">
            <div class="container py-14">
                <div class="flex items-center gap-6 mb-4">
                    <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
                        class="h-20 w-auto object-contain bg-white/95 p-2 rounded-lg shadow" />
                    <div>
                        <h1 class="m-0 text-4xl font-extrabold">Ngân hàng Ý tưởng</h1>
                        <p class="max-w-3xl text-white/90 text-lg m-0">Khám phá các ý tưởng đổi mới sáng tạo đã được Ban
                            Giám hiệu duyệt và cho phép công khai từ sinh viên VLUTE</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter & Search Section --}}
    <section class="container py-8">
        <form method="GET" action="{{ route('ideas.index') }}" id="filterForm">
            <div class="grid md:grid-cols-4 gap-4 mb-4">
                {{-- Search Box --}}
                <div>
                    <label for="search" class="block mb-2 font-semibold text-slate-900">Tìm kiếm</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập từ khóa tìm kiếm..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
                {{-- Faculty Filter --}}
                <div>
                    <label for="faculty" class="block mb-2 font-semibold text-slate-900">Khoa</label>
                    <select name="faculty" id="faculty"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Tất cả khoa</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ request('faculty') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Category Filter --}}
                <div>
                    <label for="category" class="block mb-2 font-semibold text-slate-900">Lĩnh vực</label>
                    <select name="category" id="category"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Tất cả lĩnh vực</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Sort Filter --}}
                <div>
                    <label for="sort" class="block mb-2 font-semibold text-slate-900">Sắp xếp</label>
                    <select name="sort" id="sort"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-[15px] bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="most_liked" {{ request('sort') == 'most_liked' ? 'selected' : '' }}>Nhiều lượt thích nhất</option>
                    </select>
                </div>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-5 py-3 font-bold border border-transparent hover:brightness-95">🔍
                    Tìm kiếm</button>
                @if (request()->hasAny(['search', 'faculty', 'category', 'sort']))
                    <a href="{{ route('ideas.index') }}"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-300 bg-white px-5 py-3 font-bold hover:bg-slate-50">✕
                        Xóa bộ lọc</a>
                @endif
                <div class="ml-auto text-slate-500 text-sm">Tìm thấy <strong
                        class="text-slate-900">{{ $ideas->total() }}</strong> ý tưởng</div>
            </div>
        </form>
    </section>

    {{-- Ideas Grid --}}
    <section class="container pb-16">
        @if ($ideas->count() > 0)
            <div class="grid md:grid-cols-4 gap-4" id="ideasGrid">
                @foreach ($ideas as $idea)
                    <article
                        class="cursor-pointer flex flex-col border border-slate-200 bg-white rounded-2xl shadow-card overflow-hidden"
                        onclick="window.location.href='{{ route('ideas.show', $idea->slug) }}'">
                        <div class="h-[180px] bg-slate-100 relative group">
                            <img src="{{ $idea->thumbnail_url }}"
                                 alt="{{ $idea->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 onerror="this.src='{{ asset('images/default-idea.png') }}'">
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <div class="flex items-center justify-between text-slate-500 text-xs mb-1.5">
                                <span
                                    class="inline-block bg-brand-gray-100 text-slate-700 px-2.5 py-1 rounded-full">{{ $idea->faculty->name ?? 'Chưa phân loại' }}</span>
                                @if ($idea->category)
                                    <span
                                        class="inline-block bg-emerald-50 text-brand-green px-2.5 py-1 rounded-full">{{ $idea->category->name }}</span>
                                @endif
                            </div>
                            <h5 class="font-bold text-slate-900 leading-snug">{{ $idea->title }}</h5>
                            @if ($idea->summary)
                                <p class="text-sm text-slate-600 mt-2 line-clamp-2">{{ $idea->summary }}</p>
                            @endif
                            <div class="mt-auto pt-3 border-t border-slate-200 flex items-center justify-between gap-3">
                                {{-- Liên hệ --}}
                                <div class="text-[12px] text-slate-500 flex-1 min-w-0">
                                    <strong class="text-slate-900">Liên hệ:</strong>
                                    @php
                                        $teamEmails = collect();
                                        if ($idea->owner) {
                                            $teamEmails->push($idea->owner->email);
                                        }
                                        $memberEmails = $idea->members->filter(fn($m) => $m->user && $m->user->email)->pluck('user.email')->unique();
                                        $teamEmails = $teamEmails->merge($memberEmails)->unique()->take(3);
                                    @endphp
                                    @if ($teamEmails->isNotEmpty())
                                        <span class="break-all">{{ $teamEmails->implode(', ') }}</span>
                                        @if ($memberEmails->count() + ($idea->owner ? 1 : 0) > 3)
                                            <span class="text-slate-400">...</span>
                                        @endif
                                    @else
                                        <span class="text-slate-400">Chưa có thông tin liên hệ</span>
                                    @endif
                                </div>
                                {{-- Like --}}
                                <button class="flex items-center gap-1.5 ml-auto"
                                    onclick="event.stopPropagation(); likeIdea({{ $idea->id }})">
                                    @php
                                        $isLiked = auth()->check() && $idea->isLikedBy(auth()->user());
                                        $likeCount = $idea->likes_count ?? $idea->like_count ?? 0;
                                    @endphp
                                    <svg id="like-icon-{{ $idea->id }}" width="20" height="20" viewBox="0 0 24 24"
                                        fill="{{ $isLiked ? '#ef4444' : 'none' }}" stroke="{{ $isLiked ? '#ef4444' : '#6b7280' }}"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                    <span id="like-count-{{ $idea->id }}"
                                        class="text-sm text-slate-500 font-semibold">{{ $likeCount }}</span>
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($ideas->hasPages())
                <div class="mt-12 flex justify-center">{{ $ideas->links() }}</div>
            @endif
        @else
            <div class="text-center bg-white border border-slate-200 rounded-2xl p-16">
                <div class="text-5xl mb-4">🔍</div>
                <h3 class="m-0 mb-3 text-slate-900">Không tìm thấy ý tưởng nào</h3>
                <p class="text-slate-500 m-0 mb-6">
                    @if (request()->hasAny(['search', 'faculty', 'category']))
                        Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.
                    @else
                        Hiện tại chưa có ý tưởng nào được công khai.
                    @endif
                </p>
                @if (request()->hasAny(['search', 'faculty', 'category']))
                    <a href="{{ route('ideas.index') }}"
                        class="inline-flex items-center gap-2 rounded-full bg-white text-brand-navy px-5 py-3 font-bold border border-transparent hover:brightness-95">Xem
                        tất cả ý tưởng</a>
                @endif
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const facultySelect = document.getElementById('faculty');
            const categorySelect = document.getElementById('category');
            const sortSelect = document.getElementById('sort');
            if (facultySelect) facultySelect.addEventListener('change', () => document.getElementById('filterForm').submit());
            if (categorySelect) categorySelect.addEventListener('change', () => document.getElementById('filterForm').submit());
            if (sortSelect) sortSelect.addEventListener('change', () => document.getElementById('filterForm').submit());
        });

        function likeIdea(ideaId) {
            @guest
                window.location.href = '{{ route('login') }}';
                return;
            @endguest

                const likeIcon = document.getElementById('like-icon-' + ideaId);
            const likeCount = document.getElementById('like-count-' + ideaId);

            likeIcon.style.transform = 'scale(1.2)';
            setTimeout(() => { likeIcon.style.transform = 'scale(1)'; }, 150);

            fetch(`/ideas/${ideaId}/like`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
                .then(r => { if (r.status === 401) { window.location.href = '{{ route('login') }}'; return; } return r.json(); })
                .then(data => {
                    if (!data) return;
                    likeCount.textContent = data.like_count;
                    const isLiked = !!data.liked;
                    likeIcon.setAttribute('fill', isLiked ? '#ef4444' : 'none');
                    likeIcon.setAttribute('stroke', isLiked ? '#ef4444' : '#6b7280');
                })
                .catch(console.error);
        }
    </script>
@endpush