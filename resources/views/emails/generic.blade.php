<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            font-family: 'Outfit', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            color: #1e293b;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            margin-top: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }
        .header {
            padding: 40px 0;
            text-align: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        .logo {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -1px;
            color: #ffffff;
            text-decoration: none;
        }
        .logo span {
            color: #3b82f6;
        }
        .content {
            padding: 40px;
        }
        h1 {
            font-size: 24px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 16px;
            color: #0f172a;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 24px;
            color: #475569;
        }
        .details-box {
            background-color: #f1f5f9;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .detail-row {
            margin-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
        }
        .detail-row:last-child {
            margin-bottom: 0;
            border-bottom: none;
            padding-bottom: 0;
        }
        .detail-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 15px;
            font-weight: 600;
            color: #1e293b;
        }
        .footer {
            text-align: center;
            padding: 24px;
            font-size: 13px;
            color: #94a3b8;
        }
        .button {
            display: inline-block;
            padding: 16px 32px;
            background-color: #3b82f6;
            color: #ffffff;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            margin-top: 24px;
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main" align="center">
            <tr>
                <td class="header">
                    <div class="logo">
                        {{ \App\Models\Setting::get('site_name', 'Silva Stone') }}<span>.</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <h1>{{ $title }}</h1>
                    <p>{{ $message_text }}</p>

                    @if(!empty($details))
                    <div class="details-box">
                        @foreach($details as $label => $value)
                        <div class="detail-row">
                            <div class="detail-label">{{ $label }}</div>
                            <div class="detail-value">{{ is_array($value) ? implode(', ', $value) : $value }}</div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if(isset($button_url))
                    <div style="text-align: center;">
                        <a href="{{ $button_url }}" class="button">{{ $button_text ?? 'Görüntüle' }}</a>
                    </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="footer">
                    &copy; {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Silva Stone') }}. Tüm hakları saklıdır.<br>
                    {{ \App\Models\Setting::get('contact_address') }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
