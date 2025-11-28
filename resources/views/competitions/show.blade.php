@extends('layouts.main')

@section('title', $competition->title)

@section('content')
  <div class="container" style="padding: 32px 0;">
    @if (session('success'))
      <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="flash-error">{{ session('error') }}</div>
    @endif
    @if (session('info'))
      <div class="flash-info">{{ session('info') }}</div>
    @endif

    <div class="card" style="overflow:hidden;">
      <div style="width:100%; height:240px; background:#f3f4f6; overflow:hidden;">
        <img src="{{ $competition->banner_url ?? asset('images/panel-truong.jpg') }}" alt="{{ $competition->title }}" style="width:100%; height:100%; object-fit:cover;">
      </div>
      <div class="card-body" style="padding:24px;">
        <h1 style="font-size:28px; font-weight:800; margin:0 0 8px;">{{ $competition->title }}</h1>
        <div style="margin:8px 0 16px; color:#6b7280;">
          <span>Trạng thái: <strong>{{ $competition->status }}</strong></span>
          @if($competition->end_date)
            <span style="margin-left:16px;">Hạn chót: <strong>{{ $competition->end_date->format('d/m/Y H:i') }}</strong></span>
          @endif
        </div>

        <div class="prose" style="margin: 16px 0;">
          {!! $competition->description !!}
        </div>

        <div style="margin-top: 24px; display:flex; gap:12px; align-items:center;">
          @php
            $isOpen = $competition->status === 'open' && (!$competition->end_date || $competition->end_date->isFuture());
            $hasRegistered = auth()->check() && $competition->registrations()->where('user_id', auth()->id())->exists();
          @endphp

          @if ($isOpen)
            @auth
              @if ($hasRegistered)
                <span class="btn btn-ghost" style="cursor: default;">Bạn đã đăng ký cuộc thi này</span>
                <a class="btn btn-primary" href="{{ route('my-competitions.index') }}">Xem cuộc thi của tôi</a>
              @else
                <form method="POST" action="{{ route('competitions.register', $competition) }}">
                  @csrf
                  <button type="submit" class="btn btn-primary">Đăng ký ngay</button>
                </form>
              @endif
            @else
              <a class="btn btn-primary" href="{{ route('login') }}">Đăng nhập để đăng ký</a>
            @endauth
          @else
            <button class="btn btn-ghost" disabled>Cuộc thi đã đóng hoặc chưa mở</button>
          @endif

          <a class="btn btn-ghost" href="{{ route('competitions.index') }}">← Quay lại danh sách</a>
        </div>
      </div>
    </div>
  </div>
@endsection
