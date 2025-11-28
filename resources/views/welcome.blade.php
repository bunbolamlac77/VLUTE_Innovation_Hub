@extends('layouts.main')

@section('title', 'VLUTE Innovation Hub')

@section('content')
    {{-- Announcement --}}
    <div class="announce" role="region" aria-label="Thông báo">
        <div class="container">
            <span class="pill">Mới</span>
            <strong style="margin-left: 8px">Đợt gọi ý tưởng 2025</strong>
            <span style="opacity: 0.9">— Hạn nộp 30/10. Hãy gửi ý tưởng của bạn ngay hôm nay!</span>
            <a class="btn btn-ghost" style="margin-left: 12px" href="#submit">Gửi ý tưởng</a>
            <button class="close" aria-label="Đóng thông báo">×</button>
        </div>
    </div>

    {{-- Hero Section --}}
    <section class="hero">
        <div class="container hero-inner">
            <div>
                <h1>VLUTE Innovation Hub</h1>
                <div class="sub">
                    Kết nối ý tưởng – cố vấn – doanh nghiệp – ươm tạo. Cổng dành cho
                    sinh viên, giảng viên và đối tác.
                </div>
                <div class="cta">
                    <a class="btn btn-ghost" href="#submit">Gửi ý tưởng</a>
                    <a class="btn btn-ghost" href="{{ route('competitions.index') }}">Đăng ký cuộc thi</a>
                    <a class="btn btn-ghost" href="#mentors">Đặt lịch mentor</a>
                </div>
            </div>
            <aside class="stats" aria-label="Số liệu nhanh">
                <h3>Tổng quan</h3>
                <ul>
                    <li>
                        <span>Ý tưởng đã nộp</span>
                        <strong class="n counter" data-target="128">128</strong>
                    </li>
                    <li>
                        <span>Mentor</span>
                        <strong class="n counter" data-target="34">34</strong>
                    </li>
                    <li>
                        <span>Đối tác</span>
                        <strong class="n counter" data-target="22">22</strong>
                    </li>
                    <li>
                        <span>Cuộc thi đang mở</span>
                        <strong class="n counter" data-target="4">4</strong>
                    </li>
                </ul>
            </aside>
        </div>
    </section>

    <br />

    {{-- Roles Section --}}
    <section class="roles container">
        <div class="grid-3">
            <div class="card">
                <div class="card-body">
                    <h4>🎓 Sinh viên</h4>
                    <p>Tạo đội, gửi ý tưởng, theo dõi phản hồi & đăng ký cuộc thi.</p>
                    <div style="margin-top: 12px">
                        <a class="btn btn-primary" href="#submit">Bắt đầu</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>👩‍🏫 Giảng viên / Mentor</h4>
                    <p>
                        Chấm theo rubric, yêu cầu bổ sung, nhận cố vấn & quản lý lịch.
                    </p>
                    <div style="margin-top: 12px">
                        <a class="btn btn-primary" href="#review">Vào hàng chờ</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4>🏢 Doanh nghiệp / Đối tác</h4>
                    <p>Đăng challenge, shortlist giải pháp, tài trợ & kết nối PoC.</p>
                    <div style="margin-top: 12px">
                        <a class="btn btn-primary" href="#partners">Tạo challenge</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <br />

    {{-- Events Section --}}
    <section id="events" class="container">
        <div class="section-header">
            <h2 class="section-title">Cuộc thi & Sự kiện</h2>
            <a class="muted-link" href="{{ route('competitions.index') }}">Xem tất cả →</a>
        </div>
        <div class="tabs" role="tablist">
            <button class="tab active" data-tab="open" role="tab" aria-selected="true">
                Đang mở
            </button>
            <button class="tab" data-tab="upcoming" role="tab">
                Sắp diễn ra
            </button>
            <button class="tab" data-tab="past" role="tab">Đã kết thúc</button>
        </div>
        <div class="grid-4">
            @forelse($openCompetitions as $c)
                <article class="item" style="display:flex; flex-direction:column; height:100%;">
                    <div class="thumb" style="background: linear-gradient(135deg, #c7d2fe, #a7f3d0); height: 180px;"></div>
                    <div class="meta" style="display:flex; flex-direction:column; flex:1;">
                        <div class="row" style="margin-bottom:8px;">
                            <span class="tag">Cuộc thi</span>
                            <span style="font-size:12px;color:#6b7280">{{ optional($c->end_date)->format('d/m/Y H:i') }}</span>
                        </div>
                        <h5 style="margin:0 0 8px; line-height:1.35;">
                            <a href="{{ route('competitions.show', $c->slug) }}" style="text-decoration:none; color:#0f172a;">{{ $c->title }}</a>
                        </h5>
                        <div class="actions" style="margin-top:auto; display:flex; gap:8px; align-items:center; padding-top:12px;">
                            <a class="btn btn-ghost" href="{{ route('competitions.show', $c->slug) }}">Xem chi tiết</a>
                            <a class="btn btn-primary" href="{{ route('competitions.show', $c->slug) }}">Đăng ký</a>
                        </div>
                    </div>
                </article>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 24px; color: #6b7280;">
                    Chưa có cuộc thi nào đang mở.
                </div>
            @endforelse
        </div>
    </section>

    <br />

    {{-- Ideas Section --}}
    <section id="ideas" class="container">
        <div class="section-header">
            <h2 class="section-title">Ý tưởng nổi bật / Portfolio ươm tạo</h2>
            <a class="muted-link" href="{{ route('ideas.index') }}">Khám phá ý tưởng →</a>
        </div>
        <div class="grid-4" id="featuredGrid">
            @forelse($featuredIdeas as $idea)
                <article class="item" onclick="window.location.href='{{ route('ideas.show', $idea->slug) }}'" style="cursor: pointer;">
                    <div class="thumb" style="background: linear-gradient(135deg, #93c5fd, #a7f3d0); height: 180px;"></div>
                    <div class="meta">
                        <div class="row">
                            @if($idea->faculty)
                                <span class="tag">{{ $idea->faculty->name }}</span>
                            @else
                                <span class="tag">Chưa phân loại</span>
                            @endif
                            @if($idea->category)
                                <span class="tag" style="background: rgba(10, 168, 79, 0.1); color: var(--brand-green);">
                                    {{ $idea->category->name }}
                                </span>
                            @endif
                        </div>
                        <h5>{{ $idea->title }}</h5>
                        @if($idea->summary)
                            <p style="color: #6b7280; font-size: 14px; margin: 8px 0 0; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $idea->summary }}
                            </p>
                        @endif
                        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                @php
                                    $isLiked = auth()->check() && $idea->isLikedBy(auth()->user());
                                    $likeCount = $idea->likes_count ?? $idea->like_count ?? 0;
                                @endphp
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $isLiked ? '#ef4444' : 'none' }}" stroke="{{ $isLiked ? '#ef4444' : '#9ca3af' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                                <span style="font-size: 14px; color: #6b7280;">{{ $likeCount }}</span>
                            </div>
                            <span style="font-size: 12px; color: #6b7280;">
                                {{ $idea->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </article>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 48px; color: #6b7280;">
                    <p>Chưa có ý tưởng nổi bật nào.</p>
                </div>
            @endforelse
        </div>
    </section>

    <br />

    {{-- News Section --}}
    <section id="news" class="container" style="background: transparent">
        <div class="section-header">
            <h2 class="section-title">Bản Tin Ngiêm Cứu Khoa Học</h2>
            <a class="muted-link" href="#">Xem tất cả bản tin →</a>
        </div>
        <div class="grid-4" id="newsGrid"></div>
    </section>

    {{-- Core Values Section --}}
    <section class="core-values">
        <div class="container">
            <h2>GIÁ TRỊ CỐT LÕI</h2>
            <div class="sub">
                Khát vọng – Trí tuệ – Đổi mới – Trách nhiệm – Bền vững
            </div>
            <div class="scroller">
                <ul class="scroller__inner logo-list">
                    <li>
                        <img src="{{ asset('images/05.png') }}" alt="Logo nhà tài trợ 05" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 01.png') }}"
                            alt="Logo nhà tài trợ 01" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 02.png') }}"
                            alt="Logo nhà tài trợ 02" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 03.png') }}"
                            alt="Logo nhà tài trợ 03" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 04.png') }}"
                            alt="Logo nhà tài trợ 04" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 06.png') }}"
                            alt="Logo nhà tài trợ 06" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 07.png') }}"
                            alt="Logo nhà tài trợ 07" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 08.png') }}"
                            alt="Logo nhà tài trợ 08" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 09.png') }}"
                            alt="Logo nhà tài trợ 09" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 10.png') }}"
                            alt="Logo nhà tài trợ 10" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 11.png') }}"
                            alt="Logo nhà tài trợ 11" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 12.png') }}"
                            alt="Logo nhà tài trợ 12" />
                    </li>
                    <li>
                        <img src="{{ asset('images/Trường Đại học Sư phạm Kỹ thuật Vĩnh Long 13.png') }}"
                            alt="Logo nhà tài trợ 13" />
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <br />
    <br />

    {{-- Success Stories Section --}}
    <section class="container">
        <div class="section-header">
            <h2 class="section-title">Câu chuyện thành công</h2>
            <a class="muted-link" href="#">Xem thêm →</a>
        </div>
        <div class="grid-4" id="successGrid"></div>
    </section>

    <br />

    {{-- Counters Section --}}
    <section class="container">
        <div class="counters">
            <div class="counter">
                <div class="n counter" data-target="128">0</div>
                <br />
                <div class="l">Ý tưởng đã nộp</div>
            </div>
            <div class="counter">
                <div class="n counter" data-target="34">0</div>
                <br />
                <div class="l">Mentor</div>
            </div>
            <div class="counter">
                <div class="n counter" data-target="22">0</div>
                <br />
                <div class="l">Đối tác</div>
            </div>
            <div class="counter">
                <div class="n counter" data-target="17">0</div>
                <br />
                <div class="l">Giải thưởng</div>
            </div>
        </div>
    </section>

    <br />

    {{-- Newsletter Section --}}
    <section class="newsletter">
        <div class="container box">
            <strong>Nhận bản tin Đổi mới Sáng tạo</strong>
            <input type="email" placeholder="Email @vlute.edu.vn" aria-label="Email nhận bản tin" />
            <button class="btn btn-primary">Đăng ký</button>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Announcement close
        document
            .querySelector('.announce .close')
            ?.addEventListener('click', () => {
                document.querySelector('.announce').style.display = 'none';
            });

        // Counters animation
        const numberCounters = document.querySelectorAll('.counter[data-target]');
        function animateCounters(list) {
            list.forEach((c) => {
                const target = Number(c.dataset.target);
                if (!Number.isFinite(target)) return;
                let current = 0;
                const step = Math.max(1, Math.floor(target / 40));
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    c.textContent = current;
                }, 30);
            });
        }

        const counterObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const countersToAnimate = entry.target.querySelectorAll(
                            '.counter[data-target]'
                        );
                        animateCounters(countersToAnimate);
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.3 }
        );

        document.querySelectorAll('.stats, .counters').forEach((section) => {
            counterObserver.observe(section);
        });

        // Mock data for cards (kept only if compGrid exists on some pages)
        const compGridEl = document.getElementById('compGrid');
        if (compGridEl) {
        const comps = {
            open: [
                {
                    title: 'VLUTE Innovation Challenge 2025',
                    deadline: '30/10/2025',
                    track: 'IoT · AI',
                    btn: 'Đăng ký',
                },
                {
                    title: 'Hack4Mekong – Nông nghiệp số',
                    deadline: '12/11/2025',
                    track: 'AgriTech',
                    btn: 'Đăng ký',
                },
                {
                    title: 'SV Startup – Cấp trường',
                    deadline: '05/12/2025',
                    track: 'Open',
                    btn: 'Đăng ký',
                },
                {
                    title: 'Design Thinking Bootcamp',
                    deadline: '28/09/2025',
                    track: 'Workshop',
                    btn: 'Đăng ký',
                },
            ],
            upcoming: [
                {
                    title: 'Maker Space Day',
                    deadline: 'T11/2025',
                    track: 'Prototype',
                    btn: 'Nhắc tôi',
                },
                {
                    title: 'Demo Day – Cohort 01',
                    deadline: 'T12/2025',
                    track: 'Showcase',
                    btn: 'Nhắc tôi',
                },
            ],
            past: [
                {
                    title: 'CiC VNU-HCM – Bán kết',
                    deadline: '06/2025',
                    track: 'Open',
                    btn: 'Kết quả',
                },
                {
                    title: 'BK Innovation Pitch',
                    deadline: '04/2025',
                    track: 'Tech',
                    btn: 'Kết quả',
                },
            ],
        };

        // Ideas data is now loaded from database via $featuredIdeas variable in Blade template

        var news = [
            {
                title: 'Hội thảo "Ứng dụng trí tuệ nhân tạo trong giáo dục kỹ thuật"',
                date: '06/02/2025',
            },
            { title: 'VLUTE làm việc với Công ty PCB G…', date: '29/03/2024' },
            { title: 'Kết nối việc làm tại thị trường Đức…', date: '18/03/2024' },
            { title: 'Ký kết hợp tác đào tạo nguồn nhân lực…', date: '16/03/2024' },
        ];

        const success = [
            {
                title: 'AgriSense – quán quân cấp trường 2024',
                brief: 'Đã ký thoả thuận thử nghiệm với nông trại đối tác.',
            },
            {
                title: 'AR-Factory – giải Nhì SV Startup',
                brief: 'Triển khai pilot tại xưởng thực hành cơ khí.',
            },
        ];

        function cardHTML(data) {
            return `
                                  <article class="item" tabindex="0">
                                    <div class="thumb" aria-hidden="true"></div>
                                    <div class="meta">
                                      <div class="row"><span class="tag">${data.track || data.dept || 'General'
                }</span><span style="font-size:12px;color:#6b7280">${data.deadline || ''
                }</span></div>
                                      <h5>${data.title}</h5>
                                      <div class="actions">${data.btn
                    ? `<a class="btn btn-ghost" style="border-color:var(--brand-navy);color:var(--brand-navy)" href="#">${data.btn}</a>`
                    : ''
                }</div>
                                    </div>
                                  </article>`;
        }

        function renderComps(type = 'open') {
            const grid = document.getElementById('compGrid');
            grid.innerHTML = comps[type].map(cardHTML).join('');
        }
        renderComps('open');

        document.querySelectorAll('.tab').forEach((t) => {
            t.addEventListener('click', () => {
                document
                    .querySelectorAll('.tab')
                    .forEach((x) => x.classList.remove('active'));
                t.classList.add('active');
                renderComps(t.dataset.tab);
            });
        });
        }

        // featured ideas rendered by Blade
        document.getElementById('newsGrid').innerHTML = news
            .map(
                (n) =>
                    `
                                <article class="item">
                                  <div class="thumb"></div>
                                  <div class="meta">
                                    <div class="row"><span class="tag">Bản tin</span><span style="font-size:12px;color:#6b7280">${n.date}</span></div>
                                    <h5>${n.title}</h5>
                                  </div>
                                </article>`
            )
            .join('');
        document.getElementById('successGrid').innerHTML = success
            .map(
                (s) =>
                    `
                                <article class="item">
                                  <div class="thumb" style="background:linear-gradient(135deg,#fde68a,#86efac)"></div>
                                  <div class="meta">
                                    <h5>${s.title}</h5>
                                    <p style="color:#6b7280;margin:0">${s.brief}</p>
                                  </div>
                                </article>`
            )
            .join('');

        // Scroller/Marquee Logic
        const scrollers = document.querySelectorAll('.scroller');

        // Luôn kích hoạt marquee logo (bỏ điều kiện giảm chuyển động để đảm bảo chạy)
        addAnimation();

        function addAnimation() {
            scrollers.forEach((scroller) => {
                scroller.setAttribute('data-animated', true);
                const scrollerInner = scroller.querySelector('.scroller__inner');
                const scrollerContent = Array.from(scrollerInner.children);
                scrollerContent.forEach((item) => {
                    const duplicatedItem = item.cloneNode(true);
                    duplicatedItem.setAttribute('aria-hidden', true);
                    scrollerInner.appendChild(duplicatedItem);
                });
            });
        }
    </script>
@endpush