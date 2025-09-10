<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password OTP</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            max-width: 600px;
            margin: 50px auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header {
            background-color: #f1f1f1;
            /* color: #fff; */
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 30px;
        }

        .otp-box {
            background-color: #f1f1f1;
            padding: 20px;
            font-size: 24px;
            letter-spacing: 6px;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        a.button {
            background-color: #0d6efd;
            color: white;
            padding: 10px 25px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <div class="container">
        {{-- <div class="header">
            <h1>Reset Password</h1>
        </div> --}}
        <div class="content">
            <p>Halo,</p>
            <p>Kami menerima permintaan untuk mereset password Anda. Gunakan kode OTP berikut untuk melanjutkan proses reset password:</p>
            <div class="otp-box">{{ $otp }}</div>
            {{-- <p>Kode ini hanya berlaku selama 15 menit.</p> --}}
            <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
