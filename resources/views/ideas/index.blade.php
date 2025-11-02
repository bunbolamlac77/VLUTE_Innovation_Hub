@extends('layouts.main')

@section('title', 'Ngân hàng Ý tưởng - VLUTE Innovation Hub')

@section('content')
    {{-- Hero Section --}}
    <section class="hero"
        style="background: linear-gradient(120deg, rgba(7, 26, 82, 0.9), rgba(10, 168, 79, 0.85)), url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat;">
        <div class="container" style="padding: 56px 0">
            <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 16px;">
                <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
                    style="height: 80px; width: auto; object-fit: contain; background: rgba(255, 255, 255, 0.95); padding: 8px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);" />
                <div>
                    <h1 style="color: #fff; margin: 0 0 8px; font-size: 40px;">Ngân hàng Ý tưởng</h1>
                    <p class="sub" style="max-width: 820px; color: rgba(255, 255, 255, 0.92); font-size: 18px; margin: 0;">
                        Khám phá các ý tưởng đổi mới sáng tạo đã được Ban Giám hiệu duyệt và cho phép công khai từ sinh viên
                        VLUTE
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter & Search Section --}}
    <section class="container" style="padding: 32px 0 16px;">
        <form method="GET" action="{{ route('ideas.index') }}" id="filterForm" class="filter-section">
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                {{-- Search Box --}}
                <div>
                    <label for="search" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                        Tìm kiếm
                    </label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        placeholder="Nhập từ khóa tìm kiếm..."
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 15px;">
                </div>

                {{-- Faculty Filter --}}
                <div>
                    <label for="faculty" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                        Khoa
                    </label>
                    <select name="faculty" id="faculty"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 15px; background: #fff;">
                        <option value="">Tất cả khoa</option>
                        @foreach ($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ request('faculty') == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Category Filter --}}
                <div>
                    <label for="category" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                        Lĩnh vực
                    </label>
                    <select name="category" id="category"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 15px; background: #fff;">
                        <option value="">Tất cả lĩnh vực</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: flex-end;">
                <button type="submit" class="btn btn-primary"
                    style="padding: 12px 24px; font-weight: 700; cursor: pointer;">
                    🔍 Tìm kiếm
                </button>
                @if (request()->hasAny(['search', 'faculty', 'category']))
                    <a href="{{ route('ideas.index') }}" class="btn btn-ghost"
                        style="padding: 12px 24px; font-weight: 700; border-color: var(--brand-navy); color: var(--brand-navy);">
                        ✕ Xóa bộ lọc
                    </a>
                @endif
                <div style="margin-left: auto; color: var(--muted); font-size: 14px;">
                    Tìm thấy <strong>{{ $ideas->total() }}</strong> ý tưởng
                </div>
            </div>
        </form>
    </section>

    {{-- Ideas Grid --}}
    <section class="container" style="padding: 16px 0 64px;">
        @if ($ideas->count() > 0)
            <div class="grid-4" id="ideasGrid">
                @foreach ($ideas as $idea)
                    <article class="item" style="cursor: pointer;"
                        onclick="window.location.href='{{ route('ideas.show', $idea->slug) }}'">
                        <div class="thumb" style="background: linear-gradient(135deg, #93c5fd, #a7f3d0); height: 180px;">
                        </div>
                        <div class="meta">
                            <div class="row">
                                @if ($idea->faculty)
                                    <span class="tag">{{ $idea->faculty->name }}</span>
                                @else
                                    <span class="tag">Chưa phân loại</span>
                                @endif
                                @if ($idea->category)
                                    <span class="tag" style="background: rgba(10, 168, 79, 0.1); color: var(--brand-green);">
                                        {{ $idea->category->name }}
                                    </span>
                                @endif
                            </div>
                            <h5>{{ $idea->title }}</h5>
                            @if ($idea->summary)
                                <p
                                    style="color: #6b7280; font-size: 14px; margin: 8px 0 0; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $idea->summary }}
                                </p>
                            @endif
                            <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                                <div style="font-size: 12px; color: var(--muted); flex: 1;">
                                    <strong style="color: #0f172a;">Liên hệ:</strong>
                                    @php
                                        $teamEmails = collect();
                                        if ($idea->owner) {
                                            $teamEmails->push($idea->owner->email);
                                        }
                                        $memberEmails = $idea->members->filter(function ($member) {
                                            return $member->user && $member->user->email;
                                        })->pluck('user.email')->unique();
                                        $teamEmails = $teamEmails->merge($memberEmails)->unique()->take(3);
                                    @endphp
                                    @if ($teamEmails->isNotEmpty())
                                        <span style="word-break: break-all;">{{ $teamEmails->implode(', ') }}</span>
                                        @if ($memberEmails->count() + ($idea->owner ? 1 : 0) > 3)
                                            <span style="color: var(--muted);">...</span>
                                        @endif
                                    @else
                                        <span style="color: var(--muted);">Chưa có thông tin liên hệ</span>
                                    @endif
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px; margin-left: 12px; cursor: pointer;" onclick="event.stopPropagation(); likeIdea({{ $idea->id }})">
                                    @php
                                        $isLiked = auth()->check() && $idea->isLikedBy(auth()->user());
                                        $likeCount = $idea->likes_count ?? $idea->like_count ?? 0;
                                    @endphp
                                    <svg id="like-icon-{{ $idea->id }}" width="20" height="20" viewBox="0 0 24 24" fill="{{ $isLiked ? '#ef4444' : 'none' }}" stroke="{{ $isLiked ? '#ef4444' : '#6b7280' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="cursor: pointer; transition: all 0.2s;">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    <span style="font-size: 14px; color: #6b7280; font-weight: 600;" id="like-count-{{ $idea->id }}">{{ $likeCount }}</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($ideas->hasPages())
                <div style="margin-top: 48px; display: flex; justify-content: center;">
                    {{ $ideas->links() }}
                </div>
            @endif
        @else
            <div class="card" style="text-align: center; padding: 64px 32px;">
                <div style="font-size: 48px; margin-bottom: 16px;">🔍</div>
                <h3 style="margin: 0 0 12px; color: #0f172a;">Không tìm thấy ý tưởng nào</h3>
                <p style="color: var(--muted); margin: 0 0 24px;">
                    @if (request()->hasAny(['search', 'faculty', 'category']))
                        Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.
                    @else
                        Hiện tại chưa có ý tưởng nào được công khai.
                    @endif
                </p>
                @if (request()->hasAny(['search', 'faculty', 'category']))
                    <a href="{{ route('ideas.index') }}" class="btn btn-primary">
                        Xem tất cả ý tưởng
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
            const ideasLink = document.querySelector('nav.menu a[data-key="ideas"]');
            if (ideasLink) {
                ideasLink.classList.add('active');
            }

            // Auto submit form when filters change
            const facultySelect = document.getElementById('faculty');
            const categorySelect = document.getElementById('category');

            if (facultySelect) {
                facultySelect.addEventListener('change', function () {
                    document.getElementById('filterForm').submit();
                });
            }

            if (categorySelect) {
                categorySelect.addEventListener('change', function () {
                    document.getElementById('filterForm').submit();
                });
            }
        });

        // Function to handle like button click
        function likeIdea(ideaId) {
            @guest
                // Nếu chưa đăng nhập, chuyển đến trang đăng nhập
                window.location.href = '{{ route('login') }}';
                return;
            @endguest

            const likeIcon = document.getElementById('like-icon-' + ideaId);
            const likeCount = document.getElementById('like-count-' + ideaId);
            const iconPath = likeIcon.querySelector('path');
            
            // Visual feedback
            likeIcon.style.transform = 'scale(1.3)';
            setTimeout(() => {
                likeIcon.style.transform = 'scale(1)';
            }, 200);

            // Send request to update like count
            fetch(`/ideas/${ideaId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (response.status === 401) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    likeCount.textContent = data.like_count;
                    
                    // Toggle icon: filled (red) or outline
                    if (data.liked) {
                        iconPath.setAttribute('fill', '#ef4444');
                        iconPath.setAttribute('stroke', '#ef4444');
                    } else {
                        iconPath.setAttribute('fill', 'none');
                        iconPath.setAttribute('stroke', '#6b7280');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
@endpush