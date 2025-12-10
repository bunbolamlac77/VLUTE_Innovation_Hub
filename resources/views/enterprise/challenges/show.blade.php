@extends('layouts.main')

@section('title', $challenge->title)

@section('content')

<div class="container py-8">

    <div class="grid md:grid-cols-3 gap-8">

        {{-- Cột trái: Nội dung chính --}}
        <div class="md:col-span-2 space-y-6">

            {{-- Ảnh bìa --}}
            @if($challenge->image)
                <img src="{{ asset('storage/' . $challenge->image) }}" class="w-full h-64 object-cover rounded-xl shadow-sm">
            @endif

            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
                <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $challenge->title }}</h1>
                <div class="flex gap-3 text-sm text-slate-500 mb-6">
                    <span>📅 Hạn nộp: {{ \Carbon\Carbon::parse($challenge->valid_until)->format('d/m/Y') }}</span>
                    <span>🏢 {{ $challenge->organization->name ?? 'Doanh nghiệp' }}</span>
                </div>

                {{-- Hiển thị HTML an toàn từ CKEditor --}}
                <div class="prose max-w-none">
                    <h3 class="font-bold text-lg">Mô tả vấn đề</h3>
                    <div>{!! $challenge->problem_statement !!}</div>

                    @if($challenge->requirements)
                        <h3 class="font-bold text-lg mt-6">Yêu cầu giải pháp</h3>
                        <div>{!! $challenge->requirements !!}</div>
                    @endif
                </div>

                {{-- Danh sách file đính kèm --}}
                @if($challenge->attachments && $challenge->attachments->count() > 0)
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <h4 class="font-bold text-slate-800 mb-3">Tài liệu đính kèm</h4>
                        <div class="grid gap-2">
                            @foreach($challenge->attachments as $file)
                                <a href="{{ route('attachments.download', $file->id) }}" class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50">
                                    <span class="text-2xl">📎</span>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-slate-900 truncate">{{ $file->filename }}</div>
                                        <div class="text-xs text-slate-500">{{ round($file->size / 1024, 1) }} KB</div>
                                    </div>
                                    <span class="text-blue-600 text-sm font-semibold">Tải về</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Cột phải: Thông tin tóm tắt & Action --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm sticky top-24">
                <div class="mb-4">
                    <div class="text-sm text-slate-500 uppercase font-bold tracking-wider mb-1">Giải thưởng</div>
                    <div class="text-2xl font-bold text-emerald-600">{{ $challenge->reward ?? 'Thỏa thuận' }}</div>
                </div>
                
                {{-- Nút nộp bài (chỉ hiện cho sinh viên) --}}
                @if(auth()->check() && auth()->user()->hasRole('student'))
                    <a href="{{ route('challenges.submit.create', $challenge->id) }}" class="block w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-center rounded-lg transition">
                        Gửi giải pháp ngay
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
