<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Georgia, serif; background: #f8f4ee; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.1); }
        .header { background: #0f2044; padding: 2.5rem 2rem; text-align: center; }
        .header h1 { font-family: Georgia, serif; color: #c8953a; font-size: 1.8rem; margin: 0; }
        .header p { color: rgba(255,255,255,.7); margin: .5rem 0 0; font-size: .9rem; }
        .body { padding: 2rem; }
        .body h2 { color: #0f2044; font-size: 1.4rem; margin-bottom: .5rem; }
        .body p { color: #4a5568; line-height: 1.7; margin-bottom: 1rem; }
        .event-box { background: #f8f4ee; border-left: 4px solid #c8953a; padding: 1rem 1.5rem; border-radius: 4px; margin: 1.5rem 0; }
        .event-box h3 { color: #0f2044; margin: 0 0 .5rem; font-size: 1.2rem; }
        .event-box p { margin: .25rem 0; font-size: .9rem; color: #4a5568; }
        .btn { display: block; background: #c8953a; color: #0f2044; text-decoration: none; text-align: center; padding: 1rem 2rem; border-radius: 6px; font-weight: bold; font-size: 1rem; margin: 2rem 0; }
        .footer { background: #0f2044; padding: 1.5rem; text-align: center; color: rgba(255,255,255,.5); font-size: .8rem; }
        .footer strong { color: #c8953a; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>AlumniConnect</h1>
        <p>You have been cordially invited</p>
    </div>
    <div class="body">
        <h2>Dear {{ $alumni->first_name }},</h2>
        <p>
            We are delighted to invite you to an upcoming alumni gathering. Your school family
            misses you and would love to reconnect with you at this special event.
        </p>

        <div class="event-box">
            <h3>{{ $event->title }}</h3>
            <p>📅 {{ $event->event_date->format('l, F j, Y · g:i A') }}</p>
            @if($event->venue)
                <p>📍 {{ $event->venue }}</p>
            @endif
            @if($event->description)
                <p style="margin-top:.75rem">{{ $event->description }}</p>
            @endif
        </div>

        <p>
            Please let us know if you'll be joining us. Click the button below to confirm your RSVP:
        </p>

        <a href="{{ $rsvpUrl }}" class="btn">✅ RSVP Now</a>

        <p style="font-size:.85rem;color:#718096">
            If the button doesn't work, copy this link into your browser:<br>
            <span style="color:#c8953a">{{ $rsvpUrl }}</span>
        </p>

        <p>
            We look forward to seeing you there, {{ $alumni->first_name }}!<br>
            With warm regards,<br>
            <strong>The Alumni Affairs Office</strong>
        </p>
    </div>
    <div class="footer">
        <strong>AlumniConnect</strong> · Alumni Management System<br>
        You received this because you are a registered alumnus.
    </div>
</div>
</body>
</html>
