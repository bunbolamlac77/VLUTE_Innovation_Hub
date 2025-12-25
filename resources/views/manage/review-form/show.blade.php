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

    <div class="space-y-8">
        {{-- NỘI DUNG CHÍNH --}}
        <div class="space-y-8">
            
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
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 lg:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
                    <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 leading-tight">{{ $idea->title }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">{{ $idea->status }}</span>
                </div>

                <div class="flex flex-wrap gap-3 lg:gap-4 text-sm text-slate-600 mb-6 pb-4 border-b border-slate-200">
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

                <div class="prose prose-slate max-w-none text-slate-700">
                    <h3 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Mô tả chi tiết ý tưởng
                    </h3>
                    <div class="text-slate-700 leading-relaxed">{!! $idea->description !!}</div>
                </div>

                @if($idea->content)
                    <div class="prose prose-slate max-w-none text-slate-700 mt-8 pt-8 border-t border-slate-200">
                        <h3 class="text-lg font-bold text-slate-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Nội dung chi tiết
                        </h3>
                        <div class="text-slate-700 leading-relaxed" style="word-wrap: break-word; overflow-wrap: break-word;">
                            {!! \App\Helpers\MarkdownHelper::parse($idea->content) !!}
                        </div>
                    </div>
                @endif

                {{-- File đính kèm --}}
                @if($idea->attachments && $idea->attachments->count() > 0)
                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <h4 class="font-bold text-sm text-slate-700 uppercase mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            Tài liệu đính kèm
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($idea->attachments as $file)
                                <a href="{{ route('attachments.download', $file->id) }}" class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-300 transition-all group">
                                    <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div class="overflow-hidden flex-1 min-w-0">
                                        <div class="truncate font-medium text-sm text-slate-900 group-hover:text-indigo-700">{{ $file->filename }}</div>
                                        <div class="text-xs text-slate-500 mt-0.5">{{ round($file->size / 1024) }} KB</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- 2. CÔNG CỤ AI --}}
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 border-2 border-indigo-200 rounded-xl p-6 lg:p-8 shadow-sm" x-data="{ aiResult: '', loading: false, duplicateLoading: false, duplicateResult: null }">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-indigo-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        AI giúp tôi
                    </h3>
                </div>

                <p class="text-sm text-indigo-700 mb-6 leading-relaxed">Sử dụng AI để tóm tắt và đánh giá sơ bộ ý tưởng này trước khi bạn quyết định.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    {{-- Nút Phân tích nội dung (Văn bản) --}}
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
                        class="px-5 py-3 bg-white border-2 border-indigo-300 text-indigo-700 font-semibold rounded-xl hover:bg-indigo-100 hover:border-indigo-400 flex items-center justify-center gap-2 transition-all shadow-sm"
                        :disabled="loading"
                        :class="loading ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-md'">
                        <span x-show="loading" class="animate-spin text-lg">⌛</span>
                        <span x-show="!loading" class="text-lg">📝</span>
                        <span>Phân tích Văn bản</span>
                    </button>

                    {{-- Nút Kiểm tra Đạo văn --}}
                    <button type="button" 
                        @click="
                            duplicateLoading = true; duplicateResult = null;
                            const content = `{{ strip_tags($idea->description ?? $idea->summary ?? '') }} {{ strip_tags($idea->content ?? '') }}`.trim();
                            if (!content || content.length < 10) {
                                duplicateResult = { error: 'Nội dung quá ngắn để kiểm tra.' };
                                duplicateLoading = false;
                                return;
                            }
                            fetch('{{ route('ai.duplicate') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ 
                                    content: content,
                                    current_id: {{ $idea->id }}
                                })
                            })
                            .then(res => res.json())
                            .then(data => { 
                                duplicateResult = data;
                                duplicateLoading = false; 
                            })
                            .catch(err => { 
                                duplicateResult = { error: '❌ Lỗi kết nối: ' + (err.message || 'Vui lòng thử lại sau') }; 
                                duplicateLoading = false; 
                            });
                        "
                        class="px-5 py-3 bg-white border-2 border-indigo-300 text-indigo-700 font-semibold rounded-xl hover:bg-indigo-100 hover:border-indigo-400 flex items-center justify-center gap-2 transition-all shadow-sm"
                        :disabled="duplicateLoading"
                        :class="duplicateLoading ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-md'">
                        <span x-show="duplicateLoading" class="animate-spin text-lg">⌛</span>
                        <span x-show="!duplicateLoading" class="text-lg">🔍</span>
                        <span>Kiểm tra Đạo văn</span>
                    </button>
                </div>

                {{-- Kết quả AI - Văn bản --}}
                <div x-show="aiResult" 
                     class="mt-6 p-5 bg-white rounded-xl border-2 border-indigo-200 text-sm leading-relaxed max-w-none overflow-auto max-h-96 prose prose-sm prose-indigo shadow-sm" 
                     x-html="aiResult ? aiResult.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>').replace(/\*(.*?)\*/g, '<em>$1</em>').replace(/^### (.*$)/gim, '<h3>$1</h3>').replace(/^## (.*$)/gim, '<h2>$1</h2>').replace(/^# (.*$)/gim, '<h1>$1</h1>').replace(/^\- (.*$)/gim, '<li>$1</li>').replace(/\n/g, '<br>') : ''"></div>

                {{-- Kết quả Kiểm tra Đạo văn --}}
                <div x-show="duplicateResult" 
                     class="mt-6 p-5 bg-white rounded-xl border-2 border-indigo-200 text-sm shadow-sm">
                    <template x-if="duplicateResult && duplicateResult.error">
                        <div class="text-red-600" x-text="duplicateResult.error"></div>
                    </template>
                    <template x-if="duplicateResult && duplicateResult.requires_openai">
                        <div class="text-amber-600" x-text="duplicateResult.message || 'Tính năng này yêu cầu OpenAI API key.'"></div>
                    </template>
                    <template x-if="duplicateResult && !duplicateResult.error && !duplicateResult.requires_openai">
                        <div>
                            <div class="font-bold mb-2" x-text="duplicateResult.is_duplicate ? '⚠️ Phát hiện trùng lặp!' : '✅ Không phát hiện trùng lặp'"></div>
                            <template x-if="duplicateResult.matches && duplicateResult.matches.length > 0">
                                <div class="mt-2">
                                    <div class="text-sm font-semibold mb-1">Các ý tưởng tương tự:</div>
                                    <ul class="list-disc list-inside space-y-1">
                                        <template x-for="match in duplicateResult.matches" :key="match.id">
                                            <li>
                                                <a :href="match.slug ? '/ideas/' + match.slug : '/my-ideas/' + match.id" 
                                                   target="_blank" 
                                                   class="text-indigo-600 hover:underline"
                                                   x-text="match.title + ' (' + match.score + ')'"></a>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            {{-- KHỐI KIỂM TRA ĐẠO VĂN ONLINE (MỚI) --}}
            <div class="mt-8 bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden" x-data="plagiarismChecker()">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Tra cứu Trùng lặp Cộng đồng
                    </h3>
                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded border border-blue-200">Nguồn: IP Vietnam, Techfest, Google...</span>
                </div>

                <div class="p-6">
                    <p class="text-sm text-slate-500 mb-4">
                        Hệ thống sẽ quét tiêu đề dự án <b>"{{ $idea->title }}"</b> trên các cơ sở dữ liệu công khai để tìm các dự án tương tự.
                    </p>

                    {{-- Nút bấm --}}
                    <button @click="checkOnline" 
                            :disabled="loading"
                            class="mb-4 px-4 py-2 bg-white border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 flex items-center gap-2 transition shadow-sm">
                        <span x-show="!loading">🔍 Quét ngay</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Đang tra cứu dữ liệu...
                        </span>
                    </button>

                    {{-- Kết quả --}}
                    <div x-show="hasSearched" x-transition>
                        <template x-if="results.length > 0">
                            <div class="space-y-4">
                                <div class="p-3 bg-amber-50 text-amber-800 text-sm rounded border border-amber-200 flex items-start gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span>Phát hiện <strong x-text="results.length"></strong> kết quả có khả năng liên quan:</span>
                                </div>

                                <ul class="divide-y divide-slate-100">
                                    <template x-for="item in results" :key="item.link">
                                        <li class="py-3">
                                            <div class="flex justify-between items-start">
                                                <a :href="item.link" target="_blank" class="text-blue-600 font-semibold hover:underline text-base block" x-text="item.title"></a>
                                                <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded ml-2 whitespace-nowrap" x-text="item.source"></span>
                                            </div>
                                            <p class="text-slate-600 text-sm mt-1 line-clamp-2" x-text="item.snippet"></p>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </template>

                        <template x-if="results.length === 0">
                            <div class="text-center py-6 text-green-600 bg-green-50 rounded border border-green-100">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="font-semibold">Không tìm thấy trùng lặp đáng ngờ.</p>
                                <p class="text-xs text-green-500 mt-1">Chưa phát hiện dự án công khai nào có tên tương tự.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <script>
                function plagiarismChecker() {
                    return {
                        loading: false,
                        hasSearched: false,
                        results: [],
                        
                        checkOnline() {
                            this.loading = true;
                            this.hasSearched = false;
                            this.results = [];

                            // Lấy tiêu đề dự án từ blade PHP sang JS
                            const projectTitle = "{{ $idea->title }}";

                            fetch('{{ route("plagiarism.check.online") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ title: projectTitle })
                            })
                            .then(res => {
                                // Kiểm tra status code trước khi parse JSON
                                if (!res.ok) {
                                    return res.json().then(err => {
                                        throw new Error(err.message || `HTTP ${res.status}: ${res.statusText}`);
                                    }).catch(() => {
                                        throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                                    });
                                }
                                return res.json();
                            })
                            .then(data => {
                                this.loading = false;
                                this.hasSearched = true;
                                if (data.success) {
                                    this.results = data.results || [];
                                } else {
                                    Swal.fire('Lỗi tra cứu', data.message || 'Không thể kết nối Google.', 'error');
                                }
                            })
                            .catch(err => {
                                this.loading = false;
                                this.hasSearched = false;
                                console.error('Plagiarism check error:', err);
                                Swal.fire('Lỗi', err.message || 'Lỗi hệ thống khi gọi API. Vui lòng thử lại sau.', 'error');
                            });
                        }
                    }
                }
            </script>

            {{-- 3. FORM QUYẾT ĐỊNH --}}
            <div class="bg-white border-2 border-indigo-300 rounded-xl p-6 lg:p-8 shadow-lg">
                <h3 class="text-xl lg:text-2xl font-bold text-slate-900 mb-6 pb-3 border-b-2 border-indigo-200 flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Quyết định Phản biện
                </h3>
                
                <form action="{{ route('manage.review.store', $idea->id) }}" method="POST">
                    @csrf
                    
                    {{-- Nhập nhận xét --}}
                    <div class="mb-6">
                        <label class="block font-semibold text-sm mb-3 text-slate-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Nhận xét / Góp ý của bạn <span class="text-red-500">*</span>
                        </label>
                        <textarea name="comment" rows="5" class="w-full rounded-lg border-2 border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all p-3 text-slate-700" required placeholder="Nhập lý do duyệt, từ chối hoặc yêu cầu sửa đổi..."></textarea>
                    </div>

                    {{-- Các nút hành động --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button type="submit" name="action" value="approve" 
                                class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-6 rounded-xl flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Duyệt & Gửi cấp trên
                        </button>

                        <button type="submit" name="action" value="request_changes" 
                                class="flex-1 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold py-3 px-6 rounded-xl flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Yêu cầu chỉnh sửa
                        </button>

                        <button type="submit" name="action" value="reject" 
                                class="flex-1 bg-gradient-to-r from-slate-200 to-slate-300 hover:from-red-100 hover:to-red-200 text-slate-700 hover:text-red-700 font-semibold py-3 px-6 rounded-xl flex items-center justify-center gap-2 shadow-md hover:shadow-lg transition-all border-2 border-slate-300 hover:border-red-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Từ chối
                        </button>
                    </div>
                </form>
            </div>

            {{-- 4. LỊCH SỬ XỬ LÝ (ĐÃ CHUYỂN XUỐNG DƯỚI) --}}
            <div class="bg-white rounded-xl shadow-sm border-2 border-slate-200 p-6 lg:p-8">
                <h4 class="text-lg font-bold text-slate-900 mb-5 flex items-center gap-2 pb-3 border-b-2 border-slate-200">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Lịch sử xử lý
                </h4>
                <div class="space-y-5 border-l-2 border-indigo-300 ml-3 pl-6 relative">
                    @forelse($idea->auditLogs as $log)
                        <div class="relative">
                            <span class="absolute -left-[29px] top-1.5 w-4 h-4 rounded-full bg-indigo-500 border-2 border-white shadow-sm"></span>
                            <div class="text-xs font-medium text-indigo-600 mb-1">{{ $log->created_at->format('H:i d/m/Y') }}</div>
                            <div class="font-semibold text-sm text-slate-900 mb-1">{{ $log->actor->name ?? 'Hệ thống' }}</div>
                            <div class="text-sm text-slate-700 mb-2">{{ $log->action }}</div>
                            @php
                                $comment = $log->meta['comment'] ?? ($log->comment ?? null);
                            @endphp
                            @if($comment)
                                <div class="mt-2 text-sm bg-indigo-50 border-l-4 border-indigo-400 p-3 rounded-r-lg text-slate-700 italic">"{{ $comment }}"</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-sm text-slate-400 italic py-4">Chưa có lịch sử xử lý.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
