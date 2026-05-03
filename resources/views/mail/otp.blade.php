<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your One-Time Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .logo {
            font-size: 42px;
            margin-bottom: 15px;
        }
        
        h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px;
        }
        
        .message {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            color: #555;
        }
        
        .otp-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e9ecef;
        }
        
        .otp-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 10px;
            display: block;
        }
        
        .otp-code {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .otp-digits {
            display: flex;
            gap: 10px;
        }
        
        .digit {
            width: 50px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e0e0e0;
        }
        
        .copy-btn {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .copy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 117, 252, 0.3);
        }
        
        .copy-btn.copied {
            background: linear-gradient(135deg, #20bf6b 0%, #01baef 100%);
        }
        
        .validity {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6c757d;
            font-size: 14px;
            margin-top: 10px;
        }
        
        .validity i {
            color: #20bf6b;
        }
        
        .footer {
            padding: 20px 40px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        
        .security-note {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
            margin-top: 10px;
        }
        
        .security-note i {
            color: #6a11cb;
        }
        
        @media (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }
            
            .otp-code {
                flex-direction: column;
                gap: 20px;
            }
            
            .otp-digits {
                width: 100%;
                justify-content: center;
            }
            
            .digit {
                width: 40px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1>One-Time Password</h1>
            <p class="subtitle">Your secure verification code</p>
        </div>
        
        <div class="content">
            <p class="message">
                Use the following One-Time Password (OTP) to complete your verification process. 
                This code is valid for a limited time only.
            </p>
            
            <div style="font-family: Arial, sans-serif; color: #333; text-align:center;">
                <h2 style="margin-bottom: 15px; font-size: 20px;">Your OTP Code</h2>
                
                <div style="
                    display: inline-block;
                    background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                    color: #fff;
                    padding: 20px 35px;
                    border-radius: 12px;
                    font-size: 28px;
                    font-weight: bold;
                    letter-spacing: 10px;
                    box-shadow: 0 4px 15px rgba(37, 117, 252, 0.3);
                ">
                    {{ implode(' ', str_split($otp)) }}
                </div>
                
                <p style="margin-top: 20px; color: #6c757d;">
                    This OTP is valid for <strong>{{$ttl}} minutes</strong>.
                </p>
            </div>


            
            <p class="message">
                For security reasons, please do not share this code with anyone. 
                If you did not request this code, please ignore this message.
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <div class="security-note">
                <i class="fas fa-lock"></i>
                <span>Secured with end-to-end encryption</span>
            </div>
        </div>
    </div>


</body>
</html>
