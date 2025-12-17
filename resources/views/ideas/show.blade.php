@extends('layouts.main')

@section('title', $idea->title . ' - Ngân hàng Ý tưởng')

@section('content')
<div class="container py-8">
    {{-- Breadcrumb --}}
    <div class="text-sm text-slate-500 mb-6">
        <a href="{{ route('welcome') }}" class="hover:text-blue-600">Trang chủ</a> / 
        <a href="{{ route('ideas.index') }}" class="hover:text-blue-600">Ý tưởng</a> / 
        <span class="text-slate-900">{{ $idea->title }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- CỘT TRÁI: NỘI DUNG --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Ảnh bìa (nếu có) --}}
            @if($idea->image)
                <img src="{{ asset('storage/' . $idea->image) }}" class="w-full h-80 object-cover rounded-2xl shadow-sm">
            @endif

            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                <h1 class="text-3xl font-bold text-slate-900 mb-4 leading-tight">{{ $idea->title }}</h1>
                
                {{-- Categories & Tags --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $idea->category->name ?? 'Chưa phân loại' }}</span>
                    @foreach($idea->tags as $tag)
                        <span class="bg-slate-100 text-slate-600 text-xs px-2 py-0.5 rounded">#{{ $tag->name }}</span>
                    @endforeach
                </div>

                <div class="prose max-w-none text-slate-700">
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Mô tả ý tưởng</h3>
                    {{-- Hiển thị HTML từ CKEditor --}}
                    {!! $idea->description !!}
                </div>
            </div>

            {{-- Phần Bình luận --}}
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 mt-8" id="comments">
                <h3 class="font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    Bình luận ({{ $idea->comments->where('visibility', 'public')->count() }})
                </h3>

                {{-- Form bình luận --}}
                @auth
                    <form action="{{ route('ideas.comments.store', $idea->id) }}" method="POST" class="mb-8">
                        @csrf
                        <div class="flex gap-4">
                            <img src="{{ auth()->user()->avatar_url ?? asset('images/avatar-default.svg') }}" class="w-10 h-10 rounded-full border">
                            <div class="flex-1">
                                <textarea name="content" rows="3" class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Chia sẻ suy nghĩ hoặc góp ý của bạn..." required></textarea>
                                <div class="mt-2 text-right">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg text-sm shadow-md transition">Gửi bình luận</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="bg-blue-50 text-blue-700 p-4 rounded-lg mb-6 text-center">
                        Vui lòng <a href="{{ route('login') }}" class="font-bold underline">đăng nhập</a> để tham gia thảo luận.
                    </div>
                @endauth

                {{-- Danh sách bình luận --}}
                <div class="space-y-6">
                    @forelse($idea->comments->where('visibility', 'public') as $comment)
                        <div class="flex gap-4">
                            <img src="{{ $comment->user->avatar_url ?? asset('images/avatar-default.svg') }}" class="w-10 h-10 rounded-full border bg-white">
                            <div class="flex-1">
                                <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <span class="font-bold text-slate-900">{{ $comment->user->name }}</span>
                                            <span class="text-xs text-slate-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        {{-- Role Badge --}}
                                        @if($comment->user->hasRole('staff'))
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700">MENTOR</span>
                                        @endif
                                    </div>
                                    <div class="text-slate-700 text-sm leading-relaxed">
                                        {!! nl2br(e($comment->body)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-slate-400 py-4">Chưa có bình luận nào. Hãy là người đầu tiên!</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: PANEL THÔNG TIN --}}
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                {{-- Panel Tác giả --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <img class="w-14 h-14 rounded-full border-2 border-white shadow" 
                             src="{{ $idea->owner->avatar_url ?? asset('images/avatar-default.svg') }}" 
                             alt="{{ $idea->owner->name }}">
                        <div>
                            <div class="font-bold text-slate-900 text-lg">{{ $idea->owner->name }}</div>
                            <div class="text-sm text-slate-500">{{ $idea->faculty->name ?? 'Sinh viên' }}</div>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-100 pt-4 mt-4 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Ngày đăng:</span>
                            <span class="font-medium">{{ $idea->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500">Trạng thái:</span>
                            @php
                                $statusMap = [
                                    'approved_final' => ['text' => 'Đã duyệt', 'color' => 'text-green-600 bg-green-50'],
                                    'submitted_center' => ['text' => 'Chờ duyệt', 'color' => 'text-yellow-600 bg-yellow-50'],
                                    'draft' => ['text' => 'Bản nháp', 'color' => 'text-slate-600 bg-slate-50'],
                                ];
                                $st = $statusMap[$idea->status] ?? ['text' => $idea->status, 'color' => 'text-slate-600'];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $st['color'] }}">{{ $st['text'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Panel Hành động --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="text-center mb-6">
                        <div class="text-5xl font-bold text-blue-600 mb-1">{{ $idea->likes_count ?? 0 }}</div>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lượt thích</div>
                    </div>
                    
                    @auth
                        <form action="{{ route('ideas.like', $idea->id) }}" method="POST">
                            @csrf
                            <button class="w-full py-3 rounded-lg font-bold text-white transition shadow-lg shadow-blue-200 
                                {{ $idea->isLikedBy(auth()->user()) ? 'bg-slate-400 hover:bg-slate-500' : 'bg-blue-600 hover:bg-blue-700' }}">
                                {{ $idea->isLikedBy(auth()->user()) ? 'Đã thích' : '❤ Yêu thích ý tưởng này' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-center rounded-lg">
                            Đăng nhập để bình chọn
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


