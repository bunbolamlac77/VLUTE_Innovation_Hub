@extends('layouts.main')

@section('title', 'Cuộc thi & Sự kiện')

@section('content')
    <div class="container" style="padding: 32px 0;">
        <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 24px;">
            Các Cuộc thi & Sự kiện
        </h1>

        @if ($competitions->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px;">
                @foreach ($competitions as $competition)
                    <div class="card"
                        style="border: 1px solid var(--border); border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px #0000000d;">
                        <a href="{{ route('competitions.show', $competition) }}">
                            <img src="{{ $competition->banner_url ?? '/images/panel-truong.jpg' }}" alt="{{ $competition->title }}"
                                style="width: 100%; height: 200px; object-fit: cover;">
                        </a>
                        <div class="card-body" style="padding: 20px;">
                            <h3 style="font-size: 20px; font-weight: 600; margin: 0 0 12px;">
                                <a href="{{ route('competitions.show', $competition) }}"
                                    style="text-decoration: none; color: #0f172a;">
                                    {{ $competition->title }}
                                </a>
                            </h3>
                            <p style="font-size: 14px; color: var(--muted); margin: 0 0 16px;">
                                ⏳ Kết thúc: {{ $competition->end_date->format('d/m/Y') }}
                            </p>
                            <a href="{{ route('competitions.show', $competition) }}" class="btn btn-primary"
                                style="display: inline-block; padding: 10px 20px; font-weight: 600; text-decoration: none;">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top: 32px;">
                {{ $competitions->links() }}
            </div>
        @else
            <p>Hiện tại không có cuộc thi nào đang mở.</p>
        @endif
    </div>
@endsection