@extends('layouts.main')

@section('title', 'Tạo ý tưởng mới - VLUTE Innovation Hub')

@section('content')
    {{-- Breadcrumb --}}
    <section class="container" style="padding: 24px 0 16px;">
        <nav style="display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 14px;">
            <a href="/" style="color: var(--brand-navy);">Trang chủ</a>
            <span>/</span>
            <a href="{{ route('my-ideas.index') }}" style="color: var(--brand-navy);">Ý tưởng của tôi</a>
            <span>/</span>
            <span>Tạo mới</span>
        </nav>
    </section>

    {{-- Form --}}
    <section class="container" style="padding: 16px 0 64px;">
        <div style="max-width: 1100px; margin: 0 auto;">
            <div class="card" style="box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                <div class="card-body" style="padding: 48px;">
                    <h2 style="margin: 0 0 32px; font-size: 32px; color: #0f172a; font-weight: 800; letter-spacing: -0.01em;">Tạo ý tưởng mới</h2>

                    <form method="POST" action="{{ route('my-ideas.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Title --}}
                        <div style="margin-bottom: 28px;">
                            <label for="title" style="display: block; margin-bottom: 10px; font-weight: 600; color: #0f172a; font-size: 15px;">
                                Tiêu đề <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                placeholder="Nhập tiêu đề ý tưởng..."
                                style="width: 100%; padding: 14px 18px; border: 2px solid var(--border); border-radius: 12px; font-size: 16px; transition: all 0.2s ease; background: #fff;"
                                onfocus="this.style.borderColor='var(--brand-navy)'; this.style.boxShadow='0 0 0 4px rgba(10, 15, 90, 0.1)';"
                                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">
                            @error('title')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div style="margin-bottom: 28px;">
                            <label for="description"
                                style="display: block; margin-bottom: 10px; font-weight: 600; color: #0f172a; font-size: 15px;">
                                Mô tả ý tưởng <span style="color: #ef4444;">*</span>
                                <span style="font-weight: 400; color: var(--muted); font-size: 14px;">(Tối thiểu 50 ký tự)</span>
                            </label>
                            {{-- Lưu ý: id="editor" là quan trọng để script bắt được --}}
                            <textarea id="editor" name="description" rows="10" required
                                placeholder="Mô tả chi tiết về ý tưởng của bạn..."
                                style="width: 100%; padding: 14px 18px; border: 2px solid var(--border); border-radius: 12px; font-size: 16px; font-family: inherit; resize: vertical; transition: all 0.2s ease; background: #fff; line-height: 1.6;"
                                onfocus="this.style.borderColor='var(--brand-navy)'; this.style.boxShadow='0 0 0 4px rgba(10, 15, 90, 0.1)';"
                                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">{{ old('description') }}</textarea>
                            @error('description')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div style="margin-bottom: 28px;">
                            <label for="content" style="display: block; margin-bottom: 10px; font-weight: 600; color: #0f172a; font-size: 15px;">
                                Nội dung chi tiết <span style="font-weight: 400; color: var(--muted); font-size: 14px;">(Tùy chọn)</span>
                            </label>
                            <textarea name="content" id="content" rows="10"
                                placeholder="Thêm nội dung chi tiết về ý tưởng..."
                                style="width: 100%; padding: 14px 18px; border: 2px solid var(--border); border-radius: 12px; font-size: 16px; font-family: inherit; resize: vertical; transition: all 0.2s ease; background: #fff; line-height: 1.6;"
                                onfocus="this.style.borderColor='var(--brand-navy)'; this.style.boxShadow='0 0 0 4px rgba(10, 15, 90, 0.1)';"
                                onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">{{ old('content') }}</textarea>
                            @error('content')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Visibility --}}
                        <div style="margin-bottom: 28px;">
                            <label style="display: block; margin-bottom: 12px; font-weight: 600; color: #0f172a; font-size: 15px;">
                                Chế độ công khai <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
                                <label
                                    style="display: flex; align-items: flex-start; gap: 12px; padding: 18px 20px; border: 2px solid var(--border); border-radius: 12px; cursor: pointer; transition: all 0.3s ease; background: #fff;"
                                    onmouseover="if(!this.querySelector('input[type=radio]:checked')) { this.style.borderColor='var(--brand-navy)'; this.style.boxShadow='0 4px 12px rgba(10, 15, 90, 0.1)'; this.style.transform='translateY(-2px)'; }"
                                    onmouseout="if(!this.querySelector('input[type=radio]:checked')) { this.style.borderColor='var(--border)'; this.style.boxShadow='none'; this.style.transform='translateY(0)'; }">
                                    <input type="radio" name="visibility" value="private"
                                        {{ old('visibility', 'private') === 'private' ? 'checked' : '' }} required
                                        onchange="document.querySelectorAll('label[for^=visibility]').forEach(l => { if(l.querySelector('input[type=radio]:checked')) { l.style.borderColor='var(--brand-navy)'; l.style.background='rgba(10, 15, 90, 0.05)'; l.style.boxShadow='0 4px 12px rgba(10, 15, 90, 0.15)'; } else { l.style.borderColor='var(--border)'; l.style.background='#fff'; l.style.boxShadow='none'; } });">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 700; color: #0f172a; font-size: 15px; margin-bottom: 6px;">Riêng tư</div>
                                        <div style="font-size: 13px; color: var(--muted); line-height: 1.5;">Chỉ bạn và thành viên nhóm</div>
                                    </div>
                                </label>

                                <label
                                    style="display: flex; align-items: flex-start; gap: 12px; padding: 18px 20px; border: 2px solid var(--border); border-radius: 12px; cursor: pointer; transition: all 0.3s ease; background: #fff;"
                                    onmouseover="if(!this.querySelector('input[type=radio]:checked')) { this.style.borderColor='var(--brand-navy)'; this.style.boxShadow='0 4px 12px rgba(10, 15, 90, 0.1)'; this.style.transform='translateY(-2px)'; }"
                                    onmouseout="if(!this.querySelector('input[type=radio]:checked')) { this.style.borderColor='var(--border)'; this.style.boxShadow='none'; this.style.transform='translateY(0)'; }">
                                    <input type="radio" name="visibility" value="public"
                                        {{ old('visibility') === 'public' ? 'checked' : '' }}
                                        onchange="document.querySelectorAll('label[for^=visibility]').forEach(l => { if(l.querySelector('input[type=radio]:checked')) { l.style.borderColor='var(--brand-navy)'; l.style.background='rgba(10, 15, 90, 0.05)'; l.style.boxShadow='0 4px 12px rgba(10, 15, 90, 0.15)'; } else { l.style.borderColor='var(--border)'; l.style.background='#fff'; l.style.boxShadow='none'; } });">
                                    <div style="flex: 1;">
                                        <div style="font-weight: 700; color: #0f172a; font-size: 15px; margin-bottom: 6px;">Công khai</div>
                                        <div style="font-size: 13px; color: var(--muted); line-height: 1.5;">Mọi người có thể xem (sau khi duyệt)</div>
                                    </div>
                                </label>
                            </div>
                            @error('visibility')
                                <div style="color: #ef4444; font-size: 14px; margin-top: 4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Faculty & Category --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px;">
                            <div>
                                <label for="faculty_id" style="display: block; margin-bottom: 10px; font-weight: 600; color: #0f172a; font-size: 15px;">
                                    Khoa/Đơn vị
                                </label>
                                <select name="faculty_id" id="faculty_id"
                                    style="width: 100%; padding: 14px 18px; border: 2px solid var(--border); border-radius: 12px; font-size: 16px; background: #fff; cursor: pointer; transition: all 0.2s ease;"
                                    onfocus="this.style.borderColor='var(--brand-navy)'; this.style.boxShadow='0 0 0 4px rgba(10, 15, 90, 0.1)';"
                                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">
                                    <option value="">-- Chọn khoa --</option>
                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                            {{ $faculty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('faculty_id')
                                    <div style="color: #ef4444; font-size: 14px; margin-top: 6px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" style="display: block; margin-bottom: 10px; font-weight: 600; color: #0f172a; font-size: 15px;">
                                    Danh mục
                                </label>
                                <select name="category_id" id="category_id"
                                    style="width: 100%; padding: 14px 18px; border: 2px solid var(--border); border-radius: 12px; font-size: 16px; background: #fff; cursor: pointer; transition: all 0.2s ease;"
                                    onfocus="this.style.borderColor='var(--brand-navy)'; this.style.boxShadow='0 0 0 4px rgba(10, 15, 90, 0.1)';"
                                    onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none';">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div style="color: #ef4444; font-size: 14px; margin-top: 6px;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Image Banner: Upload hoặc Link (đơn giản giống File đính kèm) --}}
                        <div style="margin-bottom: 28px;" x-data="{ type: '{{ old('image_type', 'file') }}' }">
                            <label style="display:block; margin-bottom:10px; font-weight:600; color:#0f172a; font-size:15px;">
                                Hình ảnh đại diện (Banner)
                            </label>

                            <div style="display:flex; gap:16px; margin-bottom:8px; font-size:14px; color:#4b5563;">
                                <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                                    <input type="radio" name="image_type" value="file" x-model="type">
                                    <span>Tải ảnh lên</span>
                                </label>
                                <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                                    <input type="radio" name="image_type" value="url" x-model="type">
                                    <span>Dùng link ảnh online</span>
                                </label>
                            </div>

                            {{-- Input Upload --}}
                            <div x-show="type === 'file'">
                                <input type="file" name="image_file"
                                       accept="image/*"
                                       style="width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:10px; font-size:14px; background:#f9fafb;">
                                <div style="margin-top:6px; font-size:12px; color:var(--muted);">
                                    Khuyến nghị tỉ lệ ngang (16:9), dung lượng ≤ 5MB.
                                </div>
                            </div>

                            {{-- Input URL --}}
                            <div x-show="type === 'url'" style="display:none; margin-top:4px;">
                                <input type="url" name="image_url" value="{{ old('image_url') }}"
                                       placeholder="https://example.com/image.jpg"
                                       style="width:100%; padding:10px 12px; border:1px solid var(--border); border-radius:10px; font-size:14px;">
                                <div style="margin-top:6px; font-size:12px; color:var(--muted);">
                                    Dán liên kết ảnh từ Google Drive, Imgur, v.v...
                                </div>
                            </div>

                            @error('image_file')
                                <div style="color:#ef4444; font-size:14px; margin-top:4px;">{{ $message }}</div>
                            @enderror
                            @error('image_url')
                                <div style="color:#ef4444; font-size:14px; margin-top:4px;">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- AI Description Input --}}
                        <div style="margin-bottom: 32px; padding: 28px; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 50%, #fde68a 100%); border: 2px solid #fbbf24; border-radius: 16px; box-shadow: 0 4px 20px rgba(251, 191, 36, 0.15), 0 0 0 1px rgba(251, 191, 36, 0.1); position: relative; overflow: hidden;">
                            {{-- Decorative background pattern --}}
                            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(251, 191, 36, 0.1) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
                            <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
                            
                            <div style="position: relative; z-index: 1;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">
                                        🤖
                                    </div>
                                    <div>
                                        <label for="ai-description" style="display: block; margin: 0; font-weight: 800; color: #78350f; font-size: 18px; letter-spacing: -0.01em;">
                                            Mô tả ý tưởng dành cho AI
                                        </label>
                                        <div style="display: inline-block; margin-top: 4px; padding: 2px 8px; background: rgba(245, 158, 11, 0.2); border-radius: 4px; font-size: 11px; font-weight: 600; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Dữ liệu đầu vào cho AI
                                        </div>
                                    </div>
                                </div>
                                
                                <p style="margin: 0 0 18px; font-size: 14px; color: #78350f; line-height: 1.6; padding-left: 50px;">
                                    Nhập mô tả chi tiết về ý tưởng của bạn tại đây. Dữ liệu này sẽ được sử dụng bởi các AI để <strong style="color: #92400e;">tìm kiếm thêm thông tin</strong> và đưa ra gợi ý chính xác hơn.
                                </p>
                                
                                <textarea id="ai-description" name="ai_description" rows="7"
                                    placeholder="Ví dụ: Tôi muốn tạo một ứng dụng di động để quản lý việc học tập của sinh viên. Ứng dụng cần có tính năng nhắc nhở deadline, quản lý lịch học, và theo dõi tiến độ học tập..."
                                    style="width: 100%; padding: 16px 20px; border: 2px solid #fbbf24; border-radius: 12px; font-size: 15px; font-family: inherit; resize: vertical; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); background: #ffffff; line-height: 1.7; color: #1f2937; box-shadow: 0 2px 8px rgba(251, 191, 36, 0.1);"
                                    onfocus="this.style.borderColor='#f59e0b'; this.style.boxShadow='0 0 0 4px rgba(245, 158, 11, 0.15), 0 4px 12px rgba(245, 158, 11, 0.2)'; this.style.transform='translateY(-1px)';"
                                    onblur="this.style.borderColor='#fbbf24'; this.style.boxShadow='0 2px 8px rgba(251, 191, 36, 0.1)'; this.style.transform='translateY(0)';">{{ old('ai_description') }}</textarea>
                                
                                <div style="margin-top: 12px; display: flex; align-items: center; gap: 8px; padding: 10px 14px; background: rgba(255, 255, 255, 0.6); border-radius: 8px; border-left: 3px solid #f59e0b;">
                                    <span style="font-size: 16px;">💡</span>
                                    <span style="font-size: 13px; color: #78350f; line-height: 1.5;">
                                        <strong style="color: #92400e;">Mẹo:</strong> Mô tả càng chi tiết, AI sẽ đưa ra gợi ý càng chính xác và hữu ích.
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Tech Stack Advisor --}}
                        <div style="margin-bottom: 28px; padding: 24px; background: linear-gradient(135deg, #eef2ff 0%, #f3e8ff 100%); border: 2px solid #c7d2fe; border-radius: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                <div>
                                    <h4 style="margin: 0 0 4px; font-weight: 700; color: #4c1d95; font-size: 16px;">🛠️ Kiến trúc sư Công nghệ AI</h4>
                                    <p style="margin: 0; font-size: 13px; color: #6b21a8;">Chưa biết dùng công nghệ gì? Hãy nhập mô tả ý tưởng và hỏi AI gợi ý.</p>
                                </div>
                                <button type="button" onclick="askTechAdvisor()" 
                                    style="padding: 12px 24px; background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); white-space: nowrap; flex-shrink: 0; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.3), 0 0 0 1px rgba(124, 58, 237, 0.1); position: relative; overflow: hidden;" 
                                    onmouseover="this.style.background='linear-gradient(135deg, #6d28d9 0%, #5b21b6 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(124, 58, 237, 0.4), 0 0 0 1px rgba(124, 58, 237, 0.15)';" 
                                    onmouseout="this.style.background='linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(124, 58, 237, 0.3), 0 0 0 1px rgba(124, 58, 237, 0.1)';"
                                    onmousedown="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(124, 58, 237, 0.3)';"
                                    onmouseup="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(124, 58, 237, 0.4), 0 0 0 1px rgba(124, 58, 237, 0.15)';">
                                    <span style="display: inline-flex; align-items: center; gap: 8px; position: relative; z-index: 1;">
                                        <span style="font-size: 16px; filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));">✨</span>
                                        <span>Gợi ý Tech Stack</span>
                                    </span>
                                </button>
                            </div>
                            <div id="tech-loading" class="hidden" style="text-align: center; padding: 16px; color: #7c3aed;"><div style="display: inline-block; width: 20px; height: 20px; border: 2px solid #e9d5ff; border-top-color: #7c3aed; border-radius: 50%; animation: spin 0.8s linear infinite;"></div><p style="margin: 8px 0 0; font-size: 13px;">🤖 Đang phân tích yêu cầu kỹ thuật...</p></div>
                            <div id="tech-stack-result" class="hidden" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 16px;"></div>
                        </div>

                        {{-- AI Business Consultant --}}
                        <div style="margin-bottom: 28px; padding: 24px; background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%); border: 2px solid #93c5fd; border-radius: 12px;" x-data="businessPlanAI()">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                                <div>
                                    <h4 style="margin: 0 0 4px; font-weight: 700; color: #1e40af; font-size: 16px;">💼 Cố vấn Chiến lược Kinh doanh AI</h4>
                                    <p style="margin: 0; font-size: 13px; color: #1e3a8a;">Biến ý tưởng thành bản kế hoạch kinh doanh chuyên nghiệp để thuyết phục doanh nghiệp đầu tư.</p>
                                </div>
                                <button type="button" @click="analyzePlan" :disabled="loading" 
                                    style="padding: 12px 24px; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; border: none; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); white-space: nowrap; flex-shrink: 0; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3), 0 0 0 1px rgba(37, 99, 235, 0.1); position: relative; overflow: hidden;" 
                                    :style="loading ? 'opacity: 0.7; cursor: not-allowed; box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);' : ''" 
                                    onmouseover="if (!this.disabled) { this.style.background='linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(37, 99, 235, 0.4), 0 0 0 1px rgba(37, 99, 235, 0.15)'; }" 
                                    onmouseout="if (!this.disabled) { this.style.background='linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 14px rgba(37, 99, 235, 0.3), 0 0 0 1px rgba(37, 99, 235, 0.1)'; }"
                                    onmousedown="if (!this.disabled) { this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(37, 99, 235, 0.3)'; }"
                                    onmouseup="if (!this.disabled) { this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(37, 99, 235, 0.4), 0 0 0 1px rgba(37, 99, 235, 0.15)'; }">
                                    <span x-show="!loading" style="display: inline-flex; align-items: center; gap: 8px; position: relative; z-index: 1;">
                                        <span style="font-size: 16px; filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));">✨</span>
                                        <span>Phân tích Kế hoạch</span>
                                    </span>
                                    <span x-show="loading" style="display: inline-flex; align-items: center; gap: 8px; position: relative; z-index: 1;">
                                        <svg style="width: 16px; height: 16px; animation: spin 0.8s linear infinite;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle class="opacity-25" cx="12" cy="12" r="10"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span>Đang phân tích...</span>
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

                        {{-- File Attachments --}}
                        <div style="margin-bottom: 28px;">
                            <label for="attachments" style="display: block; margin-bottom: 10px; font-weight: 600; color: #0f172a; font-size: 15px;">
                                File đính kèm <span style="font-weight: 400; color: var(--muted); font-size: 14px;">(Tùy chọn, tối đa 10MB/file)</span>
                            </label>
                            <div style="border: 2px dashed var(--border); border-radius: 12px; padding: 24px; background: #f9fafb; transition: all 0.3s ease;"
                                id="file-upload-area"
                                onmouseover="this.style.borderColor='var(--brand-navy)'; this.style.background='#f0f4ff';"
                                onmouseout="this.style.borderColor='var(--border)'; this.style.background='#f9fafb';">
                                <input type="file" name="attachments[]" id="attachments" multiple
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip"
                                    style="width: 100%; padding: 12px; border: none; border-radius: 8px; font-size: 15px; background: transparent; cursor: pointer;">
                                <div style="margin-top: 12px; font-size: 13px; color: var(--muted); text-align: center;">
                                    <p style="margin: 4px 0; font-weight: 600;">📎 Kéo thả file vào đây hoặc click để chọn</p>
                                    <p style="margin: 4px 0;">Định dạng: JPG, PNG, PDF, DOC, DOCX, ZIP</p>
                                    <p style="margin: 4px 0;">Bạn có thể chọn nhiều file cùng lúc</p>
                                </div>
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
                        <div style="display: flex; gap: 16px; justify-content: flex-end; margin-top: 40px; padding-top: 32px; border-top: 2px solid var(--brand-gray-100);">
                            <a href="{{ route('my-ideas.index') }}" class="btn btn-ghost"
                                style="padding: 14px 28px; font-weight: 700; font-size: 16px; border-radius: 12px; background: #f3f4f6; color: #374151; border: 2px solid #e5e7eb; transition: all 0.2s ease;"
                                onmouseover="this.style.background='#e5e7eb'; this.style.transform='translateY(-1px)';"
                                onmouseout="this.style.background='#f3f4f6'; this.style.transform='translateY(0)';">
                                Hủy
                            </a>
                            <button type="submit" class="btn btn-primary" 
                                style="padding: 14px 32px; font-weight: 700; font-size: 16px; border-radius: 12px; background: var(--brand-navy); color: #fff; border: none; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(10, 15, 90, 0.2);"
                                onmouseover="this.style.background='#080c3d'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 16px rgba(10, 15, 90, 0.3)';"
                                onmouseout="this.style.background='var(--brand-navy)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(10, 15, 90, 0.2)';">
                                ✨ Tạo ý tưởng
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
                    // Reset states
                    this.loading = true;
                    this.result = null;
                    
                    // Ẩn kết quả cũ nếu có
                    const resultDiv = document.querySelector('[x-show="result && !loading"]');
                    if (resultDiv) {
                        resultDiv.style.display = 'none';
                    }

                    // Lấy nội dung từ khung mô tả dành cho AI
                    const aiDescTextarea = document.getElementById('ai-description');
                    let description = '';
                    
                    if (aiDescTextarea) {
                        description = aiDescTextarea.value.trim() || '';
                    }
                    
                    // Nếu khung AI trống, thử lấy từ mô tả chính (fallback)
                    if (!description || description.length < 20) {
                        const descTextarea = document.getElementById('editor');
                        if (descTextarea) {
                            // Nếu có CKEditor instance, lấy data từ editor
                            if (window.CKEditorInstance && window.CKEditorInstance.getData) {
                                description = window.CKEditorInstance.getData();
                            } else {
                                description = descTextarea.value || descTextarea.textContent || '';
                            }
                        }
                    }
                    
                    // Lấy nội dung chi tiết để bổ sung thông tin
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
                        // Focus vào khung AI description
                        if (aiDescTextarea) {
                            aiDescTextarea.focus();
                            aiDescTextarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Lỗi', 'Mô tả ý tưởng quá ngắn. Vui lòng nhập ít nhất 20 ký tự vào khung "Mô tả ý tưởng dành cho AI".', 'warning');
                        } else {
                            alert('Mô tả ý tưởng quá ngắn. Vui lòng nhập ít nhất 20 ký tự vào khung "Mô tả ý tưởng dành cho AI".');
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
                    .then(res => {
                        if (!res.ok) {
                            return res.json().then(err => {
                                throw new Error(err.error || 'Network response was not ok');
                            });
                        }
                        return res.json();
                    })
                    .then(data => {
                        this.loading = false;
                        if(data.success && data.html) {
                            this.result = data.html;
                        } else {
                            const errorMsg = data.error || 'Có lỗi xảy ra khi xử lý yêu cầu';
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('Lỗi', errorMsg, 'error');
                            } else {
                                alert(errorMsg);
                            }
                        }
                    })
                    .catch(err => {
                        this.loading = false;
                        console.error('Business Plan AI Error:', err);
                        const errorMsg = err.message || 'Không thể kết nối tới máy chủ AI. Vui lòng thử lại sau.';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Lỗi', errorMsg, 'error');
                        } else {
                            alert('Lỗi: ' + errorMsg);
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

        // Tech Stack Advisor Function
        function askTechAdvisor() {
            // Reset kết quả cũ
            const container = document.getElementById('tech-stack-result');
            if (container) {
                container.innerHTML = '';
                container.classList.add('hidden');
            }
            
            // Lấy nội dung từ khung mô tả dành cho AI
            const aiDescTextarea = document.getElementById('ai-description');
            let content = '';
            
            if (aiDescTextarea) {
                content = aiDescTextarea.value.trim() || '';
            }
            
            // Nếu khung AI trống, thử lấy từ mô tả chính (fallback)
            if (!content || content.length < 20) {
                const descTextarea = document.getElementById('editor');
                if (descTextarea) {
                    // Nếu có CKEditor instance, lấy data từ editor
                    if (window.CKEditorInstance && window.CKEditorInstance.getData) {
                        content = window.CKEditorInstance.getData();
                    } else {
                        content = descTextarea.value || descTextarea.textContent || '';
                    }
                }
            }

            // Strip HTML tags nếu có
            if (content) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = content;
                content = tempDiv.textContent || tempDiv.innerText || content;
            }

            if (!content || content.length < 20) {
                alert('Vui lòng nhập mô tả ý tưởng chi tiết hơn vào khung "Mô tả ý tưởng dành cho AI" (ít nhất 20 ký tự).');
                // Focus vào khung AI description
                if (aiDescTextarea) {
                    aiDescTextarea.focus();
                    aiDescTextarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }

            document.getElementById('tech-loading').classList.remove('hidden');
            document.getElementById('tech-stack-result').classList.add('hidden');

            fetch('{{ route("ai.tech_stack") }}', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ content: content })
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => {
                        throw new Error(err.error || 'Network response was not ok');
                    });
                }
                return res.json();
            })
            .then(res => {
                // Kiểm tra nếu có lỗi trong response
                if (res.error) {
                    throw new Error(res.error);
                }

                if (!res.data) {
                    throw new Error('Dữ liệu trả về không hợp lệ');
                }

                const data = res.data;
                const container = document.getElementById('tech-stack-result');
                container.innerHTML = '';

                const createCard = (title, icon, text) => {
                    if (!text) return '';
                    return `
                        <div style="background: white; padding: 16px; border-radius: 8px; border: 1px solid #e9d5ff; transition: all 0.2s ease;" 
                             onmouseover="this.style.boxShadow='0 4px 12px rgba(124, 58, 237, 0.15)'; this.style.transform='translateY(-2px)';"
                             onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)';">
                            <div style="font-weight: 700; color: #4c1d95; font-size: 12px; text-transform: uppercase; margin-bottom: 6px;">
                                ${icon} ${title}
                            </div>
                            <div style="font-size: 13px; color: #6b7280; line-height: 1.5;">
                                ${text}
                            </div>
                        </div>
                    `;
                };

                let html = createCard('Frontend', '💻', data.frontend || 'Chưa có gợi ý');
                html += createCard('Backend', '⚙️', data.backend || 'Chưa có gợi ý');
                html += createCard('Database', '🗄️', data.database || 'Chưa có gợi ý');
                html += createCard('Mobile', '📱', data.mobile || 'Không áp dụng');
                html += createCard('Hardware/IoT', '🔌', data.hardware || 'Không áp dụng');

                const adviceHtml = `
                    <div style="grid-column: 1 / -1; background: #fef3c7; padding: 16px; border-radius: 8px; border: 1px solid #fcd34d;">
                        <div style="font-weight: 700; color: #92400e; font-size: 12px; text-transform: uppercase; margin-bottom: 6px;">
                            💡 Lời khuyên
                        </div>
                        <div style="font-size: 13px; color: #b45309; line-height: 1.5;">
                            ${data.advice || 'Không có lời khuyên'}
                        </div>
                    </div>
                `;

                container.innerHTML = html + adviceHtml;
                container.classList.remove('hidden');
            })
            .catch(err => {
                console.error('Error:', err);
                const container = document.getElementById('tech-stack-result');
                container.innerHTML = `
                    <div style="grid-column: 1 / -1; background: #fee2e2; padding: 16px; border-radius: 8px; border: 1px solid #fca5a5; text-align: center;">
                        <div style="font-weight: 700; color: #991b1b; font-size: 14px; margin-bottom: 8px;">
                            ❌ Lỗi
                        </div>
                        <div style="font-size: 13px; color: #7f1d1d; line-height: 1.5;">
                            ${err.message || 'Có lỗi xảy ra khi kết nối với AI. Vui lòng thử lại sau.'}
                        </div>
                    </div>
                `;
                container.classList.remove('hidden');
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Lỗi', err.message || 'Có lỗi xảy ra khi kết nối với AI.', 'error');
                } else {
                    alert('Lỗi: ' + (err.message || 'Có lỗi xảy ra khi kết nối với AI.'));
                }
            })
            .finally(() => {
                document.getElementById('tech-loading').classList.add('hidden');
            });
        }

        // Lưu trữ danh sách file đã chọn
        let selectedFiles = [];
        const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB

        // Hiển thị danh sách file đã chọn
        function renderFileList() {
            const fileList = document.getElementById('file-list');
            const uploadArea = document.getElementById('file-upload-area');
            const fileInput = document.getElementById('attachments');
            
            fileList.innerHTML = '';
            
            if (selectedFiles.length > 0) {
                uploadArea.style.borderColor = 'var(--brand-green)';
                uploadArea.style.borderStyle = 'solid';
                uploadArea.style.background = '#f0fdf4';
                
                const list = document.createElement('div');
                list.style.cssText = 'display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; margin-top: 16px;';
                
                selectedFiles.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.style.cssText = 'display: flex; align-items: center; gap: 12px; padding: 14px 16px; background: #fff; border: 1px solid var(--border); border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: all 0.2s ease;';
                    
                    fileItem.onmouseover = function() {
                        this.style.transform = 'translateY(-2px)';
                        this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                    };
                    fileItem.onmouseout = function() {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
                    };
                    
                    // Icon theo loại file
                    let icon = '📄';
                    if (file.type.includes('image')) icon = '🖼️';
                    else if (file.type.includes('pdf')) icon = '📕';
                    else if (file.type.includes('word') || file.type.includes('document')) icon = '📘';
                    else if (file.type.includes('zip') || file.type.includes('archive')) icon = '📦';
                    
                    const iconDiv = document.createElement('div');
                    iconDiv.style.cssText = 'font-size: 28px; flex-shrink: 0;';
                    iconDiv.textContent = icon;
                    
                    const fileInfo = document.createElement('div');
                    fileInfo.style.cssText = 'flex: 1; min-width: 0;';
                    
                    const fileName = document.createElement('div');
                    fileName.style.cssText = 'font-weight: 600; color: #0f172a; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;';
                    fileName.textContent = file.name;
                    
                    const fileSize = document.createElement('div');
                    fileSize.style.cssText = 'font-size: 12px; margin-top: 4px;';
                    const sizeKB = (file.size / 1024).toFixed(2);
                    const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                    
                    // Kiểm tra kích thước file
                    if (file.size > MAX_FILE_SIZE) {
                        fileSize.style.color = '#ef4444';
                        fileSize.textContent = (file.size > 1024 * 1024 ? sizeMB + ' MB' : sizeKB + ' KB') + ' (Vượt quá 10MB)';
                        fileItem.style.borderColor = '#ef4444';
                    } else {
                        fileSize.style.color = '#6b7280';
                        fileSize.textContent = file.size > 1024 * 1024 ? sizeMB + ' MB' : sizeKB + ' KB';
                    }
                    
                    // Nút xóa file
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.style.cssText = 'background: #ef4444; color: #fff; border: none; border-radius: 6px; padding: 6px 12px; font-size: 12px; cursor: pointer; font-weight: 600; transition: all 0.2s ease; flex-shrink: 0;';
                    removeBtn.textContent = '✕ Xóa';
                    removeBtn.onmouseover = function() {
                        this.style.background = '#dc2626';
                        this.style.transform = 'scale(1.05)';
                    };
                    removeBtn.onmouseout = function() {
                        this.style.background = '#ef4444';
                        this.style.transform = 'scale(1)';
                    };
                    removeBtn.onclick = function() {
                        selectedFiles.splice(index, 1);
                        updateFileInput();
                        renderFileList();
                    };
                    
                    fileInfo.appendChild(fileName);
                    fileInfo.appendChild(fileSize);
                    
                    fileItem.appendChild(iconDiv);
                    fileItem.appendChild(fileInfo);
                    fileItem.appendChild(removeBtn);
                    list.appendChild(fileItem);
                });
                
                fileList.appendChild(list);
            } else {
                uploadArea.style.borderColor = 'var(--border)';
                uploadArea.style.borderStyle = 'dashed';
                uploadArea.style.background = '#f9fafb';
            }
        }

        // Cập nhật file input với danh sách file mới
        function updateFileInput() {
            const fileInput = document.getElementById('attachments');
            const dataTransfer = new DataTransfer();
            
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            
            fileInput.files = dataTransfer.files;
        }

        // Xử lý khi chọn file
        document.getElementById('attachments').addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            const validFiles = [];
            const invalidFiles = [];
            
            files.forEach(file => {
                // Kiểm tra kích thước
                if (file.size > MAX_FILE_SIZE) {
                    invalidFiles.push(file);
                    alert(`File "${file.name}" (${(file.size / 1024 / 1024).toFixed(2)} MB) vượt quá giới hạn 10MB. File này sẽ không được thêm.`);
                } else {
                    // Kiểm tra xem file đã tồn tại chưa
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!exists) {
                        validFiles.push(file);
                    }
                }
            });
            
            // Thêm các file hợp lệ vào danh sách
            selectedFiles = [...selectedFiles, ...validFiles];
            updateFileInput();
            renderFileList();
        });
        
        // Style cho radio buttons khi được chọn
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[type="radio"][name="visibility"]');
            radios.forEach(radio => {
                if (radio.checked) {
                    const label = radio.closest('label');
                    if (label) {
                        label.style.borderColor = 'var(--brand-navy)';
                        label.style.background = 'rgba(10, 15, 90, 0.05)';
                        label.style.boxShadow = '0 4px 12px rgba(10, 15, 90, 0.15)';
                    }
                }
                radio.addEventListener('change', function() {
                    document.querySelectorAll('label input[type="radio"][name="visibility"]').forEach(r => {
                        const l = r.closest('label');
                        if (r.checked) {
                            l.style.borderColor = 'var(--brand-navy)';
                            l.style.background = 'rgba(10, 15, 90, 0.05)';
                            l.style.boxShadow = '0 4px 12px rgba(10, 15, 90, 0.15)';
                        } else {
                            l.style.borderColor = 'var(--border)';
                            l.style.background = '#fff';
                            l.style.boxShadow = 'none';
                        }
                    });
                });
            });
        });
    </script>
    <style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .hidden {
            display: none !important;
        }
        /* Responsive cho mobile */
        @media (max-width: 768px) {
            .container > div[style*="max-width: 1100px"] {
                max-width: 100% !important;
                padding: 0 16px;
            }
            .card-body[style*="padding: 48px"] {
                padding: 32px 24px !important;
            }
            h2[style*="font-size: 32px"] {
                font-size: 24px !important;
            }
            div[style*="grid-template-columns: repeat(3, 1fr)"] {
                grid-template-columns: 1fr !important;
            }
            div[style*="grid-template-columns: 1fr 1fr"] {
                grid-template-columns: 1fr !important;
            }
            button[style*="padding: 14px 32px"],
            a[style*="padding: 14px 28px"] {
                padding: 12px 20px !important;
                font-size: 14px !important;
            }
        }
    </style>
@endpush

@push('scripts')
    {{-- Load thư viện CKEditor từ CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script>
        // Khởi tạo CKEditor và đảm bảo đồng bộ dữ liệu về textarea trước khi submit
        (function() {
            const descTextarea = document.querySelector('#editor');
            if (!descTextarea) return;

            ClassicEditor
                .create(descTextarea, {
                    toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ],
                    placeholder: 'Nhập mô tả chi tiết về ý tưởng của bạn tại đây...'
                })
                .then(editor => {
                    // Lưu editor instance vào window để script khác có thể truy cập
                    window.CKEditorInstance = editor;
                    
                    // Đặt min-height cho vùng soạn thảo
                    editor.editing.view.change(writer => {
                        writer.setStyle('min-height', '300px', editor.editing.view.document.getRoot());
                    });

                    // Luôn đồng bộ nội dung CKEditor về textarea mỗi khi thay đổi
                    editor.model.document.on('change:data', () => {
                        descTextarea.value = editor.getData();
                    });

                    // Đồng bộ dữ liệu ngay trước khi submit (phòng hờ)
                    const form = descTextarea.closest('form');
                    if (form) {
                        form.addEventListener('submit', function() {
                            descTextarea.value = editor.getData();
                        });
                    }
                })
                .catch(error => { console.error(error); });
        })();
    </script>
@endpush
