@extends('layouts.main')

@section('title', $competition->title)

@section('content')
    <section class="container" style="padding: 32px 0 64px;">
        <img src="{{ $competition->banner_url ?? '/images/panel-truong.jpg' }}" alt="{{ $competition->title }}"
            style="width: 100%; height: 350px; object-fit: cover; border-radius: 12px; margin-bottom: 24px;">

        <div style="display: grid; grid-template-columns: 2.5fr 1fr; gap: 32px;">
            <div>
                <h1 style="font-size: 36px; font-weight: 700; margin: 0 0 16px; line-height: 1.3;">
                    {{ $competition->title }}
                </h1>

                <div class="prose" style="line-height: 1.8; color: #374151;">
                    {!! $competition->description !!}
                </div>
            </div>

            <aside>
                <div class="card"
                    style="border: 1px solid var(--border); border-radius: 12px; padding: 24px; position: sticky; top: 100px;">
                    <h3 style="font-size: 18px; font-weight: 600; margin: 0 0 16px;">
                        Thông tin cuộc thi
                    </h3>

                    <div style="font-size: 15px; margin-bottom: 12px;">
                        <strong>Bắt đầu:</strong>
                        <span>{{ $competition->start_date->format('d/m/Y H:i') }}</span>
                    </div>
                    <div style="font-size: 15px; margin-bottom: 20px;">
                        <strong>Kết thúc:</strong>
                        <span>{{ $competition->end_date->format('d/m/Y H:i') }}</span>
                    </div>

                    @auth
                        @php
                            // Kiểm tra xem user đã đăng ký cuộc thi này chưa
                            $isRegistered = Auth::user()->competitionRegistrations()
                                                ->where('competition_id', $competition->id)
                                                ->exists();
                        @endphp

                        @if ($isRegistered)
                            <div style="padding: 12px; background-color: #e0fdf4; border: 1px solid #34d399; border-radius: 8px; text-align: center; font-weight: 600; color: #06764e;">
                                ✅ Bạn đã đăng ký
                            </div>
                        @elseif ($competition->status === 'open' && $competition->end_date > now())
                            <form method="POST" action="{{ route('competitions.register', $competition) }}">
                                @csrf
                                
                                <button type="submit" class="btn btn-primary"
                                        style="display: block; width: 100%; text-align: center; padding: 12px; font-size: 16px; font-weight: 600; text-decoration: none; border: none; cursor: pointer;">
                                    Đăng ký tham gia
                                </button>
                            </form>
                        @else
                            <div style="padding: 12px; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 8px; text-align: center; font-weight: 600; color: #4b5563;">
                                Cuộc thi đã đóng
                            </div>
                        @endif

                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary"
                           style="display: block; width: 100%; text-align: center; padding: 12px; font-size: 16px; font-weight: 600; text-decoration: none;">
                            Đăng nhập để đăng ký
                        </a>
                    @endauth

                    @if (session('success'))
                        <div style="color: green; margin-top: 10px; font-weight: 600;">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div style="color: red; margin-top: 10px; font-weight: 600;">{{ session('error') }}</div>
                    @endif
                    @if (session('info'))
                        <div style="color: blue; margin-top: 10px; font-weight: 600;">{{ session('info') }}</div>
                    @endif
                </div>
            </aside>
        </div>
    </section>
@endsection