@extends('layouts.main')

@section('title', 'Hàng chờ Phản biện - VLUTE Innovation Hub')

@section('content')
    {{-- Breadcrumb --}}
    <section class="container" style="padding: 24px 0 16px;">
        <nav style="display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 14px;">
            <a href="/" style="color: var(--brand-navy);">Trang chủ</a>
            <span>/</span>
            <span>Hàng chờ phản biện</span>
        </nav>
    </section>

    {{-- Header --}}
    <section class="container" style="padding: 16px 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
            <div>
                <h1 style="margin: 0 0 4px; font-size: 32px; line-height: 1.3; color: #0f172a;">Hàng chờ Phản biện</h1>
                <p style="margin: 0; font-size: 16px; color: var(--muted);">Danh sách các ý tưởng đang chờ được xử lý.</p>
            </div>
        </div>
    </section>

    {{-- Filter Section --}}
    <section class="container" style="padding: 24px 0 16px;">
        <form method="GET" action="{{ route('manage.review-queue.index') }}" class="filter-section">
            <div style="display: flex; gap: 16px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                        Lọc theo trạng thái
                    </label>
                    <select name="status" id="status"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 15px; background: #fff;">
                        <option value="">Tất cả trạng thái</option>

                        <option value="submitted_center" {{ request('status') == 'submitted_center' ? 'selected' : '' }}>
                            Đã nộp (TTĐMST)
                        </option>
                        <option value="needs_change_center" {{ request('status') == 'needs_change_center' ? 'selected' : '' }}>
                            Cần chỉnh sửa (TTĐMST)
                        </option>
                        <option value="submitted_board" {{ request('status') == 'submitted_board' ? 'selected' : '' }}>
                            Đã nộp (BGH)
                        </option>
                        <option value="needs_change_board" {{ request('status') == 'needs_change_board' ? 'selected' : '' }}>
                            Cần chỉnh sửa (BGH)
                        </option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-weight: 700;">
                    Lọc
                </button>
                @if (request()->has('status') && request('status') != '')
                    <a href="{{ route('manage.review-queue.index') }}" class="btn btn-ghost"
                        style="padding: 12px 24px; font-weight: 700;">
                        Xóa bộ lọc
                    </a>
                @endif
            </div>
        </form>
    </section>

    {{-- Ideas Table --}}
    <section class="container" style="padding: 16px 0 64px;">
        <div class="card">
            <div class="card-body" style="padding: 24px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border);">
                            <th style="padding: 16px 12px; text-align: left; font-weight: 600; color: #0f172a; font-size: 14px;">Tiêu đề</th>
                            <th style="padding: 16px 12px; text-align: left; font-weight: 600; color: #0f172a; font-size: 14px;">Chủ sở hữu</th>
                            <th style="padding: 16px 12px; text-align: left; font-weight: 600; color: #0f172a; font-size: 14px;">Khoa</th>
                            <th style="padding: 16px 12px; text-align: left; font-weight: 600; color: #0f172a; font-size: 14px;">Trạng thái</th>
                            <th style="padding: 16px 12px; text-align: left; font-weight: 600; color: #0f172a; font-size: 14px;">Ngày cập nhật</th>
                            <th style="padding: 16px 12px; text-align: left; font-weight: 600; color: #0f172a; font-size: 14px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ideas as $idea)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 16px 12px;">
                                    <div style="font-weight: 600; color: #0f172a; margin-bottom: 4px;">
                                        {{ Str::limit($idea->title, 50) }}
                                    </div>
                                    @if ($idea->category)
                                        <span class="tag" style="font-size: 12px;">
                                            {{ $idea->category->name }}
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 16px 12px;">
                                    <div style="font-weight: 600; color: #0f172a;">{{ $idea->owner->name }}</div>
                                    <div style="font-size: 12px; color: var(--muted);">{{ $idea->owner->email }}</div>
                                </td>
                                <td style="padding: 16px 12px; font-size: 14px; color: var(--muted);">
                                    {{ $idea->faculty->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 16px 12px;">
                                    @php
                                        $statusLabels = [
                                            
                                            
                                            'submitted_center' => ['label' => 'Chờ duyệt (TT)', 'color' => '#3b82f6'],
                                            'needs_change_center' => ['label' => 'Cần sửa (TT)', 'color' => '#f59e0b'],
                                            'submitted_board' => ['label' => 'Chờ duyệt (BGH)', 'color' => '#3b82f6'],
                                            'needs_change_board' => ['label' => 'Cần sửa (BGH)', 'color' => '#f59e0b'],
                                        ];
                                        $statusInfo = $statusLabels[$idea->status] ?? ['label' => $idea->status, 'color' => '#6b7280'];
                                    @endphp
                                    <span class="tag"
                                        style="background: {{ $statusInfo['color'] }}15; color: {{ $statusInfo['color'] }}; border-color: {{ $statusInfo['color'] }}30;">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </td>
                                <td style="padding: 16px 12px; color: var(--muted); font-size: 14px;">
                                    {{ $idea->updated_at->format('d/m/Y H:i') }}
                                </td>
                                <td style="padding: 16px 12px;">
                                    <a href="{{ route('manage.review.form', $idea->id) }}" class="btn btn-primary"
                                        style="padding: 8px 16px; font-size: 14px; font-weight: 600;">
                                        Xem & Duyệt
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 48px; text-align: center; color: var(--muted);">
                                    <div style="font-size: 48px; margin-bottom: 16px;">🎉</div>
                                    <h3 style="margin: 0 0 8px; color: #0f172a;">Không có ý tưởng nào</h3>
                                    <p style="margin: 0;">Hiện tại không có ý tưởng nào trong hàng chờ của bạn.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($ideas->hasPages())
                    <div style="margin-top: 24px; display: flex; justify-content: center;">
                        {{ $ideas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
