@extends('layouts.main')
@section('title', 'Lỗi máy chủ')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center py-16">
    <div class="text-9xl font-bold text-slate-200">500</div>
    <h1 class="text-3xl font-bold text-slate-800 mt-4">Lỗi máy chủ</h1>
    <p class="text-slate-500 mt-2 max-w-md">Đã xảy ra lỗi không mong muốn. Vui lòng thử lại sau hoặc liên hệ quản trị viên nếu vấn đề vẫn tiếp tục.</p>
    <div class="mt-8 flex gap-4">
        <a href="{{ route('welcome') }}" class="px-6 py-3 bg-brand-navy text-white rounded-lg font-bold hover:bg-slate-800 transition">
            Trở về trang chủ
        </a>
        <button onclick="window.history.back()" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-lg font-bold hover:bg-slate-300 transition">
            Quay lại
        </button>
    </div>
</div>
@endsection

