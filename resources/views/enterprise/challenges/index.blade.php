@extends('layouts.main')

@section('title', 'Quản lý Thách thức - Doanh nghiệp')

@section('content')
<section class="container" style="padding: 32px 0;">
    <div style="display:flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 16px; flex-wrap: wrap;">
        <div>
            <h1 style="margin:0; font-size: 24px; font-weight: 800; color:#0f172a;">Quản lý Thách thức (Challenges)</h1>
            <div style="color:#64748b; font-size: 13px;">Danh sách các thách thức do doanh nghiệp của bạn tạo</div>
        </div>
        <div>
            <a href="{{ route('enterprise.challenges.create') }}" class="btn btn-primary">+ Tạo Thách thức mới</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($challenges->isEmpty())
                <div class="muted">Chưa có challenge nào. Hãy tạo cái đầu tiên!</div>
            @else
                <div class="table-responsive" style="border: 1px solid #eef2f7; border-radius: 14px; overflow: hidden;">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Hạn chót</th>
                                <th>Phần thưởng</th>
                                <th>Bài nộp</th>
                                <th class="cell-right">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($challenges as $c)
                                <tr>
                                    <td style="font-weight:700; color:#0f172a;">{{ $c->title }}</td>
                                    <td class="muted">{{ $c->deadline ? $c->deadline->format('d/m/Y') : 'Chưa đặt' }}</td>
                                    <td style="font-weight:700; color:#047857;">{{ $c->reward ?? '—' }}</td>
                                    <td style="text-align:center;">
                                        <span class="badge badge-blue">{{ $c->submissions_count }}</span>
                                    </td>
                                    <td class="cell-right">
                                        <a href="{{ route('enterprise.challenges.show', $c->id) }}" class="btn btn-ghost">Xem chi tiết</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 12px;">
                    {{ $challenges->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

