@extends('layouts.main')

@section('title', 'Đăng Thách thức mới - Doanh nghiệp')

@section('content')
<section class="container" style="padding: 32px 0; max-width: 880px;">
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
            <form method="POST" action="{{ route('enterprise.challenges.store') }}">
                @csrf
                <div class="grid" style="display:grid; gap:16px;">
                    <div>
                        <label for="title" class="form-label">Tên Thách thức / Vấn đề</label>
                        <input id="title" name="title" type="text" class="form-input" required autofocus placeholder="Ví dụ: Tối ưu quy trình đóng gói sản phẩm" value="{{ old('title') }}">
                    </div>
                    <div>
                        <label for="description" class="form-label">Mô tả chi tiết vấn đề</label>
                        <textarea id="description" name="description" rows="6" class="form-textarea" required placeholder="Bối cảnh, mục tiêu mong muốn, phạm vi...">{{ old('description') }}</textarea>
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
                </div>
                <div style="display:flex; justify-content: flex-end; gap: 12px; margin-top: 20px;">
                    <a href="{{ route('enterprise.challenges.index') }}" class="btn btn-ghost">Hủy</a>
                    <button type="submit" class="btn btn-primary">Đăng ngay</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

