@extends('layouts.main')

@section('title', 'Ý tưởng của tôi - VLUTE Innovation Hub')

@section('content')
    {{-- Hero Section --}}
    <section class="hero"
        style="background: linear-gradient(120deg, rgba(7, 26, 82, 0.9), rgba(10, 168, 79, 0.85)), url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat;">
        <div class="container" style="padding: 56px 0">
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 24px;">
                <div style="display: flex; align-items: center; gap: 24px;">
                    <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
                        style="height: 80px; width: auto; object-fit: contain; background: rgba(255, 255, 255, 0.95); padding: 8px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);" />
                    <div>
                        <h1 style="color: #fff; margin: 0 0 8px; font-size: 40px;">Ý tưởng của tôi</h1>
                        <p class="sub" style="max-width: 820px; color: rgba(255, 255, 255, 0.92); font-size: 18px; margin: 0;">
                            Quản lý và theo dõi các ý tưởng của bạn
                        </p>
                    </div>
                </div>
                <a href="{{ route('my-ideas.create') }}" class="btn btn-primary"
                    style="padding: 14px 28px; font-weight: 700; background: rgba(255, 255, 255, 0.95); color: var(--brand-navy); border: none;">
                    + Tạo ý tưởng mới
                </a>
            </div>
        </div>
    </section>

    {{-- Ideas List --}}
    <section class="container" style="padding: 32px 0 64px;">
        @if ($ideas->count() > 0)
            <div class="grid-3">
                @foreach ($ideas as $idea)
                    <article class="card" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                        onclick="window.location.href='{{ route('my-ideas.show', $idea->id) }}'"
                        onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.transform=''; this.style.boxShadow='';">
                        <div class="card-body" style="padding: 24px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                <div style="flex: 1;">
                                    <div style="display: flex; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;">
                                        @php
                                            $statusLabels = [
                                                'draft' => ['label' => 'Nháp', 'color' => '#6b7280'],
                                                'submitted_gv' => ['label' => 'Đã nộp (GV)', 'color' => '#3b82f6'],
                                                'needs_change_gv' => ['label' => 'Cần chỉnh sửa (GV)', 'color' => '#f59e0b'],
                                                'approved_gv' => ['label' => 'Đã duyệt (GV)', 'color' => '#10b981'],
                                                'submitted_center' => ['label' => 'Đã nộp (TTĐMST)', 'color' => '#3b82f6'],
                                                'needs_change_center' => ['label' => 'Cần chỉnh sửa (TTĐMST)', 'color' => '#f59e0b'],
                                                'approved_center' => ['label' => 'Đã duyệt (TTĐMST)', 'color' => '#10b981'],
                                                'submitted_board' => ['label' => 'Đã nộp (BGH)', 'color' => '#3b82f6'],
                                                'needs_change_board' => ['label' => 'Cần chỉnh sửa (BGH)', 'color' => '#f59e0b'],
                                                'approved_final' => ['label' => 'Đã duyệt (BGH)', 'color' => '#10b981'],
                                                'rejected' => ['label' => 'Từ chối', 'color' => '#ef4444'],
                                            ];
                                            $statusInfo = $statusLabels[$idea->status] ?? ['label' => $idea->status, 'color' => '#6b7280'];
                                        @endphp
                                        <span class="tag"
                                            style="background: {{ $statusInfo['color'] }}15; color: {{ $statusInfo['color'] }}; border-color: {{ $statusInfo['color'] }}30;">
                                            {{ $statusInfo['label'] }}
                                        </span>
                                        @if ($idea->visibility === 'public')
                                            <span class="tag" style="background: rgba(10, 168, 79, 0.1); color: var(--brand-green);">
                                                Công khai
                                            </span>
                                        @elseif ($idea->visibility === 'team_only')
                                            <span class="tag" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                                                Chỉ nhóm
                                            </span>
                                        @else
                                            <span class="tag" style="background: rgba(107, 114, 128, 0.1); color: #6b7280;">
                                                Riêng tư
                                            </span>
                                        @endif
                                    </div>
                                    <h5 style="margin: 0 0 8px; font-size: 18px; line-height: 1.4; color: #0f172a;">
                                        {{ $idea->title }}
                                    </h5>
                                    <p
                                        style="color: #6b7280; font-size: 14px; margin: 0 0 12px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ Str::limit($idea->description, 120) }}
                                    </p>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 12px; border-top: 1px solid var(--border);">
                                <div style="font-size: 12px; color: var(--muted);">
                                    <strong style="color: #0f172a;">Người tạo:</strong> {{ $idea->owner->name }}
                                    @if ($idea->members->count() > 0)
                                        <br>
                                        <strong style="color: #0f172a;">Thành viên:</strong> {{ $idea->members->count() }}
                                    @endif
                                </div>
                                <div style="font-size: 12px; color: var(--muted);">
                                    {{ $idea->created_at->format('d/m/Y') }}
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
                <div style="font-size: 64px; margin-bottom: 16px;">💡</div>
                <h3 style="margin: 0 0 12px; color: #0f172a;">Bạn chưa có ý tưởng nào</h3>
                <p style="color: var(--muted); margin: 0 0 24px;">
                    Hãy bắt đầu tạo ý tưởng đầu tiên của bạn!
                </p>
                <a href="{{ route('my-ideas.create') }}" class="btn btn-primary">
                    + Tạo ý tưởng mới
                </a>
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Đánh dấu menu active nếu có
            const myIdeasLink = document.querySelector('a[href="/ideas/my"]');
            if (myIdeasLink) {
                myIdeasLink.classList.add('active');
            }
        });
    </script>
@endpush

