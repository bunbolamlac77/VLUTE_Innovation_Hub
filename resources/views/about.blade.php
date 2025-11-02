@extends('layouts.main')

@section('title', 'Giới thiệu - VLUTE Innovation Hub')

@section('content')
    {{-- [BANNER] Hero giới thiệu ngắn gọn --}}
    <section class="hero"
        style="background: linear-gradient(120deg, rgba(7, 26, 82, 0.9), rgba(10, 168, 79, 0.85)), url('{{ asset('images/panel-truong.jpg') }}') center/cover no-repeat;">
        <div class="container" style="padding: 56px 0">
            <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 16px;">
                <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
                    style="height: 80px; width: auto; object-fit: contain; background: rgba(255, 255, 255, 0.95); padding: 8px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);" />
                <div>
                    <h1 style="color: #fff; margin: 0 0 8px">Giới thiệu</h1>
                    <p class="sub" style="max-width: 820px; color: rgba(255, 255, 255, 0.92); margin: 0;">
                        Cổng Đổi mới Sáng tạo VLUTE kết nối ý tưởng – cố vấn – doanh nghiệp – ươm tạo, phục vụ sinh viên,
                        giảng viên
                        và đối tác.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- [SECTION] Tổng quan ngắn về VLUTE --}}
    <section class="container" style="padding-top: 32px">
        <div class="section-header" style="align-items: center">
            <h2 class="section-title">
                Trường Đại học Sư phạm Kỹ thuật Vĩnh Long (VLUTE)
            </h2>
        </div>
        <div class="card" role="region" aria-label="Giới thiệu ngắn">
            <div class="card-body" style="line-height: 1.75">
                <p>
                    <strong>VLUTE</strong> là cơ sở giáo dục đại học công lập, trực thuộc Bộ Giáo dục và Đào tạo. Tên tiếng
                    Anh:
                    <em>Vinh Long University of Technology Education (VLUTE)</em>. Địa chỉ:
                    <strong>Số 73 Nguyễn Huệ, Phường Long Châu, tỉnh Vĩnh Long</strong>.
                </p>
                <p>
                    Nhà trường được biết đến là một trường đại học công lập uy tín tại khu vực Đồng bằng sông Cửu Long, với
                    bề dày thành tích và cơ sở vật chất hiện đại, tiên tiến.
                </p>
                <ul>
                    <li><strong>Email:</strong> spktvl@vlute.edu.vn</li>
                    <li><strong>Website:</strong> vlute.edu.vn</li>
                </ul>
                {{-- Nguồn: trang giới thiệu chính thức --}}
                <p style="font-size: 14px; color: #6b7280">
                    Nguồn: Trang giới thiệu của VLUTE và trang chủ VLUTE (được tóm tắt).
                </p>
            </div>
        </div>
    </section>
    <br />

    {{-- [SECTION] Sứ mạng - Giá trị cốt lõi --}}
    <section class="container">
        <div class="grid-3">
            <div class="card">
                <div class="card-body">
                    <h4>Sứ mạng & Tầm nhìn</h4>
                    <p>
                        Đào tạo nguồn nhân lực kỹ thuật – công nghệ đáp ứng nhu cầu xã hội; thúc đẩy nghiên cứu, chuyển giao
                        và khởi nghiệp đổi mới sáng tạo.
                    </p>
                    <p class="pill" aria-label="Slogan">
                        "Nơi không có ranh giới giữa Nhà trường và Thực tế".
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>Giá trị cốt lõi</h4>
                    <p>Khát vọng – Trí tuệ – Đổi mới – Trách nhiệm – Bền vững.</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>Liên hệ</h4>
                    <p>ĐT: 0270 3822 141 · Email: spktvl@vlute.edu.vn</p>
                    <p>
                        Địa chỉ: Số 73 Nguyễn Huệ, Phường Long Châu, tỉnh Vĩnh Long.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <br />

    {{-- [SECTION] Mục tiêu của Cổng ĐMST (trang đề tài) --}}
    <section class="container">
        <div class="section-header">
            <h2 class="section-title">Mục tiêu của Cổng Đổi mới Sáng tạo</h2>
        </div>
        <div class="grid-3">
            <div class="card">
                <div class="card-body">
                    <h4>Kết nối hệ sinh thái</h4>
                    <p>
                        Kết nối sinh viên – giảng viên – mentor – doanh nghiệp – đối tác để cùng giải quyết các bài toán
                        thực tế.
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>Ươm tạo & đồng hành</h4>
                    <p>
                        Tổ chức đợt gọi ý tưởng, cohort ươm tạo, workshop kỹ năng và cố vấn chuyên sâu theo lịch.
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>Lan toả nghiên cứu</h4>
                    <p>
                        Hỗ trợ truyền thông, công bố "Bản tin Nghiên cứu", giới thiệu giải pháp tiêu biểu và câu chuyện
                        thành công.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <br />
@endsection

@push('scripts')
    <script>
        // [ABOUT/JS] Đánh dấu mục "Giới thiệu" đang hoạt động
        document.addEventListener('DOMContentLoaded', function () {
            const aboutLink = document.querySelector('nav.menu a[data-key="about"]');
            if (aboutLink) {
                aboutLink.classList.add('active');
            }
        });
    </script>
@endpush