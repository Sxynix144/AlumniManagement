<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AlumniConnect') — Alumni Management System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:   #0f2044;
            --navy2:  #1a3566;
            --gold:   #c8953a;
            --gold2:  #e8b44d;
            --cream:  #f8f4ee;
            --white:  #ffffff;
            --slate:  #4a5568;
            --light:  #edf2f7;
            --danger: #c53030;
            --radius: 8px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: var(--navy);
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(200,149,58,.12) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(26,53,102,.8) 0%, transparent 50%);
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            width: 45%;
            background: linear-gradient(160deg, var(--navy2) 0%, var(--navy) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -100px; left: -80px;
            width: 400px; height: 400px;
            border-radius: 50%;
            border: 60px solid rgba(200,149,58,.08);
            pointer-events: none;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -120px; right: -60px;
            width: 300px; height: 300px;
            border-radius: 50%;
            border: 50px solid rgba(200,149,58,.06);
            pointer-events: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 3rem;
            position: relative;
            z-index: 1;
        }
        .brand-icon {
            width: 52px; height: 52px;
            background: var(--gold);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--navy);
            font-weight: 700;
            flex-shrink: 0;
        }
        .brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--white);
            font-weight: 700;
        }
        .brand-sub {
            font-size: .78rem;
            color: rgba(255,255,255,.5);
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .left-heading {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            color: var(--white);
            line-height: 1.2;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }
        .left-heading em { color: var(--gold2); font-style: normal; }

        .left-desc {
            color: rgba(255,255,255,.6);
            line-height: 1.7;
            font-size: .95rem;
            max-width: 360px;
            position: relative;
            z-index: 1;
        }

        .left-features {
            margin-top: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: .9rem;
            position: relative;
            z-index: 1;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: rgba(255,255,255,.7);
            font-size: .9rem;
        }
        .feature-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--gold);
            flex-shrink: 0;
        }

        /* ── RIGHT PANEL (form area) ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--cream);
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
            background: var(--white);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 8px 40px rgba(15,32,68,.15);
            border-top: 4px solid var(--gold);
        }

        .auth-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.7rem;
            color: var(--navy);
            margin-bottom: .3rem;
        }
        .auth-card .auth-subtitle {
            color: var(--slate);
            font-size: .9rem;
            margin-bottom: 2rem;
        }

        /* ── FORM ── */
        .form-group { margin-bottom: 1.2rem; }
        .form-group label {
            display: block;
            font-size: .78rem;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .07em;
        }
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="text"],
        .form-group input[type="number"] {
            width: 100%;
            padding: .75rem 1rem;
            border: 1.5px solid #d1d5db;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: .92rem;
            color: var(--navy);
            background: var(--white);
            transition: border-color .15s, box-shadow .15s;
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(200,149,58,.15);
        }
        .form-error { color: var(--danger); font-size: .78rem; margin-top: .3rem; }

        .btn-submit {
            width: 100%;
            padding: .85rem;
            background: var(--navy);
            color: var(--white);
            border: none;
            border-radius: var(--radius);
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
            margin-top: .5rem;
        }
        .btn-submit:hover { background: var(--navy2); }
        .btn-submit.gold { background: var(--gold); color: var(--navy); }
        .btn-submit.gold:hover { background: var(--gold2); }

        .auth-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: .88rem;
            color: var(--slate);
        }
        .auth-footer a {
            color: var(--navy);
            font-weight: 700;
            text-decoration: none;
        }
        .auth-footer a:hover { color: var(--gold); }

        .divider {
            border: none;
            border-top: 1px solid var(--light);
            margin: 1.5rem 0;
        }

        .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .remember-row label {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .88rem;
            color: var(--slate);
            cursor: pointer;
        }
        .remember-row input[type="checkbox"] {
            accent-color: var(--gold);
            width: 15px; height: 15px;
        }
        .forgot-link { font-size: .85rem; color: var(--slate); text-decoration: none; }
        .forgot-link:hover { color: var(--gold); }

        .alert-info {
            background: #ebf8ff;
            border: 1px solid #bee3f8;
            border-left: 4px solid #3182ce;
            border-radius: var(--radius);
            padding: .85rem 1rem;
            font-size: .88rem;
            color: #2a4365;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: #f0fff4;
            border-left: 4px solid #38a169;
            border-radius: var(--radius);
            padding: .85rem 1rem;
            font-size: .88rem;
            color: #22543d;
            margin-bottom: 1.5rem;
        }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel { background: var(--navy); }
            .auth-card { box-shadow: 0 20px 60px rgba(0,0,0,.3); }
        }
    </style>
</head>
<body>

<div class="left-panel">
    <div class="brand">
        <div class="brand-icon">A</div>
        <div>
            <div class="brand-name">AlumniConnect</div>
            <div class="brand-sub">Alumni Management System</div>
        </div>
    </div>

    <h2 class="left-heading">@yield('panel-heading', 'Stay connected with your <em>school family</em>')</h2>
    <p class="left-desc">@yield('panel-desc', 'Update your information, join reunions, and remain part of the community — wherever life has taken you.')</p>

    <div class="left-features">
        <div class="feature-item"><div class="feature-dot"></div> Claim your school record in minutes</div>
        <div class="feature-item"><div class="feature-dot"></div> Receive personalized event invitations</div>
        <div class="feature-item"><div class="feature-dot"></div> Keep your contact info current</div>
        <div class="feature-item"><div class="feature-dot"></div> Reconnect with your batchmates</div>
    </div>
</div>

<div class="right-panel">
    <div class="auth-card">
        @yield('content')
    </div>
</div>

</body>
</html>
