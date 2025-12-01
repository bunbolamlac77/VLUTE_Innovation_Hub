@extends('layouts.main')

@section('title', 'Giới thiệu - VLUTE Innovation Hub')

@section('content')
  {{-- Hero --}}
  <section class="relative text-white">
    <div class="absolute inset-0 bg-cover bg-center"
      style="background-image: url('{{ asset('images/panel-truong.jpg') }}')"></div>
    <div class="absolute inset-0 bg-gradient-to-tr from-brand-navy/90 to-brand-green/80"></div>
    <div class="relative">
      <div class="container py-14">
        <div class="flex items-center gap-6 mb-2">
          <img src="{{ asset('images/logotruong.jpg') }}" alt="Logo Trường ĐHSPKT Vĩnh Long"
            class="h-20 w-auto object-contain bg-white/95 p-2 rounded-lg shadow" />
          <div>
            <h1 class="m-0 text-3xl lg:text-4xl font-extrabold">Giới thiệu</h1>
            <p class="max-w-3xl text-white/90 text-lg m-0">Cổng Đổi mới Sáng tạo VLUTE kết nối ý tưởng – cố vấn – doanh
              nghiệp – ươm tạo, phục vụ sinh viên, giảng viên và đối tác.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Tổng quan VLUTE --}}
  <section class="container pt-8">
    <div class="flex items-center justify-between gap-4 mb-4">
      <h2 class="text-2xl font-extrabold text-slate-900">Trường Đại học Sư phạm Kỹ thuật Vĩnh Long (VLUTE)</h2>
    </div>
    <div role="region" aria-label="Giới thiệu ngắn" class="bg-white border border-slate-200 rounded-2xl shadow-card">
      <div class="p-6 leading-7 text-slate-800">
        <p><strong>VLUTE</strong> là cơ sở giáo dục đại học công lập, trực thuộc Bộ Giáo dục và Đào tạo. Tên tiếng Anh:
          <em>Vinh Long University of Technology Education (VLUTE)</em>. Địa chỉ: <strong>Số 73 Nguyễn Huệ, Phường Long
            Châu, tỉnh Vĩnh Long</strong>.</p>
        <p>Nhà trường được biết đến là một trường đại học công lập uy tín tại khu vực Đồng bằng sông Cửu Long, với bề dày
          thành tích và cơ sở vật chất hiện đại, tiên tiến.</p>
        <ul class="list-disc pl-6">
          <li><strong>Email:</strong> spktvl@vlute.edu.vn</li>
          <li><strong>Website:</strong> vlute.edu.vn</li>
        </ul>
        <p class="text-sm text-slate-500">Nguồn: Trang giới thiệu của VLUTE và trang chủ VLUTE (được tóm tắt).</p>
      </div>
    </div>
  </section>

  {{-- Sứ mạng - Giá trị cốt lõi --}}
  <section class="container py-10">
    <div class="grid md:grid-cols-3 gap-4">
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
        <h4 class="font-bold text-lg mb-2">Sứ mạng & Tầm nhìn</h4>
        <p>Đào tạo nguồn nhân lực kỹ thuật – công nghệ đáp ứng nhu cầu xã hội; thúc đẩy nghiên cứu, chuyển giao và khởi
          nghiệp đổi mới sáng tạo.</p>
        <p class="inline-block bg-emerald-50 text-brand-green px-3 py-1 rounded-full text-sm">"Nơi không có ranh giới giữa
          Nhà trường và Thực tế".</p>
      </div>
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
        <h4 class="font-bold text-lg mb-2">Giá trị cốt lõi</h4>
        <p>Khát vọng – Trí tuệ – Đổi mới – Trách nhiệm – Bền vững.</p>
      </div>
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
        <h4 class="font-bold text-lg mb-2">Liên hệ</h4>
        <p>ĐT: 0270 3822 141 · Email: spktvl@vlute.edu.vn</p>
        <p>Địa chỉ: Số 73 Nguyễn Huệ, Phường Long Châu, tỉnh Vĩnh Long.</p>
      </div>
    </div>
  </section>

  {{-- Mục tiêu của Cổng ĐMST --}}
  <section class="container pb-10">
    <div class="flex items-center justify-between gap-4 mb-4">
      <h2 class="text-2xl font-extrabold text-slate-900">Mục tiêu của Cổng Đổi mới Sáng tạo</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-4">
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
        <h4 class="font-bold text-lg mb-2">Kết nối hệ sinh thái</h4>
        <p>Kết nối sinh viên – giảng viên – mentor – doanh nghiệp – đối tác để cùng giải quyết các bài toán thực tế.</p>
      </div>
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
        <h4 class="font-bold text-lg mb-2">Ươm tạo & đồng hành</h4>
        <p>Tổ chức đợt gọi ý tưởng, cohort ươm tạo, workshop kỹ năng và cố vấn chuyên sâu theo lịch.</p>
      </div>
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
        <h4 class="font-bold text-lg mb-2">Lan toả nghiên cứu</h4>
        <p>Hỗ trợ truyền thông, công bố "Bản tin Nghiên cứu", giới thiệu giải pháp tiêu biểu và câu chuyện thành công.</p>
      </div>
    </div>
  </section>
@endsection