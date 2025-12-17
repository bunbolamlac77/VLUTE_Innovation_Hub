@extends('layouts.main')

@section('title', 'VLUTE Innovation Hub')

@section('content')
  {{-- Announcement --}}
  <div class="bg-brand-green text-white" role="region" aria-label="Thông báo">
    <div class="container flex items-center gap-3 py-2.5">
      <span class="inline-block bg-white/15 text-white px-3 py-1 rounded-full text-xs font-semibold">Mới</span>
      <strong class="ml-1">Đợt gọi ý tưởng 2025</strong>
      <span class="opacity-90">— Hạn nộp 30/10. Hãy gửi ý tưởng của bạn ngay hôm nay!</span>
      <a class="ml-3 inline-flex items-center rounded-full border border-white/30 bg-white/0 hover:bg-white/15 transition px-3 py-1.5 text-sm font-semibold"
        href="#submit">Gửi ý tưởng</a>
      <button class="ml-auto font-bold" aria-label="Đóng thông báo"
        onclick="this.closest('[role=region]').remove()">×</button>
    </div>
  </div>

  {{-- Banner Slider Section --}}
  <div class="relative w-full overflow-hidden bg-gray-100" style="height: 500px;">
    @if(isset($banners) && count($banners) > 0)
      <div class="absolute inset-0 flex transition-transform duration-500 ease-in-out" id="homeSlider">
        @foreach($banners as $banner)
          <div class="w-full flex-shrink-0 h-full relative">
            <img src="{{ Storage::url($banner->image_path) }}" class="w-full h-full object-cover" alt="{{ $banner->title }}">
            @if($banner->title)
              <div class="absolute bottom-10 left-10 bg-black/50 p-4 text-white rounded max-w-xl">
                <h2 class="text-3xl font-bold">{{ $banner->title }}</h2>
                @if($banner->link_url)
                  <a href="{{ $banner->link_url }}" class="inline-block mt-2 text-yellow-400 hover:text-yellow-300 font-bold">Xem chi tiết &rarr;</a>
                @endif
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @else
      {{-- Fallback nếu chưa có banner nào trong database --}}
      <section class="relative text-white h-full">
        <div class="absolute inset-0 bg-cover bg-center"
          style="background-image: url('{{ asset('images/panel-truong.jpg') }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-tr from-brand-navy/90 to-brand-green/80"></div>
        <div class="relative h-full">
          <div class="container grid lg:grid-cols-[1.4fr,0.9fr] gap-8 py-20 min-h-[500px] items-center">
            <div>
              <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight mb-4">VLUTE Innovation Hub</h1>
              <p class="text-white/95 text-lg leading-relaxed font-medium mb-7 max-w-2xl">
                Kết nối ý tưởng – cố vấn – doanh nghiệp – ươm tạo. Cổng dành cho sinh viên, giảng viên và đối tác.
              </p>
              <div class="flex gap-3 flex-wrap">
                @auth
                  <a class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-transparent hover:bg-white/15 px-4 py-2 font-semibold"
                    href="{{ route('my-ideas.create') }}">Gửi ý tưởng</a>
                @else
                  <a class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-transparent hover:bg-white/15 px-4 py-2 font-semibold"
                    href="{{ route('login') }}">Gửi ý tưởng</a>
                @endauth
                <a class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-transparent hover:bg-white/15 px-4 py-2 font-semibold"
                  href="{{ route('competitions.index') }}">Đăng ký cuộc thi</a>
                @auth
                  @if(auth()->user()->hasRole('staff'))
                    <a class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-transparent hover:bg-white/15 px-4 py-2 font-semibold"
                      href="{{ route('mentor.ideas') }}">Đặt lịch mentor</a>
                  @else
                    <a class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-transparent hover:bg-white/15 px-4 py-2 font-semibold"
                      href="{{ route('dashboard') }}">Đặt lịch mentor</a>
                  @endif
                @else
                  <a class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-transparent hover:bg-white/15 px-4 py-2 font-semibold"
                    href="{{ route('login') }}">Đặt lịch mentor</a>
                @endauth
              </div>
            </div>

            <aside class="bg-white/60 backdrop-blur-md border border-white/30 rounded-2xl p-6 shadow-xl self-start">
              <h3 class="m-0 mb-5 text-lg font-extrabold text-slate-800">Tổng quan</h3>
              <ul class="m-0 p-0 grid gap-3">
                <li
                  class="flex justify-between items-center text-slate-900 pb-3 border-b border-white/40 font-semibold text-sm">
                  <span>Ý tưởng đã nộp</span><strong
                    class="bg-white/80 text-brand-navy px-3 py-1 rounded-full font-bold">{{ $ideaCount }}</strong>
                </li>
                <li
                  class="flex justify-between items-center text-slate-900 pb-3 border-b border-white/40 font-semibold text-sm">
                  <span>Mentor</span><strong
                    class="bg-white/80 text-brand-navy px-3 py-1 rounded-full font-bold">{{ $mentorCount }}</strong>
                </li>
                <li
                  class="flex justify-between items-center text-slate-900 pb-3 border-b border-white/40 font-semibold text-sm">
                  <span>Đối tác</span><strong
                    class="bg-white/80 text-brand-navy px-3 py-1 rounded-full font-bold">{{ $partnerCount }}</strong>
                </li>
                <li class="flex justify-between items-center text-slate-900 font-semibold text-sm">
                  <span>Cuộc thi đang mở</span><strong
                    class="bg-white/80 text-brand-navy px-3 py-1 rounded-full font-bold">{{ $openCompetitionsCount }}</strong>
                </li>
              </ul>
            </aside>
          </div>
        </div>
      </section>
    @endif
  </div>

  {{-- Script JS để chạy slider tự động --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const slider = document.getElementById('homeSlider');
      if(!slider) return;
      
      const slides = slider.children;
      const totalSlides = slides.length;
      if(totalSlides < 2) return;

      let index = 0;
      
      setInterval(() => {
        index = (index + 1) % totalSlides;
        slider.style.transform = `translateX(-${index * 100}%)`;
      }, 5000); // Chuyển slide mỗi 5 giây
    });
  </script>

  {{-- Roles Section --}}
  <section class="container py-14">
    <div class="grid md:grid-cols-3 gap-4">
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
        <h4 class="text-lg font-bold mb-2">🎓 Sinh viên</h4>
        <p class="text-slate-600">Tạo đội, gửi ý tưởng, theo dõi phản hồi & đăng ký cuộc thi.</p>
        <div class="mt-3">
          @auth
            <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
              href="{{ route('my-ideas.create') }}">Bắt đầu</a>
          @else
            <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
              href="{{ route('login') }}">Bắt đầu</a>
          @endauth
        </div>
      </div>
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
        <h4 class="text-lg font-bold mb-2">👩‍🏫 Giảng viên / Mentor</h4>
        <p class="text-slate-600">Chấm theo rubric, yêu cầu bổ sung, nhận cố vấn & quản lý lịch.</p>
        <div class="mt-3">
          @auth
            @if(auth()->user()->hasRole('staff') || auth()->user()->hasRole('center') || auth()->user()->hasRole('board') || auth()->user()->hasRole('reviewer'))
              <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
                href="{{ route('manage.review-queue.index') }}">Vào hàng chờ</a>
            @else
              <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
                href="{{ route('dashboard') }}">Vào hàng chờ</a>
            @endif
          @else
            <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
              href="{{ route('login') }}">Vào hàng chờ</a>
          @endauth
        </div>
      </div>
      <div class="bg-white border border-slate-200 rounded-2xl shadow-card p-5">
        <h4 class="text-lg font-bold mb-2">🏢 Doanh nghiệp / Đối tác</h4>
        <p class="text-slate-600">Đăng challenge, shortlist giải pháp, tài trợ & kết nối PoC.</p>
        <div class="mt-3 flex gap-2 flex-wrap">
          @auth
            @if(auth()->user()->hasRole('enterprise'))
              <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
                href="{{ route('enterprise.challenges.create') }}">Tạo challenge</a>
              <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
                href="{{ route('enterprise.scout') }}">🎯 Thợ săn Giải pháp</a>
            @else
              <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
                href="{{ route('dashboard') }}">Tạo challenge</a>
            @endif
          @else
            <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-4 py-2 font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
              href="{{ route('login') }}">Tạo challenge</a>
          @endauth
        </div>
      </div>
    </div>
  </section>

  {{-- Events Section --}}
  <section class="container py-10">
    <div class="flex items-end justify-between gap-4 mb-6">
      <h2 class="text-2xl font-extrabold text-slate-900">Cuộc thi & Sự kiện</h2>
      <a class="text-brand-navy font-semibold" href="{{ route('competitions.index') }}">Xem tất cả →</a>
    </div>
    <div class="grid md:grid-cols-4 gap-4">
      @forelse($openCompetitions as $c)
        <article class="flex flex-col border border-slate-200 bg-white rounded-2xl shadow-card overflow-hidden">
          <div class="h-[180px] bg-slate-100 relative group">
            <img src="{{ $c->thumbnail_url }}"
                 alt="{{ $c->title }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                 onerror="this.src='{{ asset('images/default-competition.png') }}'">
          </div>
          <div class="p-4 flex-1 flex flex-col">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-2">
              <span class="inline-block bg-brand-gray-100 text-slate-700 px-2.5 py-1 rounded-full">Cuộc thi</span>
              <span>{{ optional($c->end_date)->format('d/m/Y H:i') }}</span>
            </div>
            <h5 class="font-bold text-slate-900 leading-snug mb-2">
              <a class="no-underline text-slate-900" href="{{ route('competitions.show', $c->slug) }}">{{ $c->title }}</a>
            </h5>
            <div class="mt-auto pt-3 flex gap-2">
              <a class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50"
                href="{{ route('competitions.show', $c->slug) }}">Xem chi tiết</a>
              <a class="inline-flex items-center gap-2 rounded-full bg-brand-navy text-white px-3 py-1.5 text-sm font-bold shadow hover:shadow-lg hover:-translate-y-px transition focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2"
                href="{{ route('competitions.show', $c->slug) }}">Đăng ký</a>
            </div>
          </div>
        </article>
      @empty
        <div class="col-span-full text-center text-slate-500 py-6">Chưa có cuộc thi nào đang mở.</div>
      @endforelse
    </div>
  </section>

  {{-- Featured Ideas Section --}}
  <section class="container py-10" id="ideas">
    <div class="flex items-end justify-between gap-4 mb-6">
      <h2 class="text-2xl font-extrabold text-slate-900">Ý tưởng nổi bật / Portfolio ươm tạo</h2>
      <a class="text-brand-navy font-semibold" href="{{ route('ideas.index') }}">Khám phá ý tưởng →</a>
    </div>
    <div class="grid md:grid-cols-4 gap-4">
      @forelse($featuredIdeas as $idea)
        <article onclick="window.location.href='{{ route('ideas.show', $idea->slug) }}'"
          class="cursor-pointer border border-slate-200 rounded-2xl shadow-card overflow-hidden">
          <div class="h-[180px] bg-slate-100 relative group">
            <img src="{{ $idea->thumbnail_url }}"
                 alt="{{ $idea->title }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                 onerror="this.src='{{ asset('images/default-idea.png') }}'">
          </div>
          <div class="p-4">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1.5">
              <span
                class="inline-block bg-brand-gray-100 text-slate-700 px-2.5 py-1 rounded-full">{{ $idea->faculty->name ?? 'Chưa phân loại' }}</span>
              @if($idea->category)
                <span
                  class="inline-block bg-emerald-50 text-brand-green px-2.5 py-1 rounded-full">{{ $idea->category->name }}</span>
              @endif
            </div>
            <h5 class="font-bold text-slate-900 leading-snug">{{ $idea->title }}</h5>
            @if($idea->summary)
              <p class="text-sm text-slate-600 mt-2 leading-snug max-h-[3.2em] overflow-hidden">{{ $idea->summary }}</p>
            @endif
            <div class="mt-3 pt-3 border-t border-slate-200 flex items-center justify-between">
              @php
                $isLiked = auth()->check() && $idea->isLikedBy(auth()->user());
                $likeCount = $idea->likes_count ?? $idea->like_count ?? 0;
              @endphp
              <div class="flex items-center gap-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $isLiked ? '#ef4444' : 'none' }}"
                  stroke="{{ $isLiked ? '#ef4444' : '#9ca3af' }}" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round">
                  <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                  </path>
                </svg>
                <span class="text-sm text-slate-500">{{ $likeCount }}</span>
              </div>
              <span class="text-xs text-slate-500">{{ $idea->created_at->format('d/m/Y') }}</span>
            </div>
          </div>
        </article>
      @empty
        <div class="col-span-full text-center text-slate-500 py-12">Chưa có ý tưởng nổi bật nào.</div>
      @endforelse
    </div>
  </section>

  {{-- Scientific Research News Section --}}
  <section class="container py-10">
    <div class="flex items-end justify-between gap-4 mb-4">
      <h2 class="text-2xl font-extrabold text-slate-900">Bản tin Nghiên cứu Khoa học</h2>
      <a class="text-brand-navy font-semibold" href="{{ route('scientific-news.index') }}">Xem tất cả bản tin →</a>
    </div>
    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
      @forelse($latestScientificNews as $item)
        <article class="flex flex-col border border-slate-200 bg-white rounded-2xl shadow-card overflow-hidden">
          <div class="h-[180px] bg-slate-100">
            @if ($item->image_url)
              <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover" />
            @endif
          </div>
          <div class="p-4 flex-1 flex flex-col">
            <div class="flex items-center justify-between text-slate-500 text-xs mb-1.5">
              <span class="inline-block bg-brand-gray-100 text-slate-700 px-2.5 py-1 rounded-full">{{ $item->category ?? 'Chưa phân loại' }}</span>
              <span>{{ optional($item->published_date)->format('d/m/Y') }}</span>
            </div>
            <h5 class="font-bold text-slate-900 leading-snug mb-1 line-clamp-2">
              <a class="no-underline text-slate-900" href="{{ route('scientific-news.show', $item) }}">{{ $item->title }}</a>
            </h5>
            @if ($item->description)
              <p class="text-sm text-slate-600 mt-1 line-clamp-3">{{ $item->description }}</p>
            @endif
            <div class="mt-auto pt-3 flex gap-2">
              <a class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold hover:bg-slate-50"
                 href="{{ route('scientific-news.show', $item) }}">Đọc thêm</a>
              @if ($item->source)
                <a class="inline-flex items-center gap-2 rounded-full text-indigo-600 px-3 py-1.5 text-sm font-semibold hover:bg-indigo-50"
                   href="{{ $item->source }}" target="_blank" rel="noopener">Nguồn</a>
              @endif
            </div>
          </div>
        </article>
      @empty
        <div class="col-span-full text-center text-slate-500 py-6">Chưa có bản tin nào.</div>
      @endforelse
    </div>
  </section>

  {{-- Core Values / Logos --}}
  <section class="container py-6">
    <h2 class="text-3xl font-extrabold text-center text-slate-900 mb-1">GIÁ TRỊ CỐT LÕI</h2>
    <p class="text-center text-slate-600 mb-6">Khát vọng – Trí tuệ – Đổi mới – Trách nhiệm – Bền vững</p>
    <div class="overflow-hidden">
      <div class="flex items-center justify-center gap-10 flex-wrap opacity-90">
        <img src="{{ asset('images/05.png') }}" alt="Logo 05" class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 01.png') }}" alt="Logo 01"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 02.png') }}" alt="Logo 02"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 03.png') }}" alt="Logo 03"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 04.png') }}" alt="Logo 04"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 06.png') }}" alt="Logo 06"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 07.png') }}" alt="Logo 07"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 08.png') }}" alt="Logo 08"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 09.png') }}" alt="Logo 09"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 10.png') }}" alt="Logo 10"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 11.png') }}" alt="Logo 11"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 12.png') }}" alt="Logo 12"
          class="h-12 object-contain" />
        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 13.png') }}" alt="Logo 13"
          class="h-12 object-contain" />
      </div>
    </div>
  </section>

  {{-- Counters Section --}}
  <section class="container py-10">
    <div class="grid md:grid-cols-4 gap-4">
      <div
        class="text-center bg-white border border-slate-200 rounded-2xl p-6 shadow-card hover:-translate-y-0.5 hover:shadow-xl transition">
        <div class="text-3xl font-extrabold text-brand-navy tracking-tight mb-2">{{ $ideaCount }}</div>
        <div class="text-slate-600 font-semibold text-sm">Ý tưởng đã nộp</div>
      </div>
      <div
        class="text-center bg-white border border-slate-200 rounded-2xl p-6 shadow-card hover:-translate-y-0.5 hover:shadow-xl transition">
        <div class="text-3xl font-extrabold text-brand-navy tracking-tight mb-2">{{ $mentorCount }}</div>
        <div class="text-slate-600 font-semibold text-sm">Mentor</div>
      </div>
      <div
        class="text-center bg-white border border-slate-200 rounded-2xl p-6 shadow-card hover:-translate-y-0.5 hover:shadow-xl transition">
        <div class="text-3xl font-extrabold text-brand-navy tracking-tight mb-2">{{ $partnerCount }}</div>
        <div class="text-slate-600 font-semibold text-sm">Đối tác</div>
      </div>
      <div
        class="text-center bg-white border border-slate-200 rounded-2xl p-6 shadow-card hover:-translate-y-0.5 hover:shadow-xl transition">
        <div class="text-3xl font-extrabold text-brand-navy tracking-tight mb-2">{{ $openCompetitionsCount }}</div>
        <div class="text-slate-600 font-semibold text-sm">Cuộc thi đang mở</div>
      </div>
    </div>
  </section>

  {{-- Newsletter Section --}}
  <section class="bg-brand-gray-50 border-y border-slate-200 py-10">
    <div class="container">
      <form method="POST" action="{{ route('newsletter.subscribe') }}" class="bg-white border border-slate-200 rounded-2xl shadow-card p-4 flex items-center gap-3">
        @csrf
        <strong class="text-slate-900">Nhận bản tin Đổi mới Sáng tạo</strong>
        <input type="text" name="name" placeholder="Họ tên (tuỳ chọn)" aria-label="Họ tên"
          class="rounded-full border border-slate-300 px-4 py-2 hidden md:block" />
        <input type="email" name="email" placeholder="Email @vlute.edu.vn" aria-label="Email nhận bản tin" required
          class="flex-1 rounded-full border border-slate-300 px-4 py-2" />
        <input type="hidden" name="source" value="homepage" />
        <button type="submit"
          class="rounded-full bg-white text-brand-navy px-4 py-2 font-bold border border-slate-200 hover:bg-slate-50">Đăng ký</button>
      </form>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    // Rê chuột: coun ters animation có thể bổ sung sau nếu cần.
  </script>
@endpush