@extends('layouts.main')
@section('title', 'Không tìm thấy trang')

@section('content')
<div class="min-h-[60vh] flex flex-col items-center justify-center text-center py-16">
    <div class="text-9xl font-bold text-slate-200">404</div>
    <h1 class="text-3xl font-bold text-slate-800 mt-4">Ối! Trang này không tồn tại.</h1>
    <p class="text-slate-500 mt-2 max-w-md">Có vẻ như đường dẫn bạn truy cập bị hỏng hoặc trang đã bị xóa.</p>
    <a href="{{ route('welcome') }}" class="mt-8 px-6 py-3 bg-brand-navy text-white rounded-lg font-bold hover:bg-slate-800 transition">
        Trở về trang chủ
    </a>
</div>
@endsection

