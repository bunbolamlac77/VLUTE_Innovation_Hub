<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Hàng chờ Phản biện Ý tưởng
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chủ sở hữu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khoa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày nộp</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($ideas as $idea)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $idea->title }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $idea->owner->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $idea->faculty->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $idea->updated_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <a href="{{ route('my-ideas.show', $idea) }}" class="text-indigo-600 hover:text-indigo-900">Chi tiết</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-sm text-gray-500 text-center">Không có ý tưởng nào cần phản biện.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $ideas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@extends('layouts.main')

@section('title', 'Hàng chờ Phản biện - VLUTE Innovation Hub')

@section('content')
    {{-- Hero Section --}}
    <section class="hero"
        style="background: linear-gradient(120deg, rgba(7, 26, 82, 0.9), rgba(10, 168, 79, 0.85)), url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat;">
        <div class="container" style="padding: 56px 0">
            <div style="display: flex; align-items: center; gap: 24px;">
                <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
                    style="height: 80px; width: auto; object-fit: contain; background: rgba(255, 255, 255, 0.95); padding: 8px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);" />
                <div>
                    <h1 style="color: #fff; margin: 0 0 8px; font-size: 40px;">Hàng chờ Phản biện</h1>
                    <p class="sub" style="max-width: 820px; color: rgba(255, 255, 255, 0.92); font-size: 18px; margin: 0;">
                        Danh sách các ý tưởng đang chờ được phản biện và duyệt
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter Section --}}
    <section class="container" style="padding: 32px 0 16px;">
        <form method="GET" action="{{ route('manage.review-queue.index') }}" class="filter-section">
            <div style="display: flex; gap: 16px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label for="status" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                        Lọc theo trạng thái
                    </label>
                    <select name="status" id="status"
                        style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 15px; background: #fff;">
                        <option value="">Tất cả trạng thái</option>
                        <option value="submitted_gv" {{ request('status') == 'submitted_gv' ? 'selected' : '' }}>
                            Đã nộp (GV)
                        </option>
                        <option value="needs_change_gv" {{ request('status') == 'needs_change_gv' ? 'selected' : '' }}>
                            Cần chỉnh sửa (GV)
                        </option>
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
                    🔍 Lọc
                </button>
                @if (request()->has('status'))
                    <a href="{{ route('manage.review-queue.index') }}" class="btn btn-ghost"
                        style="padding: 12px 24px; font-weight: 700;">
                        ✕ Xóa bộ lọc
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
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #0f172a;">Tiêu đề</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #0f172a;">Chủ sở hữu</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #0f172a;">Khoa</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #0f172a;">Trạng thái</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #0f172a;">Ngày nộp</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; color: #0f172a;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ideas as $idea)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 12px;">
                                    <div style="font-weight: 600; color: #0f172a; margin-bottom: 4px;">
                                        {{ Str::limit($idea->title, 50) }}
                                    </div>
                                    @if ($idea->category)
                                        <span class="tag" style="font-size: 12px;">
                                            {{ $idea->category->name }}
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 12px;">
                                    <div style="font-weight: 600; color: #0f172a;">{{ $idea->owner->name }}</div>
                                    <div style="font-size: 12px; color: var(--muted);">{{ $idea->owner->email }}</div>
                                </td>
                                <td style="padding: 12px;">
                                    {{ $idea->faculty->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px;">
                                    @php
                                        $statusLabels = [
                                            'submitted_gv' => ['label' => 'Đã nộp (GV)', 'color' => '#3b82f6'],
                                            'needs_change_gv' => ['label' => 'Cần chỉnh sửa (GV)', 'color' => '#f59e0b'],
                                            'submitted_center' => ['label' => 'Đã nộp (TTĐMST)', 'color' => '#3b82f6'],
                                            'needs_change_center' => ['label' => 'Cần chỉnh sửa (TTĐMST)', 'color' => '#f59e0b'],
                                            'submitted_board' => ['label' => 'Đã nộp (BGH)', 'color' => '#3b82f6'],
                                            'needs_change_board' => ['label' => 'Cần chỉnh sửa (BGH)', 'color' => '#f59e0b'],
                                        ];
                                        $statusInfo = $statusLabels[$idea->status] ?? ['label' => $idea->status, 'color' => '#6b7280'];
                                    @endphp
                                    <span class="tag"
                                        style="background: {{ $statusInfo['color'] }}15; color: {{ $statusInfo['color'] }}; border-color: {{ $statusInfo['color'] }}30;">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </td>
                                <td style="padding: 12px; color: var(--muted); font-size: 14px;">
                                    {{ $idea->updated_at->format('d/m/Y H:i') }}
                                </td>
                                <td style="padding: 12px;">
                                    <a href="{{ route('my-ideas.show', $idea->id) }}" class="btn btn-primary"
                                        style="padding: 8px 16px; font-size: 14px; font-weight: 600;">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 32px; text-align: center; color: var(--muted);">
                                    <div style="font-size: 48px; margin-bottom: 16px;">📋</div>
                                    <h3 style="margin: 0 0 8px; color: #0f172a;">Không có ý tưởng nào cần phản biện</h3>
                                    <p style="margin: 0;">Tất cả ý tưởng đã được xử lý hoặc chưa có ý tưởng nào được nộp.</p>
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