@extends('layouts.main')
@section('title', 'Không có quyền truy cập')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center py-16">
    <div class="text-9xl font-bold text-slate-200">403</div>
    <h1 class="text-3xl font-bold text-slate-800 mt-4">Không có quyền truy cập</h1>
    <p class="text-slate-500 mt-2 max-w-md">
        {{ $message ?? 'Bạn không có quyền truy cập trang này. Vui lòng liên hệ quản trị viên để được cấp quyền.' }}
    </p>
    <div class="mt-8 flex gap-4">
        @auth
            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-brand-navy text-white rounded-lg font-bold hover:bg-slate-800 transition">
                Về Trang Chủ
            </a>
        @else
            <a href="{{ route('login') }}" class="px-6 py-3 bg-brand-navy text-white rounded-lg font-bold hover:bg-slate-800 transition">
                Đăng Nhập
            </a>
        @endauth
        <button onclick="window.history.back()" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-lg font-bold hover:bg-slate-300 transition">
            Quay lại
        </button>
    </div>
</div>
@endsection