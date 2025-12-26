<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4F46E5; color: white; padding: 20px; text-align: center; }
        .otp-box { background: #F3F4F6; padding: 30px; text-align: center; border-radius: 10px; margin: 20px 0; }
        .otp-code { font-size: 36px; font-weight: bold; color: #4F46E5; letter-spacing: 5px; margin: 10px 0; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to E-Commerce Platform!</h1>
        </div>

        <div class="content">
            <h2>Hello <?php echo e($user->name); ?>,</h2>
            <p>Your account has been created successfully!</p>
            
            <div class="otp-box">
                <h3>Your OTP Code</h3>
                <div class="otp-code"><?php echo e($otp); ?></div>
                <p><strong>Valid for <?php echo e($expires_in); ?></strong></p>
            </div>

            <p>Enter this code to verify your email address and complete your registration.</p>
            
            <div style="background: #EFF6FF; padding: 20px; border-left: 4px solid #4F46E5; margin: 20px 0;">
                <p><strong>Need help?</strong></p>
                <ul>
                    <li>OTP expires in 5 minutes</li>
                    <li>Didn't request this? Ignore this email</li>
                    <li>Request new OTP if expired</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>© 2025 E-Commerce Platform. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\major-project\vibe-mart\resources\views/emails/otp.blade.php ENDPATH**/ ?>