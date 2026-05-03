<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Identity | Secure OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            overflow: hidden;
            position: relative;
        }
        
        .background-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
            animation: float 15s infinite ease-in-out;
        }
        
        .shape:nth-child(1) {
            width: 300px;
            height: 300px;
            background: linear-gradient(45deg, #ff0080, #ff8c00);
            top: 10%;
            left: 5%;
            animation-delay: 0s;
        }
        
        .shape:nth-child(2) {
            width: 200px;
            height: 200px;
            background: linear-gradient(45deg, #00ffcc, #00b3ff);
            top: 60%;
            left: 80%;
            animation-delay: 2s;
        }
        
        .shape:nth-child(3) {
            width: 250px;
            height: 250px;
            background: linear-gradient(45deg, #ffeb3b, #ff9800);
            top: 20%;
            left: 70%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        
        .container {
            width: 90%;
            max-width: 500px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shine 8s infinite linear;
            z-index: -1;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo i {
            font-size: 48px;
            background: linear-gradient(45deg, #ff0080, #ff8c00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 10px rgba(255, 140, 0, 0.5));
        }
        
        h1 {
            text-align: center;
            font-size: 32px;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #fff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 1px;
        }
        
        .subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.5;
        }
        
        .otp-container {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }
        
        .otp-input {
            width: 60px;
            height: 70px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            transition: all 0.3s ease;
        }
        
        .otp-input:focus {
            outline: none;
            border-color: #ff8c00;
            box-shadow: 0 0 15px rgba(255, 140, 0, 0.5);
            background: rgba(255, 255, 255, 0.15);
            transform: scale(1.05);
        }
        
        .otp-input.filled {
            border-color: #00ffcc;
            box-shadow: 0 0 10px rgba(0, 255, 204, 0.5);
        }
        
        .info-text {
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            margin: 20px 0;
            font-size: 14px;
        }
        
        .email-display {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 12px;
            border-radius: 10px;
            margin: 20px 0;
            font-weight: 500;
            letter-spacing: 0.5px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .btn {
            padding: 16px 30px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #ff0080, #ff8c00);
            color: white;
            flex: 1;
            margin-right: 15px;
            box-shadow: 0 5px 15px rgba(255, 140, 0, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 140, 0, 0.6);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-3px);
        }
        
        .timer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .timer span {
            color: #ff8c00;
            font-weight: bold;
        }
        
        .security-notice {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 25px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            font-size: 14px;
            gap: 10px;
        }
        
        .security-notice i {
            color: #00ffcc;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }
            
            .otp-input {
                width: 50px;
                height: 60px;
                font-size: 24px;
            }
            
            .buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn-primary {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="background-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="container">
        <div class="logo">
            <i class="fas fa-shield-alt"></i>
        </div>
        
        <h1>Verify Your Identity</h1>
        <p class="subtitle">We've sent a One-Time Password (OTP) to your registered email address. Enter the code below to continue.</p>
        
        <div class="email-display">
            johndoe@example.com
        </div>
        
        <div class="otp-container">
            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 1)">
            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 2)">
            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 3)">
            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 4)">
            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 5)">
            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 6)">
        </div>
        
        <p class="info-text">The OTP will expire in <span class="timer">04:59</span></p>
        
        <div class="buttons">
            <button class="btn btn-primary">
                <i class="fas fa-check-circle"></i> Verify & Continue
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-redo"></i> Resend Code
            </button>
        </div>
        
        <div class="security-notice">
            <i class="fas fa-lock"></i>
            <span>This is a secure process. Your information is protected.</span>
        </div>
    </div>

    <script>
        // Auto-focus first OTP input on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.otp-input').focus();
        });
        
        // Function to move to next input field
        function moveToNext(current, next) {
            if (current.value.length >= current.maxLength) {
                const nextInput = document.querySelectorAll('.otp-input')[next];
                if (nextInput) {
                    nextInput.focus();
                }
            }
            
            // Add filled class if input has value
            if (current.value.length > 0) {
                current.classList.add('filled');
            } else {
                current.classList.remove('filled');
            }
        }
        
        // Timer countdown
        let timeLeft = 5 * 60; // 5 minutes in seconds
        const timerElement = document.querySelector('.timer');
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft > 0) {
                timeLeft--;
            } else {
                timerElement.textContent = "00:00";
                timerElement.style.color = "#ff4757";
            }
        }
        
        setInterval(updateTimer, 1000);
        updateTimer(); // Initial call
    </script>
</body>
</html>
