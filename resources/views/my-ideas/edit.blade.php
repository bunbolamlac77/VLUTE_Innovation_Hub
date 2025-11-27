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
@endpush

