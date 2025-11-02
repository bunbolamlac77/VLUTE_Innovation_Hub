@if (auth()->user()?->hasRole('admin'))
    {{-- Admin users: redirect to admin panel --}}
    <script>window.location.href = '{{ route('admin.home', ['tab' => 'approvals']) }}';</script>
@else
{{-- Regular users: use main layout with empty content --}}
@extends('layouts.main')

@section('title', 'Bảng điều khiển - VLUTE Innovation Hub')

@section('content')
    {{-- Phần main content để trống để sau này có thể cấu trúc lại và thêm các chức năng --}}
    <section style="min-height: 400px; padding: 64px 0;">
        <div class="container">
            {{-- Placeholder cho nội dung tương lai --}}
            {{-- Có thể thêm các chức năng dashboard ở đây --}}
        </div>
    </section>
@endsection
@endif