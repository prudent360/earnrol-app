<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Offline | {{ \App\Models\Setting::get('app_name', 'EarnRol') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f5f6fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 2rem; }
        .card { background: white; border-radius: 1.5rem; padding: 3rem; max-width: 420px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .icon { width: 64px; height: 64px; margin: 0 auto 1.5rem; background: #f5f6fa; border-radius: 1rem; display: flex; align-items: center; justify-content: center; }
        .icon svg { width: 32px; height: 32px; color: #9ca3af; }
        h1 { font-size: 1.25rem; font-weight: 700; color: #1a1a2e; margin-bottom: 0.5rem; }
        p { font-size: 0.875rem; color: #6b7280; line-height: 1.6; }
        button { margin-top: 1.5rem; background: #e05a3a; color: white; border: none; padding: 0.75rem 2rem; border-radius: 0.75rem; font-weight: 600; font-size: 0.875rem; cursor: pointer; }
        button:hover { background: #c94e31; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m-3.536-3.536a4 4 0 010-5.656M6.343 6.343a8 8 0 000 11.314m3.536-3.536a4 4 0 000-5.656"/><line x1="4" y1="4" x2="20" y2="20" stroke-width="2" stroke-linecap="round"/></svg>
        </div>
        <h1>You're Offline</h1>
        <p>It looks like you've lost your internet connection. Please check your network and try again.</p>
        <button onclick="window.location.reload()">Try Again</button>
    </div>
</body>
</html>
