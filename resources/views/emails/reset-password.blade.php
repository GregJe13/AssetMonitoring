<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #4F46E5;
            margin-bottom: 25px;
        }
        .header h1 {
            color: #4F46E5;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(to right, #4F46E5, #7C3AED);
            color: #ffffff !important;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            opacity: 0.9;
        }
        .warning {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 12px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
            margin-top: 25px;
            font-size: 12px;
            color: #666;
        }
        .link-text {
            word-break: break-all;
            font-size: 12px;
            color: #666;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Password</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $user->name }}</strong>,</p>
            
            <p>Kami menerima permintaan untuk mereset password akun Anda di <strong>Monitoring Dashboard</strong>.</p>
            
            <p>Klik tombol di bawah ini untuk mereset password Anda:</p>
            
            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="button">Reset Password</a>
            </div>
            
            <div class="warning">
                ⚠️ <strong>Penting:</strong> Link ini hanya berlaku selama <strong>1 jam</strong>. Setelah itu, Anda perlu meminta link reset baru.
            </div>
            
            <p>Jika Anda tidak meminta reset password, abaikan email ini dan password Anda tidak akan berubah.</p>
            
            <p>Jika tombol di atas tidak berfungsi, copy dan paste URL berikut ke browser Anda:</p>
            <div class="link-text">
                {{ $resetUrl }}
            </div>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Monitoring Dashboard. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
