@extends('layouts.main')

@section('title', $challenge->title)

@section('content')
<div class="container py-10">
    <div class="grid md:grid-cols-3 gap-8">
        {{-- CỘT TRÁI: Nội dung thách thức --}}
        <div class="md:col-span-2 space-y-8">
            {{-- Ảnh bìa --}}
            @if($challenge->image)
                <img src="{{ asset('storage/' . $challenge->image) }}" class="w-full h-72 object-cover rounded-2xl shadow-sm">
            @endif

            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $challenge->title }}</h1>
                <p class="text-slate-500 mb-6">
                    Đăng bởi: 
                    <span class="font-semibold text-blue-600">{{ $challenge->organization->name ?? 'Doanh nghiệp ẩn danh' }}</span>
                </p>
                
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm prose max-w-none">
                    <h3 class="text-xl font-bold text-slate-800">Mô tả vấn đề</h3>
                    {!! $challenge->problem_statement !!}

                    @if($challenge->requirements)
                        <div class="my-6 border-t border-slate-100"></div>
                        <h3 class="text-xl font-bold text-slate-800">Yêu cầu giải pháp</h3>
                        {!! $challenge->requirements !!}
                    @endif
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: Panel Thông tin & Actions --}}
        <div class="md:col-span-1">
            <div class="sticky top-24 space-y-6">
                {{-- Panel Giải thưởng (Nổi bật) --}}
                <div class="bg-gradient-to-br from-indigo-900 to-blue-800 p-6 rounded-2xl text-white shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-10">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <div class="text-blue-200 text-sm font-bold uppercase tracking-wider mb-1">Tổng giải thưởng</div>
                        <div class="text-3xl font-extrabold text-yellow-400">{{ $challenge->reward ?? 'Thỏa thuận' }}</div>
                    </div>
                </div>

                {{-- Panel Hành động --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between mb-4 pb-4 border-b border-slate-100">
                        <span class="text-slate-500 text-sm">Hạn nộp bài</span>
                        <span class="font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($challenge->valid_until)->format('d/m/Y') }}
                        </span>
                    </div>

                    @auth
                        @if(auth()->user()->hasRole('student'))
                            <a href="{{ route('challenges.submit.create', $challenge->id) }}" 
                               class="flex items-center justify-center w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-lg shadow-blue-200">
                                🚀 Gửi giải pháp ngay
                            </a>
                        @else
                            <div class="text-center text-sm text-slate-500 bg-slate-50 p-3 rounded-lg">
                                Chỉ tài khoản <b>Sinh viên</b> mới có thể nộp bài.
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-3 bg-slate-100 text-slate-600 font-bold text-center rounded-xl">
                            Đăng nhập để nộp bài
                        </a>
                    @endauth
                </div>

                {{-- Panel File đính kèm --}}
                @if($challenge->attachments && $challenge->attachments->count() > 0)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-3">Tài liệu tham khảo</h4>
                    <div class="space-y-2">
                        @foreach($challenge->attachments as $file)
                            <a href="#" class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition">
                                <span class="text-2xl">📂</span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-slate-900 truncate">{{ $file->filename }}</div>
                                    <div class="text-xs text-slate-500">{{ round($file->size / 1024, 1) }} KB</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


