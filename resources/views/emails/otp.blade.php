<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Password Reset OTP</title>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    padding:0;
    background:#f3f4f6;
    font-family:'Inter',Arial,sans-serif;
}
.wrapper{
    width:100%;
    padding:40px 15px;
}
.container{
    max-width:600px;
    margin:auto;
    background:#ffffff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 10px 35px rgba(0,0,0,0.08);
}
.header{
    background:linear-gradient(135deg,#4f46e5,#7c3aed);
    padding:35px 30px;
    text-align:center;
    color:#fff;
}
.header h1{
    margin:0;
    font-size:28px;
    font-weight:800;
}
.header p{
    margin-top:8px;
    font-size:14px;
    opacity:.9;
}
.content{
    padding:35px 30px;
    color:#374151;
}
.content h2{
    margin-top:0;
    font-size:22px;
    color:#111827;
}
.content p{
    font-size:15px;
    line-height:1.7;
    margin:14px 0;
}
.otp-box{
    margin:30px 0;
    text-align:center;
}
.otp{
    display:inline-block;
    padding:18px 35px;
    font-size:34px;
    font-weight:800;
    letter-spacing:8px;
    color:#4f46e5;
    background:#eef2ff;
    border:2px dashed #6366f1;
    border-radius:14px;
}
.notice{
    background:#f9fafb;
    border-left:4px solid #f59e0b;
    padding:14px 16px;
    border-radius:8px;
    font-size:14px;
    color:#4b5563;
    margin-top:20px;
}
.footer{
    padding:25px 30px;
    text-align:center;
    font-size:13px;
    color:#9ca3af;
    border-top:1px solid #e5e7eb;
}
@media(max-width:600px){
    .header,
    .content,
    .footer{
        padding:25px 20px;
    }
    .otp{
        font-size:28px;
        letter-spacing:5px;
        padding:15px 22px;
    }
}
</style>
</head>

<body>
<div class="wrapper">
    <div class="container">

        <div class="header">
            <h1>Password Reset</h1>
            <p>Secure Verification Code</p>
        </div>

        <div class="content">
            <h2>Hello 👋</h2>

            <p>
                We received a request to reset your password.
                Use the OTP code below to continue:
            </p>

            <div class="otp-box">
                <div class="otp">{{ $otp }}</div>
            </div>

            <p>
                Enter this code in the verification page to reset your password.
            </p>

            <div class="notice">
                ⏰ This OTP will expire in <strong>5 minutes</strong>.<br>
                If you did not request a password reset, please ignore this email.
            </div>
        </div>

        <div class="footer">
            © {{ date('Y') }} Your Website. All rights reserved.
        </div>

    </div>
</div>
</body>
</html>