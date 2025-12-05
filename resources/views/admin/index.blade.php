@extends('layouts.admin-shell')

@section('main')
{{-- Tabs: 1 trang duy nhất, đổi panel theo query `?tab=` --}}
<div class="admin-tabs">
    <a href="{{ route('admin.home', ['tab' => 'approvals']) }}"
        class="admin-tab {{ $tab === 'approvals' ? 'is-active' : '' }}">Phê duyệt</a>
    <a href="{{ route('admin.home', ['tab' => 'users']) }}" class="admin-tab {{ $tab === 'users' ? 'is-active' : '' }}">Người
        dùng</a>
    <a href="{{ route('admin.home', ['tab' => 'ideas']) }}" class="admin-tab {{ $tab === 'ideas' ? 'is-active' : '' }}">Ý
        tưởng</a>
    <a href="{{ route('admin.home', ['tab' => 'taxonomies']) }}"
        class="admin-tab {{ $tab === 'taxonomies' ? 'is-active' : '' }}">Phân loại</a>
    <a href="{{ route('admin.home', ['tab' => 'logs']) }}" class="admin-tab {{ $tab === 'logs' ? 'is-active' : '' }}">Nhật
        ký</a>
    <a href="{{ route('admin.banners.index') }}" class="admin-tab" style="margin-left: auto;">🎨 Quản lý Banner</a>
</div>

@if (session('status'))
    <div class="flash-success">{{ session('status') }}</div>
@endif

{{-- Panel: Phê duyệt --}}
@if ($tab === 'approvals')
    @include('admin.panels.approvals', ['pending' => $pending])
@endif

{{-- Panel: Người dùng --}}
@if ($tab === 'users')
    @include('admin.panels.users', ['users' => $users, 'filters' => $filters])
@endif

{{-- Panel: Ý tưởng (MVP / seed) --}}
@if ($tab === 'ideas')
    @include('admin.panels.ideas', ['ideas' => $ideas, 'ideaFilters' => $ideaFilters, 'reviewers' => $reviewers])
@endif

{{-- Panel: Phân loại --}}
@if ($tab === 'taxonomies')
    @include('admin.panels.taxonomies', ['faculties' => $faculties, 'categories' => $categories, 'tags' => $tags])
@endif

{{-- Panel: Nhật ký --}}
@if ($tab === 'logs')
    @include('admin.panels.logs', ['logs' => $logs, 'logFilters' => $logFilters])
@endif
@endsection