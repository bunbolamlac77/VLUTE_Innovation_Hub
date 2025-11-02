@extends('layouts.main')

@section('title', $idea->title . ' - Ngân hàng Ý tưởng')

@section('content')
    {{-- Breadcrumb --}}
    <section class="container" style="padding: 24px 0 16px;">
        <nav style="display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 14px;">
            <a href="/" style="color: var(--brand-navy);">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('ideas.index') }}" style="color: var(--brand-navy);">Ngân hàng Ý tưởng</a>
            <span>/</span>
            <span>{{ \Illuminate\Support\Str::limit($idea->title, 50) }}</span>
        </nav>
    </section>

    {{-- Idea Detail --}}
    <section class="container" style="padding: 16px 0 64px;">
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px;">
            {{-- Main Content --}}
            <div>
                <div class="card">
                    <div class="card-body" style="padding: 32px;">
                        <div style="display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap;">
                            @if ($idea->faculty)
                                <span class="tag">{{ $idea->faculty->name }}</span>
                            @endif
                            @if ($idea->category)
                                <span class="tag" style="background: rgba(10, 168, 79, 0.1); color: var(--brand-green);">
                                    {{ $idea->category->name }}
                                </span>
                            @endif
                        </div>

                        <h1 style="margin: 0 0 16px; font-size: 32px; line-height: 1.3; color: #0f172a;">
                            {{ $idea->title }}
                        </h1>

                        @if ($idea->summary)
                            <div
                                style="background: var(--brand-gray-50); padding: 20px; border-radius: 12px; margin-bottom: 24px;">
                                <h3 style="margin: 0 0 12px; font-size: 16px; color: #0f172a; font-weight: 700;">
                                    Tóm tắt
                                </h3>
                                <p style="margin: 0; color: #374151; line-height: 1.7;">
                                    {{ $idea->summary }}
                                </p>
                            </div>
                        @endif

                        @if ($idea->description)
                            <div style="margin-bottom: 24px;">
                                <h3 style="margin: 0 0 12px; font-size: 18px; color: #0f172a; font-weight: 700;">
                                    Mô tả ý tưởng
                                </h3>
                                <div style="color: #374151; line-height: 1.8; white-space: pre-wrap;">
                                    {{ $idea->description }}
                                </div>
                            </div>
                        @endif

                        @if ($idea->content)
                            <div>
                                <h3 style="margin: 0 0 12px; font-size: 18px; color: #0f172a; font-weight: 700;">
                                    Nội dung chi tiết
                                </h3>
                                <div style="color: #374151; line-height: 1.8; white-space: pre-wrap;">
                                    {!! nl2br(e($idea->content)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside>
                <div class="card" style="position: sticky; top: 100px;">
                    <div class="card-body" style="padding: 24px;">
                        <h3 style="margin: 0 0 20px; font-size: 18px; color: #0f172a; font-weight: 700;">
                            Thông tin liên hệ
                        </h3>

                        <div style="margin-bottom: 24px;">
                            <h4 style="margin: 0 0 8px; font-size: 14px; color: var(--muted); font-weight: 600;">
                                Người tạo
                            </h4>
                            <div style="color: #0f172a; font-weight: 600;">
                                {{ $idea->owner->name }}
                            </div>
                            <a href="mailto:{{ $idea->owner->email }}"
                                style="color: var(--brand-navy); text-decoration: underline; font-size: 14px;">
                                {{ $idea->owner->email }}
                            </a>
                        </div>

                        @if ($idea->members->count() > 0)
                            <div>
                                <h4 style="margin: 0 0 12px; font-size: 14px; color: var(--muted); font-weight: 600;">
                                    Thành viên nhóm ({{ $idea->members->count() }})
                                </h4>
                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    @foreach ($idea->members as $member)
                                        <div>
                                            <div style="color: #0f172a; font-weight: 600; font-size: 14px;">
                                                {{ $member->user->name }}
                                                @if ($member->role_in_team)
                                                    <span
                                                        style="color: var(--muted); font-weight: 400;">({{ $member->role_in_team }})</span>
                                                @endif
                                            </div>
                                            <a href="mailto:{{ $member->user->email }}"
                                                style="color: var(--brand-navy); text-decoration: underline; font-size: 13px;">
                                                {{ $member->user->email }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                            @php
                                $isLiked = auth()->check() && $idea->isLikedBy(auth()->user());
                                $likeCount = $idea->likes_count ?? $idea->like_count ?? 0;
                            @endphp
                            <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 16px; padding: 16px; background: #f9fafb; border-radius: 8px; cursor: pointer;"
                                onclick="likeIdea({{ $idea->id }})">
                                <svg id="like-icon-{{ $idea->id }}" width="28" height="28" viewBox="0 0 24 24"
                                    fill="{{ $isLiked ? '#ef4444' : 'none' }}"
                                    stroke="{{ $isLiked ? '#ef4444' : '#6b7280' }}" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" style="cursor: pointer; transition: all 0.2s;">
                                    <path
                                        d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                    </path>
                                </svg>
                                <span style="font-size: 18px; color: #0f172a; font-weight: 700;"
                                    id="like-count-{{ $idea->id }}">{{ $likeCount }}</span>
                                <span style="font-size: 14px; color: #6b7280;">tim</span>
                            </div>
                            <a href="{{ route('ideas.index') }}" class="btn btn-primary" style="width: 100%;">
                                ← Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ideasLink = document.querySelector('nav.menu a[data-key="ideas"]');
            if (ideasLink) {
                ideasLink.classList.add('active');
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