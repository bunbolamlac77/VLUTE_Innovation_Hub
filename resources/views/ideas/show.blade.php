@extends('layouts.main')

@section('title', $idea->title . ' - Ngân hàng Ý tưởng')

@section('content')
    {{-- Breadcrumb --}}
    <section class="container py-6">
        <nav class="flex items-center gap-2 text-sm text-slate-500">
            <a href="/" class="text-brand-navy font-semibold">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('ideas.index') }}" class="text-brand-navy font-semibold">Ngân hàng Ý tưởng</a>
            <span>/</span>
            <span>{{ \Illuminate\Support\Str::limit($idea->title, 50) }}</span>
        </nav>
    </section>

    {{-- Idea Detail --}}
    <section class="container pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-body">
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if ($idea->faculty)
                                <span class="tag">{{ $idea->faculty->name }}</span>
                            @endif
                            @if ($idea->category)
                                <span class="tag bg-green-50 text-brand-green">{{ $idea->category->name }}</span>
                            @endif
                        </div>

                        <h1 class="text-3xl font-extrabold leading-tight text-slate-900 mb-4">{{ $idea->title }}</h1>

                        @if ($idea->summary)
                            <div class="bg-brand-gray-50 p-5 rounded-xl mb-6">
                                <h3 class="text-base font-bold text-slate-900 mb-2">Tóm tắt</h3>
                                <p class="text-slate-700 leading-7 break-words">{{ $idea->summary }}</p>
                            </div>
                        @endif

                        @if ($idea->description)
                            <div class="mb-6">
                                <h3 class="text-lg font-bold text-slate-900 mb-2">Mô tả ý tưởng</h3>
                                <div class="prose-content break-words whitespace-pre-wrap">{{ $idea->description }}</div>
                            </div>
                        @endif

                        @if ($idea->content)
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-2">Nội dung chi tiết</h3>
                                <div class="prose-content break-words whitespace-pre-wrap">{!! nl2br(e($idea->content)) !!}</div>
                            </div>
                        @endif

                        @if ($idea->attachments && $idea->attachments->count() > 0)
                            <div class="mt-8">
                                <h3 class="text-lg font-bold text-slate-900 mb-3">Tệp đính kèm ({{ $idea->attachments->count() }})</h3>
                                <p class="text-xs text-slate-500 mb-3">Một số tệp có thể yêu cầu đăng nhập để tải về.</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($idea->attachments as $file)
                                        @php
                                            $url = route('attachments.download', $file->id);
                                            $mime = strtolower($file->mime_type ?? '');
                                            $isImage = str_starts_with($mime, 'image/');
                                            $isPdf = $mime === 'application/pdf' || str_ends_with(strtolower($file->filename ?? ''), '.pdf');
                                            $ext = pathinfo($file->filename ?? '', PATHINFO_EXTENSION);
                                            $sizeKb = $file->size ? round($file->size / 1024, 1) : null;
                                        @endphp
                                        <a href="{{ $url }}" class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl hover:bg-slate-50">
                                            @if ($isPdf)
                                                <div class="w-12 h-12 grid place-items-center rounded bg-red-50 text-red-600 font-bold">PDF</div>
                                            @elseif ($isImage)
                                                <div class="w-12 h-12 grid place-items-center rounded bg-emerald-50 text-emerald-600 font-bold">IMG</div>
                                            @else
                                                <div class="w-12 h-12 grid place-items-center rounded bg-slate-100 text-slate-600">📄</div>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="font-semibold text-sm truncate max-w-[220px]">{{ $file->filename }}</div>
                                                <div class="text-xs text-slate-500 uppercase">{{ $ext ?: 'file' }}@if($sizeKb) • {{ $sizeKb }} KB @endif</div>
                                            </div>
                                            <div class="ml-auto text-sm text-brand-navy font-semibold">Tải</div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside>
                <div class="card sticky top-24">
                    <div class="card-body">
                        <h3 class="text-lg font-bold text-slate-900 mb-5">Thông tin liên hệ</h3>

                        <div class="mb-6">
                            <h4 class="text-[13px] text-slate-500 font-semibold mb-1">Người tạo</h4>
                            <div class="font-semibold text-slate-900">{{ $idea->owner->name }}</div>
                            <a href="mailto:{{ $idea->owner->email }}" class="text-brand-navy underline text-sm">{{ $idea->owner->email }}</a>
                        </div>

                        @if ($idea->members->count() > 0)
                            <div>
                                <h4 class="text-[13px] text-slate-500 font-semibold mb-3">Thành viên nhóm ({{ $idea->members->count() }})</h4>
                                <div class="flex flex-col gap-3">
                                    @foreach ($idea->members as $member)
                                        <div>
                                            <div class="text-slate-900 font-semibold text-sm">
                                                {{ $member->user->name }}
                                                @if ($member->role_in_team)
                                                    <span class="text-slate-500 font-normal">({{ $member->role_in_team }})</span>
                                                @endif
                                            </div>
                                            <a href="mailto:{{ $member->user->email }}" class="text-brand-navy underline text-[13px]">{{ $member->user->email }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-6 pt-6 border-t border-slate-200">
                            @php
                                $isLiked = auth()->check() && $idea->isLikedBy(auth()->user());
                                $likeCount = $idea->likes_count ?? $idea->like_count ?? 0;
                            @endphp
                            <div class="flex items-center justify-center gap-3 mb-4 p-4 bg-slate-50 rounded-lg cursor-pointer select-none"
                                 onclick="likeIdea({{ $idea->id }})">
                                <svg id="like-icon-{{ $idea->id }}" width="28" height="28" viewBox="0 0 24 24"
                                     fill="{{ $isLiked ? '#ef4444' : 'none' }}"
                                     stroke="{{ $isLiked ? '#ef4444' : '#6b7280' }}" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" style="transition: transform .2s ease;">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                                <span id="like-count-{{ $idea->id }}" class="text-lg font-bold text-slate-900">{{ $likeCount }}</span>
                                <span class="text-sm text-slate-500">tim</span>
                            </div>
                            <a href="{{ route('ideas.index') }}" class="btn btn-primary w-full">← Quay lại danh sách</a>
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
            const ideasLink = document.querySelector('#menuMain a[data-key="ideas"]');
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
            setTimeout(() => { likeIcon.style.transform = 'scale(1)'; }, 200);

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
                    if (data.liked) {
                        iconPath.setAttribute('fill', '#ef4444');
                        iconPath.setAttribute('stroke', '#ef4444');
                    } else {
                        iconPath.setAttribute('fill', 'none');
                        iconPath.setAttribute('stroke', '#6b7280');
                    }
                }
            })
            .catch(error => { console.error('Error:', error); });
        }
    </script>
@endpush