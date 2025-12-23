@extends('layouts.main')

@section('title', $competition->title)

@section('content')
<div class="bg-slate-50 min-h-screen pb-12">
    {{-- Hero Header --}}
    <div class="bg-white border-b border-slate-200">
        <div class="container py-12">
            <span class="inline-block px-3 py-1 bg-brand-navy text-white text-xs font-bold rounded mb-4">CUỘC THI SÁNG TẠO</span>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">{{ $competition->title }}</h1>
            <div class="flex items-center gap-6 text-slate-500 text-sm">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg> 
                    {{ \Carbon\Carbon::parse($competition->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($competition->end_date)->format('d/m/Y') }}
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg> 
                    {{ $competition->registrations_count ?? 0 }} Đội đăng ký
                </span>
            </div>
        </div>
    </div>

    <div class="container py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2">
                {{-- Hiển thị ảnh: sử dụng banner_url, không hiển thị logo trường --}}
                @php
                    $imageUrl = null;
                    // Sử dụng banner_url (link ảnh)
                    if ($competition->banner_url) {
                        $imageUrl = trim($competition->banner_url);
                        // Nếu không phải URL đầy đủ hoặc đường dẫn tuyệt đối, giả sử là đường dẫn tương đối
                        if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://') && !str_starts_with($imageUrl, '/')) {
                            $imageUrl = asset('storage/' . ltrim($imageUrl, '/'));
                        }
                    }
                @endphp
                @if($imageUrl)
                <div class="bg-white rounded-xl shadow-md mb-8 overflow-hidden">
                    <img src="{{ $imageUrl }}" 
                         class="w-full object-cover max-h-[500px]" 
                         alt="{{ $competition->title }}"
                         onerror="this.parentElement.style.display='none'">
                </div>
                @endif
                
                <div class="bg-white p-8 rounded-xl border border-slate-200 shadow-sm prose max-w-none">
                    @if($competition->description)
                    <h3>Nội dung cuộc thi</h3>
                    <div class="text-slate-700 leading-relaxed">
                        {!! $competition->description !!}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar Panel --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    {{-- Panel Hành động --}}
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-lg">
                        <div class="text-sm font-bold text-slate-500 uppercase mb-2">Trạng thái</div>
                        @if($competition->status == 'open')
                            <div class="text-green-600 font-bold text-xl flex items-center gap-2 mb-6">
                                <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span> Đang mở đăng ký
                            </div>
                            
                            @auth
                                <a href="{{ route('competitions.register', $competition->slug) }}" 
                                   class="block w-full py-4 bg-brand-navy hover:bg-slate-800 text-white font-bold text-center rounded-lg transition shadow-lg shadow-blue-900/20">
                                    Đăng ký tham gia ngay
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="block w-full py-3 bg-slate-100 text-slate-600 font-bold text-center rounded-lg">
                                    Đăng nhập để đăng ký
                                </a>
                            @endauth
                        @else
                            <div class="text-red-600 font-bold text-xl mb-4">Đã kết thúc</div>
                            <button disabled class="block w-full py-3 bg-slate-100 text-slate-400 font-bold rounded-lg cursor-not-allowed">
                                Hết hạn đăng ký
                            </button>
                        @endif
                    </div>

                    {{-- Panel Tài liệu --}}
                    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Tài liệu cuộc thi
                        </h4>
                        @if($competition->attachments && $competition->attachments->count() > 0)
                            <div class="space-y-3">
                                @foreach($competition->attachments as $file)
                                    <a href="#" class="block text-sm text-slate-600 hover:text-blue-600 hover:underline truncate">
                                        📄 {{ $file->filename }}
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-slate-400 italic">Không có tài liệu đính kèm.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


