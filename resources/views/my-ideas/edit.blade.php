@extends('layouts.main')

@section('title', 'Chỉnh sửa ý tưởng - VLUTE Innovation Hub')

@section('content')
    {{-- Breadcrumb --}}
    <section class="container" style="padding: 24px 0 16px;">
        <nav style="display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 14px;">
            <a href="/" style="color: var(--brand-navy);">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('my-ideas.index') }}" style="color: var(--brand-navy);">Ý tưởng của tôi</a>
            <span>/</span>
            <a href="{{ route('my-ideas.show', $idea->id) }}" style="color: var(--brand-navy);">{{ Str::limit($idea->title, 30) }}</a>
            <span>/</span>
            <span>Chỉnh sửa</span>
        </nav>
    </section>

    {{-- Form --}}
    <section class="container" style="padding: 16px 0 64px;">
        <div style="max-width: 1100px; margin: 0 auto;">
            <div class="card" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <div class="card-body" style="padding: 48px;">
                    <h2 style="margin: 0 0 32px; font-size: 32px; color: #0f172a; font-weight: 800; letter-spacing: -0.01em;">Chỉnh sửa ý tưởng</h2>

                    <form method="POST" action="{{ route('my-ideas.update', $idea->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div style="margin-bottom: 24px;">
                            <label for="title" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                                Tiêu đề <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $idea->title) }}" required
                                placeholder="Nhập tiêu đề ý tưởng..."
                                style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; font-size: 15px;">
                            @error('title')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div style="margin-bottom: 24px;">
                            <label for="description"
                                style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                                Mô tả ý tưởng <span style="color: #ef4444;">*</span>
                                <span style="font-weight: 400; color: var(--muted); font-size: 14px;">(Tối thiểu 50 ký tự)</span>
                            </label>
                            <textarea name="description" id="description" rows="6" required
                                placeholder="Mô tả chi tiết về ý tưởng của bạn..."
                                style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; font-size: 15px; font-family: inherit; resize: vertical;">{{ old('description', $idea->description) }}</textarea>
                            @error('description')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div style="margin-bottom: 24px;">
                            <label for="content" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                                Nội dung chi tiết <span style="font-weight: 400; color: var(--muted); font-size: 14px;">(Tùy chọn)</span>
                            </label>
                            <textarea name="content" id="content" rows="10"
                                placeholder="Thêm nội dung chi tiết về ý tưởng..."
                                style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; font-size: 15px; font-family: inherit; resize: vertical;">{{ old('content', $idea->content) }}</textarea>
                            @error('content')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- AI Business Consultant --}}
                        <div style="margin-bottom: 24px; padding: 24px; background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%); border: 2px solid #93c5fd; border-radius: 12px;" x-data="businessPlanAI()">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                <div>
                                    <h4 style="margin: 0 0 4px; font-weight: 700; color: #1e40af; font-size: 16px;">💼 Cố vấn Chiến lược Kinh doanh AI</h4>
                                    <p style="margin: 0; font-size: 13px; color: #1e3a8a;">Biến ý tưởng thành bản kế hoạch kinh doanh chuyên nghiệp để thuyết phục doanh nghiệp đầu tư.</p>
                                </div>
                                <button type="button" @click="analyzePlan" :disabled="loading" style="padding: 10px 18px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.2s ease; white-space: nowrap; flex-shrink: 0; opacity: 1;" :style="loading ? 'opacity: 0.6; cursor: not-allowed;' : ''" onmouseover="if (!this.disabled) { this.style.background='#1d4ed8'; this.style.transform='translateY(-1px)'; }" onmouseout="if (!this.disabled) { this.style.background='#2563eb'; this.style.transform='translateY(0)'; }">
                                    <span x-show="!loading">✨ Phân tích Kế hoạch</span>
                                    <span x-show="loading" style="display: inline-flex; align-items: center; gap: 6px;">
                                        <svg style="width: 14px; height: 14px; animation: spin 0.8s linear infinite;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle class="opacity-25" cx="12" cy="12" r="10"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Đang phân tích...
                                    </span>
                                </button>
                            </div>
                            
                            {{-- Loading state --}}
                            <div x-show="loading" style="text-align: center; padding: 16px; color: #2563eb;">
                                <div style="display: inline-block; width: 20px; height: 20px; border: 2px solid #bfdbfe; border-top-color: #2563eb; border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
                                <p style="margin: 8px 0 0; font-size: 13px;">🤖 AI đang nghiên cứu thị trường và lập kế hoạch...</p>
                            </div>

                            {{-- Result display --}}
                            <div x-show="result && !loading" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="margin-top: 16px;">
                                <div style="background: white; padding: 20px; border-radius: 8px; border: 1px solid #bfdbfe; max-height: 500px; overflow-y: auto;">
                                    <div class="prose" style="max-width: 100%;" x-html="result"></div>
                                </div>
                                <div style="margin-top: 12px; display: flex; gap: 8px; justify-content: flex-end;">
                                    <button type="button" @click="copyToContent" style="padding: 8px 16px; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">📋 Copy vào Nội dung chi tiết</button>
                                    <button type="button" @click="result = null" style="padding: 8px 16px; background: #6b7280; color: white; border: none; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer;">✕ Đóng</button>
                                </div>
                            </div>
                        </div>

                        {{-- Visibility --}}
                        @php
                            $vis = old('visibility', $idea->visibility);
                            if ($vis === 'team_only') { $vis = 'private'; }
                        @endphp
                        <div style="margin-bottom: 24px;">
                            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                                Chế độ công khai <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                                <label
                                    style="display: flex; align-items: center; gap: 8px; padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.borderColor='var(--brand-navy)';"
                                    onmouseout="this.style.borderColor='var(--border)';">
                                    <input type="radio" name="visibility" value="private"
                                        {{ $vis === 'private' ? 'checked' : '' }} required>
                                    <div>
                                        <div style="font-weight: 600; color: #0f172a;">Riêng tư</div>
                                        <div style="font-size: 12px; color: var(--muted);">Chỉ bạn và thành viên nhóm</div>
                                    </div>
                                </label>
                                <label
                                    style="display: flex; align-items: center; gap: 8px; padding: 12px 16px; border: 2px solid var(--border); border-radius: 8px; cursor: pointer; transition: all 0.2s;"
                                    onmouseover="this.style.borderColor='var(--brand-navy)';"
                                    onmouseout="this.style.borderColor='var(--border)';">
                                    <input type="radio" name="visibility" value="public"
                                        {{ $vis === 'public' ? 'checked' : '' }}>
                                    <div>
                                        <div style="font-weight: 600; color: #0f172a;">Công khai</div>
                                        <div style="font-size: 12px; color: var(--muted);">Mọi người có thể xem (sau khi duyệt)</div>
                                    </div>
                                </label>
                            </div>
                            @error('visibility')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Faculty & Category --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                            <div>
                                <label for="faculty_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                                    Khoa/Đơn vị
                                </label>
                                <select name="faculty_id" id="faculty_id"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; font-size: 15px; background: #fff;">
                                    <option value="">-- Chọn khoa --</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->id }}"
                                            {{ old('faculty_id', $idea->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                            {{ $faculty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('faculty_id')
                                    <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                                    Danh mục
                                </label>
                                <select name="category_id" id="category_id"
                                    style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; font-size: 15px; background: #fff;">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $idea->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- File Attachments (Add New) --}}
                        <div style="margin-bottom: 24px;">
                            <label for="attachments" style="display: block; margin-bottom: 8px; font-weight: 600; color: #0f172a;">
                                Thêm file đính kèm mới <span style="font-weight: 400; color: var(--muted); font-size: 14px;">(Tùy chọn, tối đa 10MB/file)</span>
                            </label>
                            <input type="file" name="attachments[]" id="attachments" multiple
                                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip"
                                style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; font-size: 15px; background: #fff;">
                            <div style="margin-top: 8px; font-size: 13px; color: var(--muted);">
                                <p style="margin: 4px 0;">Định dạng cho phép: JPG, PNG, PDF, DOC, DOCX, ZIP</p>
                                <p style="margin: 4px 0;">Bạn có thể chọn nhiều file cùng lúc (nhấn Ctrl/Cmd + Click)</p>
                            </div>
                            @error('attachments.*')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                            @error('attachments')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                            <div id="file-list" style="margin-top: 12px;"></div>
                        </div>

                        {{-- Buttons --}}
                        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px;">
                            <a href="{{ route('my-ideas.show', $idea->id) }}" class="btn btn-ghost"
                                style="padding: 12px 24px; font-weight: 600;">
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-weight: 600;">
                                Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Business Plan AI Function (Alpine.js)
        function businessPlanAI() {
            return {
                loading: false,
                result: null,

                analyzePlan() {
                    this.loading = true;
                    this.result = null;

                    // Lấy nội dung từ textarea description
                    const descTextarea = document.getElementById('description');
                    const description = descTextarea ? descTextarea.value.trim() : '';
                    
                    // Lấy nội dung chi tiết
                    const contentTextarea = document.getElementById('content');
                    const content = contentTextarea ? contentTextarea.value.trim() : '';
                    
                    // Kết hợp description và content
                    const ideaContent = (description + ' ' + content).trim();
                    
                    // Strip HTML tags nếu có
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = ideaContent;
                    const plainText = tempDiv.textContent || tempDiv.innerText || ideaContent;

                    if (!plainText || plainText.length < 20) {
                        this.loading = false;
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Lỗi', 'Mô tả ý tưởng quá ngắn. Vui lòng nhập ít nhất 20 ký tự.', 'warning');
                        } else {
                            alert('Mô tả ý tưởng quá ngắn. Vui lòng nhập ít nhất 20 ký tự.');
                        }
                        return;
                    }

                    fetch('{{ route('ai.student.business-plan') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ content: plainText })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.loading = false;
                        if(data.success) {
                            this.result = data.html;
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('Lỗi', data.error || 'Có lỗi xảy ra', 'error');
                            } else {
                                alert(data.error || 'Có lỗi xảy ra');
                            }
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        console.error(err);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Lỗi', 'Không thể kết nối tới máy chủ AI.', 'error');
                        } else {
                            alert('Không thể kết nối tới máy chủ AI.');
                        }
                    });
                },

                copyToContent() {
                    if (!this.result) return;
                    
                    // Lấy text thuần từ HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = this.result;
                    const plainText = tempDiv.textContent || tempDiv.innerText || '';
                    
                    // Thêm vào textarea content
                    const contentTextarea = document.getElementById('content');
                    if (contentTextarea) {
                        const currentContent = contentTextarea.value.trim();
                        const newContent = currentContent ? currentContent + '\n\n' + plainText : plainText;
                        contentTextarea.value = newContent;
                        
                        // Focus vào textarea
                        contentTextarea.focus();
                        contentTextarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Thành công', 'Đã copy kế hoạch kinh doanh vào phần Nội dung chi tiết!', 'success');
                        } else {
                            alert('Đã copy kế hoạch kinh doanh vào phần Nội dung chi tiết!');
                        }
                    }
                }
            }
        }

        // Hiển thị danh sách file đã chọn
        document.getElementById('attachments').addEventListener('change', function(e) {
            const fileList = document.getElementById('file-list');
            fileList.innerHTML = '';
            
            if (e.target.files.length > 0) {
                const list = document.createElement('div');
                list.style.cssText = 'display: flex; flex-direction: column; gap: 8px; margin-top: 8px;';
                
                Array.from(e.target.files).forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.style.cssText = 'display: flex; align-items: center; justify-content: space-between; padding: 8px 12px; background: #f3f4f6; border-radius: 6px;';
                    
                    const fileInfo = document.createElement('div');
                    fileInfo.style.cssText = 'flex: 1;';
                    
                    const fileName = document.createElement('div');
                    fileName.style.cssText = 'font-weight: 600; color: #0f172a; font-size: 14px;';
                    fileName.textContent = file.name;
                    
                    const fileSize = document.createElement('div');
                    fileSize.style.cssText = 'font-size: 12px; color: #6b7280; margin-top: 2px;';
                    fileSize.textContent = (file.size / 1024).toFixed(2) + ' KB';
                    
                    fileInfo.appendChild(fileName);
                    fileInfo.appendChild(fileSize);
                    
                    fileItem.appendChild(fileInfo);
                    list.appendChild(fileItem);
                });
                
                fileList.appendChild(list);
            }
        });
    </script>
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
@endpush

