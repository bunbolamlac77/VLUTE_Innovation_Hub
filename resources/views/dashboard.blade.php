@if (auth()->user()?->role === 'admin')
    {{-- Admin users see the full admin interface --}}
    @extends('layouts.admin-shell')
    @section('main')
    {{-- Redirect to admin home --}}
    <script>window.location.href = '{{ route('admin.home', ['tab' => 'approvals']) }}';</script>
    @endsection
@else
    {{-- Regular users see their dashboard --}}
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>

        {{-- Nội dung dashboard khác của bạn --}}
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 py-6">
            <p>Vai trò: <strong>{{ auth()->user()->role_label }}</strong></p>
        </div>
    </x-app-layout>
@endif