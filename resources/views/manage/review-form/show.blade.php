@extends('layouts.admin-shell')

@section('title', 'Phản biện ý tưởng: ' . $idea->title)

@section('main')
<div class="container mx-auto pb-12">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <a href="{{ route('manage.review-queue.index') }}" class="hover:text-blue-600">Hàng chờ</a>
        <span>/</span>
        <span class="text-slate-900 font-semibold">Chi tiết</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- CỘT TRÁI: NỘI DUNG Ý TƯỞNG --}}
        <div class="lg:col-span-2 space-y-8">
            
            {{-- 1. HIỂN THỊ POSTER / LOGO (Yêu cầu mới) --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="aspect-w-16 aspect-h-9 bg-slate-100 relative group">
                    @if($idea->image)
                        {{-- Ưu tiên ảnh sinh viên up --}}
                        <img src="{{ str_starts_with($idea->image, 'http') ? $idea->image : asset('storage/'.$idea->image) }}" 
                             class="w-full h-full object-contain max-h-[500px] mx-auto" 
                             alt="Poster">
                    @else
                        {{-- Fallback Logo trường --}}
                        <div class="w-full h-full flex items-center justify-center p-8">
                            <img src="{{ asset('images/logotruong.jpg') }}" class="max-h-[300px] object-contain opacity-80" alt="VLUTE Logo">
                        </div>
                    @endif
                </div>
            </div>

            {{-- Thông tin chi tiết --}}
            <div class="card p-6">
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-3xl font-bold text-slate-900">{{ $idea->title }}</h1>
                    <span class="badge badge-amber">{{ $idea->status }}</span>
                </div>

                <div class="flex flex-wrap gap-4 text-sm text-slate-600 mb-6 border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <b>{{ $idea->owner->name }}</b>
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ $idea->faculty->name ?? 'Chưa có khoa' }}
                    </div>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        {{ $idea->category->name ?? 'Chưa phân loại' }}
                    </div>
                </div>

                <div class="prose max-w-none text-slate-800">
                    <h3 class="font-bold text-lg mb-2">Mô tả chi tiết ý tưởng:</h3>
                    {!! $idea->description !!}
                </div>

                {{-- File đính kèm --}}
                @if($idea->attachments && $idea->attachments->count() > 0)
                    <div class="mt-8 pt-4 border-t border-slate-100">
                        <h4 class="font-bold text-sm text-slate-500 uppercase mb-3">Tài liệu đính kèm</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($idea->attachments as $file)
                                <a href="{{ route('attachments.download', $file->id) }}" class="flex items-center gap-3 p-3 border rounded-lg hover:bg-slate-50">
                                    <span class="text-2xl">📄</span>
                                    <div class="overflow-hidden">
                                        <div class="truncate font-medium text-sm">{{ $file->filename }}</div>
                                        <div class="text-xs text-slate-400">{{ round($file->size / 1024) }} KB</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- 2. CÔNG CỤ AI --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6" x-data="{ aiResult: '', loading: false, visionLoading: false, visionResult: '' }">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-indigo-800 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        Trợ lý AI (Groq)
                    </h3>
                </div>

                <p class="text-sm text-indigo-600 mb-4">Sử dụng AI để tóm tắt và đánh giá sơ bộ ý tưởng này trước khi bạn quyết định.</p>
                
                {{-- Nút Phân tích nội dung (Văn bản) --}}
                <div class="flex gap-2 mb-4">
                    <button type="button" 
                        @click="
                            loading = true; aiResult = '';
                            fetch('{{ route('ai.review') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ content: `{{ strip_tags($idea->description ?? $idea->summary ?? '') }}` })
                            })
                            .then(res => {
                                if (!res.ok) {
                                    return res.json().then(err => {
                                        throw new Error(err.error || 'Lỗi từ server');
                                    });
                                }
                                return res.json();
                            })
                            .then(data => { 
                                if (data.error) {
                                    aiResult = '❌ Lỗi: ' + data.error;
                                } else {
                                    aiResult = data.result || 'Không có kết quả';
                                }
                                loading = false; 
                            })
                            .catch(err => { 
                                aiResult = '❌ Lỗi kết nối AI: ' + (err.message || 'Vui lòng thử lại sau'); 
                                loading = false; 
                            });
                        "
                        class="px-4 py-2 bg-white border border-indigo-200 text-indigo-700 font-bold rounded-lg hover:bg-indigo-100 flex items-center gap-2 transition-all"
                        :disabled="loading"
                        :class="loading ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-md'">
                        <span x-show="loading" class="animate-spin">⌛</span>
                        <span x-show="!loading">📝</span>
                        Phân tích Văn bản
                    </button>
                </div>

                {{-- Nút Phân tích Hình ảnh --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-indigo-700 mb-2">Phân tích Poster/Slide (nếu có)</label>
                    <div class="flex gap-2">
                        <input type="file" 
                            id="vision-image-input" 
                            accept="image/*" 
                            class="hidden"
                            @change="
                                const file = $event.target.files[0];
                                if (!file) return;
                                if (file.size > 5120000) {
                                    alert('File quá lớn. Vui lòng chọn file nhỏ hơn 5MB.');
                                    return;
                                }
                                const formData = new FormData();
                                formData.append('image', file);
                                visionLoading = true; visionResult = '';
                                fetch('{{ route('ai.vision') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: formData
                                })
                                .then(res => {
                                    if (!res.ok) {
                                        return res.json().then(err => {
                                            throw new Error(err.error || 'Lỗi từ server');
                                        });
                                    }
                                    return res.json();
                                })
                                .then(data => { 
                                    if (data.error) {
                                        visionResult = '❌ Lỗi: ' + data.error;
                                    } else {
                                        visionResult = data.result || 'Không có kết quả';
                                    }
                                    visionLoading = false; 
                                })
                                .catch(err => { 
                                    visionResult = '❌ Lỗi kết nối AI: ' + (err.message || 'Vui lòng thử lại sau'); 
                                    visionLoading = false; 
                                });
                            ">
                        <label for="vision-image-input" 
                            class="px-4 py-2 bg-white border border-indigo-200 text-indigo-700 font-bold rounded-lg hover:bg-indigo-100 flex items-center gap-2 cursor-pointer transition-all"
                            :class="visionLoading ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-md'">
                            <span x-show="visionLoading" class="animate-spin">⌛</span>
                            <span x-show="!visionLoading">🖼️</span>
                            Phân tích Hình ảnh
                        </label>
                    </div>
                </div>

                {{-- Kết quả AI - Văn bản --}}
                <div x-show="aiResult" 
                     class="mt-4 p-4 bg-white rounded-lg border border-indigo-100 text-sm leading-relaxed max-w-none overflow-auto max-h-96 prose prose-sm prose-indigo" 
                     x-html="aiResult ? aiResult.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\*(.*?)\*/g, '<em>$1</em>').replace(/^### (.*$)/gim, '<h3>$1</h3>').replace(/^## (.*$)/gim, '<h2>$1</h2>').replace(/^# (.*$)/gim, '<h1>$1</h1>').replace(/^\- (.*$)/gim, '<li>$1</li>').replace(/\n/g, '<br>') : ''"></div>

                {{-- Kết quả AI - Hình ảnh --}}
                <div x-show="visionResult" 
                     class="mt-4 p-4 bg-white rounded-lg border border-indigo-100 text-sm leading-relaxed max-w-none overflow-auto max-h-96 prose prose-sm prose-indigo" 
                     x-html="visionResult ? visionResult.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\*(.*?)\*/g, '<em>$1</em>').replace(/^### (.*$)/gim, '<h3>$1</h3>').replace(/^## (.*$)/gim, '<h2>$1</h2>').replace(/^# (.*$)/gim, '<h1>$1</h1>').replace(/^\- (.*$)/gim, '<li>$1</li>').replace(/\n/g, '<br>') : ''"></div>
            </div>

            {{-- 3. FORM QUYẾT ĐỊNH (ĐÃ CHUYỂN XUỐNG DƯỚI CÙNG) --}}
            <div class="bg-white border-2 border-brand-navy rounded-xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-slate-900 mb-4 border-b pb-2">Quyết định Phản biện</h3>
                
                <form action="{{ route('manage.review.store', $idea->id) }}" method="POST">
                    @csrf
                    
                    {{-- Nhập nhận xét --}}
                    <div class="mb-4">
                        <label class="block font-bold text-sm mb-2 text-slate-700">Nhận xét / Góp ý của bạn (*)</label>
                        <textarea name="comment" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required placeholder="Nhập lý do duyệt, từ chối hoặc yêu cầu sửa đổi..."></textarea>
                    </div>

                    {{-- Các nút hành động --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" name="action" value="approve" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Duyệt & Gửi cấp trên
                        </button>

                        <button type="submit" name="action" value="request_changes" 
                                class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Yêu cầu chỉnh sửa
                        </button>

                        <button type="submit" name="action" value="reject" 
                                class="flex-1 bg-slate-200 hover:bg-red-100 text-slate-700 hover:text-red-600 font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Từ chối
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- CỘT PHẢI: CHỈ CÒN THÔNG TIN BỔ SUNG (Lịch sử, Tag...) --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Panel Lịch sử --}}
            <div class="card p-5 bg-slate-50">
                <h4 class="font-bold text-slate-700 mb-3 text-sm uppercase">Lịch sử xử lý</h4>
                <div class="space-y-4 border-l-2 border-slate-200 ml-2 pl-4 relative">
                    @forelse($idea->auditLogs as $log)
                        <div class="relative">
                            <span class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                            <div class="text-xs text-slate-500">{{ $log->created_at->format('H:i d/m/Y') }}</div>
                            <div class="font-semibold text-sm text-slate-800">{{ $log->actor->name ?? 'Hệ thống' }}</div>
                            <div class="text-sm text-slate-600">{{ $log->action }}</div>
                            @php
                                $comment = $log->meta['comment'] ?? ($log->comment ?? null);
                            @endphp
                            @if($comment)
                                <div class="mt-1 text-xs italic bg-white p-2 rounded border text-slate-500">"{{ $comment }}"</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-sm text-slate-400 italic">Chưa có lịch sử.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
