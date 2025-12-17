@extends('layouts.main')

@section('title', 'Đăng Thách thức mới - Doanh nghiệp')

@section('content')
<section class="container" style="padding: 32px 0; max-width: 1180px;">
    <div style="display:flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 16px; flex-wrap: wrap;">
        <div>
            <h1 style="margin:0; font-size: 24px; font-weight: 800; color:#0f172a;">Đăng Thách thức mới</h1>
            <div style="color:#64748b; font-size: 13px;">Hãy mô tả vấn đề thực tiễn để thu hút giải pháp từ sinh viên</div>
        </div>
        <div>
            <a href="{{ route('enterprise.challenges.index') }}" class="btn btn-ghost">← Quay lại danh sách</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="my-4 p-3" style="background:#fef2f2; border-left:4px solid #ef4444; border-radius:8px; color:#991b1b;">
            <ul style="margin:0 0 0 16px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('enterprise.challenges.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid" style="display:grid; gap:16px;">
                    <div>
                        <label for="title" class="form-label">Tên Thách thức / Vấn đề</label>
                        <input id="title" name="title" type="text" class="form-input" required autofocus placeholder="Ví dụ: Tối ưu quy trình đóng gói sản phẩm" value="{{ old('title') }}">
                    </div>

                    <div>
                        <label for="description" class="form-label">Tóm tắt ngắn</label>
                        <textarea id="description" name="description" rows="4" class="form-textarea" required placeholder="Mô tả ngắn gọn">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="problem_statement" class="form-label">Bối cảnh & vấn đề</label>
                        <textarea id="problem_statement" name="problem_statement" rows="8" class="form-textarea" placeholder="Mô tả chi tiết bối cảnh, vấn đề doanh nghiệp đang gặp phải...">{{ old('problem_statement') }}</textarea>
                    </div>

                    <div>
                        <label for="requirements" class="form-label">Yêu cầu & phạm vi</label>
                        <textarea id="requirements" name="requirements" rows="8" class="form-textarea" placeholder="Yêu cầu đầu ra, tiêu chí đánh giá, phạm vi dữ liệu...">{{ old('requirements') }}</textarea>
                    </div>

                    <div class="grid" style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <label for="reward" class="form-label">Phần thưởng (Hiện kim/Cơ hội)</label>
                            <input id="reward" name="reward" type="text" class="form-input" required placeholder="Ví dụ: 5.000.000 VNĐ" value="{{ old('reward') }}">
                        </div>
                        <div>
                            <label for="deadline" class="form-label">Hạn chót nộp bài</label>
                            <input id="deadline" name="deadline" type="datetime-local" class="form-input" required value="{{ old('deadline') }}">
                        </div>
                    </div>

                    <div class="grid" style="display:grid; grid-template-columns: 1fr; gap: 16px;">
                        <div x-data="{ type: '{{ old('image_type', 'file') }}' }">
                            <label class="form-label">Ảnh bìa (Banner/Thumbnail)</label>

                            <div style="display:flex; gap:12px; margin-bottom:6px; font-size:13px; color:#4b5563;">
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
                                <input id="image_file" name="image_file" type="file" class="form-input" accept="image/*">
                                <div class="muted" style="margin-top:6px; font-size:12px;">
                                    Khuyến nghị tỉ lệ 16:9, dung lượng ≤ 5MB.
                                </div>
                            </div>

                            {{-- Input URL --}}
                            <div x-show="type === 'url'" style="display:none; margin-top:4px;">
                                <input id="image_url" name="image_url" type="url" value="{{ old('image_url') }}" class="form-input" placeholder="https://example.com/image.jpg">
                            </div>

                            @error('image_file')
                                <div class="err">{{ $message }}</div>
                            @enderror
                            @error('image_url')
                                <div class="err">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="attachments" class="form-label">Tệp đính kèm (Excel/CSV/PDF...)</label>
                            <input id="attachments" name="attachments[]" type="file" class="form-input" multiple>
                            <div class="muted" style="margin-top:6px; font-size:12px;">Bạn có thể chọn nhiều tệp. Mỗi tệp tối đa 50MB.</div>
                        </div>
                    </div>
                </div>
                <div style="position: sticky; bottom: 0; background:#fff; padding-top: 12px; margin-top: 24px; display:flex; justify-content: flex-end; align-items:center; gap: 12px; border-top: 1px solid #eef2f7;">
                    <a href="{{ route('enterprise.challenges.index') }}" class="btn btn-ghost">Hủy</a>
                    <button type="submit" class="btn btn-primary" id="btn-submit-challenge" style="min-width: 168px; height: 46px; font-size: 16px;">🚀 Đăng ngay</button>
                </div>
            </form>

            @push('scripts')
                <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
                <script>
                    const createCk = (selector) => {
                        const el = document.querySelector(selector);
                        if (!el) return;
                        ClassicEditor.create(el, {
                            toolbar: ['heading','|','bold','italic','link','bulletedList','numberedList','blockQuote','insertTable','|','undo','redo']
                        }).then(editor => {
                            editor.editing.view.change(writer => {
                                writer.setStyle('min-height', '280px', editor.editing.view.document.getRoot());
                            });
                            const form = el.closest('form');
                            if (form) form.addEventListener('submit', () => editor.updateSourceElement());
                            editor.model.document.on('change:data', () => editor.updateSourceElement());
                        }).catch(console.error);
                    };
                    createCk('#problem_statement');
                    createCk('#requirements');
                </script>
            @endpush
        </div>
    </div>
</section>
@endsection

