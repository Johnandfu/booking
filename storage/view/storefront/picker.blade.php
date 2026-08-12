<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book a time</title>
    <style>
        :root { --bg:#f7f7f5; --card:#fff; --text:#1c1917; --muted:#78716c; --line:#e7e5e4; --brand:#0f766e; --ok:#15803d; --danger:#b91c1c; }
        * { box-sizing: border-box; }
        body { margin:0; font-family: "Iowan Old Style", "Palatino Linotype", Palatino, Georgia, serif; background: radial-gradient(1200px 600px at 10% -10%, #d9f3ef 0%, transparent 55%), linear-gradient(180deg, #f7f7f5, #efece7); color:var(--text); min-height:100vh; }
        main { max-width:720px; margin:0 auto; padding:28px 18px 48px; }
        h1 { font-size:34px; margin:0 0 6px; letter-spacing:-0.02em; }
        .meta { color:var(--muted); font-size:14px; margin-bottom:22px; }
        .card { background:var(--card); border:1px solid var(--line); border-radius:18px; padding:18px; box-shadow:0 10px 30px rgba(28,25,23,.04); }
        label { display:block; font-size:12px; color:var(--muted); margin:10px 0 4px; font-family: ui-sans-serif, system-ui, sans-serif; }
        input, select, button { width:100%; padding:10px 12px; border:1px solid var(--line); border-radius:10px; font:inherit; font-family: ui-sans-serif, system-ui, sans-serif; }
        button { background:var(--brand); color:#fff; border:none; cursor:pointer; margin-top:14px; font-weight:600; }
        button.secondary { background:#fff; color:var(--text); border:1px solid var(--line); }
        .slots { display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:8px; margin-top:12px; }
        .slot { padding:10px; border:1px solid var(--line); border-radius:10px; background:#fff; cursor:pointer; text-align:center; font-family: ui-sans-serif, system-ui, sans-serif; font-size:13px; }
        .slot.active { border-color:var(--brand); background:#ecfdf8; }
        .msg { min-height:20px; font-size:13px; color:var(--muted); margin-top:10px; font-family: ui-sans-serif, system-ui, sans-serif; }
        .msg.ok { color:var(--ok); }
        .msg.err { color:var(--danger); }
        .result { margin-top:12px; padding:12px; background:#f0fdf4; border-radius:12px; font-family: ui-sans-serif, system-ui, sans-serif; font-size:13px; display:none; }
    </style>
</head>
<body>
<main>
    <h1>Book a time</h1>
    <div class="meta">{{ $handle }} · {{ $timezone }}</div>
    <section class="card">
        <label>Service</label>
        <select id="serviceId"></select>
        <label>Location</label>
        <select id="locationId"></select>
        <label>Date</label>
        <input id="date" type="date">
        <button class="secondary" type="button" onclick="loadSlots()">Show available times</button>
        <div class="slots" id="slots"></div>
        <label>Name</label>
        <input id="customerName" placeholder="Your name">
        <label>Email</label>
        <input id="customerEmail" type="email" placeholder="you@example.com">
        <label>Phone</label>
        <input id="customerPhone" placeholder="+1...">
        <button type="button" onclick="book()">Reserve</button>
        <div class="msg" id="msg"></div>
        <div class="result" id="result"></div>
    </section>
</main>
<script>
const handle = @json($handle);
const token = @json($token);
const apiBase = `/storefront/api/${handle}`;
let selectedSlotId = null;
let lockToken = null;

async function api(path, options = {}) {
    const res = await fetch(apiBase + path, {
        ...options,
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token,
            ...(options.headers || {}),
        },
    });
    const json = await res.json();
    if (!res.ok || json.code !== 200) {
        throw new Error(json.message || 'Request failed');
    }
    return json.data;
}

function setMsg(text, ok) {
    const el = document.getElementById('msg');
    el.textContent = text || '';
    el.className = 'msg ' + (ok === true ? 'ok' : (ok === false ? 'err' : ''));
}

async function boot() {
    const today = new Date();
    document.getElementById('date').value = today.toISOString().slice(0, 10);
    const [services, locations] = await Promise.all([
        api('/services'),
        api('/locations'),
    ]);
    const svc = document.getElementById('serviceId');
    const loc = document.getElementById('locationId');
    svc.innerHTML = (services.list || []).map(s => `<option value="${s.id}">${s.name} (${s.duration_minutes}m)</option>`).join('');
    loc.innerHTML = (locations.list || []).map(l => `<option value="${l.id}">${l.name}</option>`).join('');
}

async function loadSlots() {
    selectedSlotId = null;
    lockToken = null;
    setMsg('Loading...');
    try {
        const data = await api(`/availability?service_id=${encodeURIComponent(document.getElementById('serviceId').value)}&location_id=${encodeURIComponent(document.getElementById('locationId').value)}&date=${encodeURIComponent(document.getElementById('date').value)}`);
        const box = document.getElementById('slots');
        const list = data.list || [];
        if (!list.length) {
            box.innerHTML = '';
            setMsg('No available times', false);
            return;
        }
        box.innerHTML = list.map(s => {
            const label = (s.start_at_local || s.start_at || '').slice(11,16) + ' – ' + (s.end_at_local || s.end_at || '').slice(11,16);
            return `<button type="button" class="slot" data-id="${s.slot_id}" onclick="selectSlot(${s.slot_id}, this)">${label}</button>`;
        }).join('');
        setMsg(`${list.length} slots`, true);
    } catch (e) {
        setMsg(e.message, false);
    }
}

function selectSlot(id, el) {
    selectedSlotId = id;
    lockToken = null;
    document.querySelectorAll('.slot').forEach(n => n.classList.remove('active'));
    el.classList.add('active');
}

async function book() {
    if (!selectedSlotId) {
        setMsg('Select a time first', false);
        return;
    }
    setMsg('Reserving...');
    try {
        if (!lockToken) {
            const lock = await api('/slot-locks', {
                method: 'POST',
                body: JSON.stringify({ slot_id: selectedSlotId }),
            });
            lockToken = lock.lock_token;
        }
        const booking = await api('/bookings', {
            method: 'POST',
            body: JSON.stringify({
                slot_id: selectedSlotId,
                lock_token: lockToken,
                customer_name: document.getElementById('customerName').value,
                customer_email: document.getElementById('customerEmail').value,
                customer_phone: document.getElementById('customerPhone').value,
            }),
        });
        const result = document.getElementById('result');
        result.style.display = 'block';
        result.innerHTML = `Reserved <strong>${booking.booking_no}</strong> (${booking.status}). Enter this booking number in Checkout (Checkout UI Customizer) so the order can confirm your reservation.`;
        try { localStorage.setItem('booking_no', booking.booking_no); } catch (e) {}
        setMsg('Pending booking created — use booking number at checkout', true);
    } catch (e) {
        setMsg(e.message, false);
    }
}

boot().catch(e => setMsg(e.message, false));
</script>
</body>
</html>
