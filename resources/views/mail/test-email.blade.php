<!DOCTYPE html>
<html>
<head>
    <title>Test Email</title>
</head>
<body>
    <h1>Hello from Laravel!</h1>
    <p>This is a test email sent to your Mailtrap sandbox.</p>
    
    <div style="background-color: #f8f9fa; padding: 20px; margin: 20px 0;">
        <h3>Email Details:</h3>
        <ul>
            <li>Sent at: {{ now()->format('Y-m-d H:i:s') }}</li>
            <li>App: {{ config('app.name') }}</li>
            <li>Environment: {{ app()->environment() }}</li>
        </ul>
    </div>
    
    <p>If you received this email, your Mailtrap configuration is working correctly!</p>
</body>
</html>
