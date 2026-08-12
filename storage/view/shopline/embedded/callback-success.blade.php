<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authorization Complete</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; margin: 0; padding: 32px; color: #1f2329; background: #f7f8fa; }
        .card { max-width: 520px; margin: 48px auto; padding: 28px; background: #fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(15, 23, 42, .06); }
        h1 { font-size: 20px; margin: 0 0 8px; }
        p { margin: 0 0 12px; color: #646a73; line-height: 1.6; }
        code { background: #f2f3f5; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="card">
    <h1>Authorization complete</h1>
    <p>Store <code>{{ $handle }}</code> is connected to Calendar Booking.</p>
    <p>You can close this page and reopen the app from SHOPLINE Admin.</p>
</div>
<script type="module">
    import Client, { shared } from 'https://esm.sh/@shoplineos/app-bridge@0.0.2';

    const appKey = @json($appKey);
    const fallbackHost = @json($host);

    try {
        const host = (typeof shared.getHost === 'function' ? shared.getHost() : '') || fallbackHost;
        Client.createApp({ appKey, host });
    } catch (e) {
        // Callback may open outside iframe; App Bridge init is best-effort.
        console.warn('App Bridge init skipped', e);
    }
</script>
</body>
</html>
