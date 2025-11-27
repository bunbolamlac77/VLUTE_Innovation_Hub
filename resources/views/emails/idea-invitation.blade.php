<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lời mời tham gia ý tưởng</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 32px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid #0a0f5a;
        }

        .header h1 {
            color: #0a0f5a;
            margin: 0 0 8px;
            font-size: 24px;
        }

        .header p {
            color: #6b7280;
            margin: 0;
            font-size: 14px;
        }

        .content {
            margin-bottom: 32px;
        }

        .idea-info {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            border-left: 4px solid #0a0f5a;
        }

        .idea-info h2 {
            color: #0a0f5a;
            margin: 0 0 12px;
            font-size: 20px;
        }

        .idea-info p {
            color: #374151;
            margin: 0;
        }

        .inviter-info {
            margin-bottom: 24px;
            padding: 16px;
            background-color: #f0fdf4;
            border-radius: 8px;
        }

        .inviter-info strong {
            color: #065f46;
        }

        .actions {
            text-align: center;
            margin-top: 32px;
            padding-top: 32px;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 0 8px 8px 0;
            transition: all 0.2s;
        }

        .btn-accept {
            background-color: #10b981;
            color: #ffffff;
        }

        .btn-accept:hover {
            background-color: #059669;
        }

        .btn-decline {
            background-color: #6b7280;
            color: #ffffff;
        }

        .btn-decline:hover {
            background-color: #4b5563;
        }

        .footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        .note {
            background-color: #fef3c7;
            padding: 12px;
            border-radius: 6px;
            margin-top: 16px;
            font-size: 14px;
            color: #92400e;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>💡 Lời mời tham gia ý tưởng</h1>
            <p>VLUTE Innovation Hub</p>
        </div>

        <div class="content">
            <p>Xin chào,</p>

            <p>
                <strong>{{ $inviter->name }}</strong> đã mời bạn tham gia vào nhóm phát triển ý tưởng sau:
            </p>

            <div class="idea-info">
                <h2>{{ $idea->title }}</h2>
                @if ($idea->description)
                    <p>{{ Str::limit($idea->description, 200) }}</p>
                @endif
            </div>

            <div class="inviter-info">
                <strong>Người mời:</strong> {{ $inviter->name }}<br>
                <strong>Email:</strong> {{ $inviter->email }}<br>
                <strong>Vai trò mời:</strong> {{ $invitation->role === 'mentor' ? 'Cố vấn (Mentor)' : 'Thành viên' }}
            </div>

            <div class="actions">
                <p style="margin-bottom: 16px; color: #374151;">
                    Bạn có muốn tham gia vào nhóm phát triển ý tưởng này không?
                </p>

                <a href="{{ $acceptUrl }}" class="btn btn-accept">
                    ✅ Chấp nhận
                </a>

                <a href="{{ $declineUrl }}" class="btn btn-decline">
                    ❌ Từ chối
                </a>
            </div>

            <div class="note">
                <strong>Lưu ý:</strong> Link này sẽ hết hạn sau 7 ngày. Nếu bạn chưa có tài khoản, bạn sẽ được yêu cầu
                đăng ký trước khi chấp nhận lời mời.
            </div>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống VLUTE Innovation Hub.</p>
            <p>Nếu bạn không mong muốn nhận email này, vui lòng bỏ qua.</p>
        </div>
    </div>
</body>

</html>