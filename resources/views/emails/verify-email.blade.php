<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email — AlumniConnect</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8f4ee;
            padding: 40px 20px;
            color: #0f2044;
        }
        .wrapper { max-width: 600px; margin: 0 auto; }

        /* Header */
        .header {
            background: linear-gradient(135deg, #0f2044 0%, #1a3566 100%);
            border-radius: 12px 12px 0 0;
            padding: 40px;
            text-align: center;
        }
        .logo-circle {
            width: 64px; height: 64px;
            background: #c8953a;
            border-radius: 50%;
            display: inline-flex;
            align-items: center; justify-content: center;
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f2044;
            font-family: Georgia, serif;
            margin-bottom: 16px;
        }
        .header h1 {
            font-family: Georgia, serif;
            font-size: 1.5rem;
            color: #ffffff;
            margin-bottom: 4px;
        }
        .header p { color: rgba(255,255,255,.55); font-size: .8rem; letter-spacing: .1em; text-transform: uppercase; }

        /* Body */
        .body {
            background: #ffffff;
            padding: 40px;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }
        .greeting {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0f2044;
            margin-bottom: 16px;
        }
        .body p {
            color: #4a5568;
            line-height: 1.8;
            font-size: .95rem;
            margin-bottom: 16px;
        }

        /* Verify button */
        .btn-wrap { text-align: center; margin: 32px 0; }
        .btn-verify {
            display: inline-block;
            background: #c8953a;
            color: #0f2044 !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .02em;
        }

        /* Expiry notice */
        .notice {
            background: #fffbf0;
            border: 1px solid #f6e49a;
            border-left: 4px solid #c8953a;
            border-radius: 6px;
            padding: 12px 16px;
            font-size: .83rem;
            color: #92400e;
            margin: 24px 0;
        }

        /* URL fallback */
        .url-fallback {
            background: #f8f4ee;
            border-radius: 6px;
            padding: 12px 16px;
            font-size: .75rem;
            color: #718096;
            word-break: break-all;
            margin-top: 8px;
        }

        /* Steps */
        .steps { margin: 24px 0; }
        .step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }
        .step-num {
            width: 24px; height: 24px;
            background: #0f2044;
            color: #ffffff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .72rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .step p { margin: 0; font-size: .88rem; color: #4a5568; }

        /* Footer */
        .footer {
            background: #0f2044;
            border-radius: 0 0 12px 12px;
            padding: 24px 40px;
            text-align: center;
        }
        .footer p { color: rgba(255,255,255,.45); font-size: .75rem; line-height: 1.7; }
        .footer strong { color: #c8953a; }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="logo-circle">A</div>
        <h1>AlumniConnect</h1>
        <p>Alumni Management System</p>
    </div>

    <!-- Body -->
    <div class="body">
        <div class="greeting">Hello, {{ $user->name }}! 👋</div>

        <p>
            Thank you for registering with <strong>AlumniConnect</strong>.
            You're one step away from accessing your alumni profile, events, and the full community.
        </p>

        <p>
            Please verify your email address by clicking the button below.
            This confirms that you own this email and keeps your account secure.
        </p>

        <div class="btn-wrap">
            <a href="{{ $verificationUrl }}" class="btn-verify">
                ✅ Verify My Email Address
            </a>
        </div>

        <div class="notice">
            ⏰ <strong>This link expires in 60 minutes.</strong>
            If it expires, you can request a new one from the verification page after logging in.
        </div>

        <div class="steps">
            <p style="font-weight:600;margin-bottom:10px;color:#0f2044">What happens after verification?</p>
            <div class="step">
                <div class="step-num">1</div>
                <p>Your email is confirmed and your account becomes active.</p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <p>You can update your alumni profile with your current job and contact info.</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <p>You'll start receiving invitations to reunions and alumni events.</p>
            </div>
        </div>

        <p style="font-size:.82rem;color:#aaa;margin-top:24px">
            If the button doesn't work, copy and paste this link into your browser:
        </p>
        <div class="url-fallback">{{ $verificationUrl }}</div>

        <p style="margin-top:24px;font-size:.82rem;color:#aaa">
            If you did not create an account with AlumniConnect, you can safely ignore this email.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            <strong>AlumniConnect</strong> · Alumni Management System<br>
            This is an automated message — please do not reply to this email.<br>
            © {{ date('Y') }} AlumniConnect. All rights reserved.
        </p>
    </div>

</div>
</body>
</html>
