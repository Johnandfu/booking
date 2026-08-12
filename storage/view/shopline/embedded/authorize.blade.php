<!DOCTYPE html>
<html lang="{{ $locale !== '' ? $locale : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authorize Booking App</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; margin: 0; padding: 32px; color: #1f2329; background: #f7f8fa; }
        .card { max-width: 480px; margin: 48px auto; padding: 24px; background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(15, 23, 42, .06); }
        h1 { font-size: 20px; margin: 0 0 8px; }
        p { margin: 0 0 16px; color: #646a73; line-height: 1.5; }
        .status { font-size: 14px; color: #8f959e; }
        .error { color: #d83931; white-space: pre-wrap; }
    </style>
</head>
<body>
<div class="card">
    <h1>Calendar Booking</h1>
    <p>Redirecting to SHOPLINE authorization…</p>
    <p class="status" id="status">Initializing App Bridge</p>
    <p class="error" id="error" hidden></p>
</div>
<script type="module">
    import Client, { Oauth, shared } from 'https://esm.sh/@shoplineos/app-bridge@0.0.2';

    const appKey = @json($appKey);
    const scopes = @json($scopes);
    const redirectUri = @json($redirectUri);
    const fallbackHost = @json($host);
    const statusEl = document.getElementById('status');
    const errorEl = document.getElementById('error');

    function fail(message) {
        statusEl.textContent = 'Authorization failed';
        errorEl.hidden = false;
        errorEl.textContent = message;
    }

    try {
        const host = (typeof shared.getHost === 'function' ? shared.getHost() : '') || fallbackHost;
        const app = Client.createApp({ appKey, host });
        statusEl.textContent = 'Opening authorization page…';
        Oauth.create(app).invoke({
            scope: scopes,
            appKey,
            redirectUri,
        });
    } catch (e) {
        fail(e?.message || String(e));
    }
</script>
</body>
</html>
