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

                    <a href="#" class="btn btn-primary"
                        style="display: block; width: 100%; text-align: center; padding: 12px; font-size: 16px; font-weight: 600; text-decoration: none;">
                        Đăng ký tham gia
                    </a>
                </div>
            </aside>
        </div>
    </section>
@endsection