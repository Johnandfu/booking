<!DOCTYPE html>
<html lang="{{ $locale !== '' ? $locale : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calendar Booking</title>
    <style>
        :root { --bg:#f5f6f8; --card:#fff; --text:#1f2329; --muted:#646a73; --line:#e5e6eb; --brand:#165dff; --ok:#00b42a; --danger:#f53f3f; }
        * { box-sizing: border-box; }
        body { margin:0; font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif; background:var(--bg); color:var(--text); }
        header { display:flex; justify-content:space-between; align-items:center; padding:16px 20px; background:var(--card); border-bottom:1px solid var(--line); }
        h1 { font-size:18px; margin:0; }
        .meta { color:var(--muted); font-size:12px; }
        main { max-width:1100px; margin:0 auto; padding:20px; display:grid; gap:16px; }
        .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:16px; }
        .card { background:var(--card); border:1px solid var(--line); border-radius:12px; padding:16px; }
        .card h2 { margin:0 0 12px; font-size:15px; }
        label { display:block; font-size:12px; color:var(--muted); margin:8px 0 4px; }
        input, select, textarea, button { width:100%; padding:8px 10px; border:1px solid var(--line); border-radius:8px; font:inherit; }
        button { background:var(--brand); color:#fff; border:none; cursor:pointer; margin-top:10px; }
        button.secondary { background:#fff; color:var(--text); border:1px solid var(--line); }
        button.danger { background:var(--danger); }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        th, td { text-align:left; padding:8px 6px; border-bottom:1px solid var(--line); vertical-align:top; }
        .msg { font-size:12px; color:var(--muted); min-height:18px; }
        .msg.ok { color:var(--ok); }
        .msg.err { color:var(--danger); }
        .row { display:flex; gap:8px; }
        .row > * { flex:1; }
    </style>
</head>
<body>
<header>
    <div>
        <h1>Calendar Booking</h1>
        <div class="meta">Store {{ $handle }} · TZ <span id="tzLabel">{{ $timezone }}</span></div>
    </div>
    <div class="meta">Shop #{{ $shopId }}</div>
</header>
<main>
    <div class="msg" id="globalMsg"></div>
    <div class="grid">
        <section class="card">
            <h2>1. Service</h2>
            <label>Name</label><input id="svcName" value="Haircut">
            <label>Duration (minutes)</label><input id="svcDuration" type="number" value="30">
            <label>Capacity</label><input id="svcCapacity" type="number" value="1">
            <button onclick="createService()">Create service</button>
            <div class="msg" id="svcMsg"></div>
            <div id="svcList"></div>
        </section>
        <section class="card">
            <h2>2. Location</h2>
            <label>Name</label><input id="locName" value="Main Store">
            <label>Address</label><input id="locAddress" value="">
            <button onclick="createLocation()">Create location</button>
            <div class="msg" id="locMsg"></div>
            <div id="locList"></div>
        </section>
        <section class="card">
            <h2>3. Staff</h2>
            <label>Name</label><input id="staffName" value="Alex">
            <label>Service IDs (comma)</label><input id="staffServices" placeholder="auto after create">
            <label>Location IDs (comma)</label><input id="staffLocations" placeholder="auto after create">
            <button onclick="createStaff()">Create staff</button>
            <div class="msg" id="staffMsg"></div>
            <div id="staffList"></div>
        </section>
        <section class="card">
            <h2>4. Weekly schedule</h2>
            <label>Staff ID</label><input id="schStaffId" type="number">
            <label>Location ID (optional)</label><input id="schLocId" type="number">
            <label>Weekday (0=Sun..6=Sat)</label><input id="schWeekday" type="number" value="1">
            <div class="row">
                <div><label>Start</label><input id="schStart" value="09:00:00"></div>
                <div><label>End</label><input id="schEnd" value="18:00:00"></div>
            </div>
            <button onclick="createSchedule()">Create schedule</button>
            <div class="msg" id="schMsg"></div>
        </section>
        <section class="card">
            <h2>5. Availability & book</h2>
            <label>Timezone</label>
            <div class="row">
                <input id="shopTz" value="{{ $timezone }}">
                <button class="secondary" onclick="saveTimezone()">Save TZ</button>
            </div>
            <button class="secondary" onclick="syncTimezone()">Sync TZ from SHOPLINE</button>
            <label>Sync product for Service ID</label>
            <div class="row">
                <input id="syncSvcId" type="number" placeholder="service id">
                <input id="syncPrice" placeholder="price e.g. 10.00">
            </div>
            <button class="secondary" onclick="syncProduct()">Create SHOPLINE product</button>
            <div class="msg" id="syncMsg"></div>
            <label>Service ID</label><input id="avServiceId" type="number">
            <label>Location ID</label><input id="avLocationId" type="number">
            <label>Date (shop timezone Y-m-d)</label><input id="avDate" placeholder="2026-08-13">
            <button onclick="loadAvailability()">Load slots</button>
            <div class="msg" id="avMsg"></div>
            <div id="slotList"></div>
        </section>
        <section class="card" style="grid-column:1/-1">
            <h2>Bookings</h2>
            <button class="secondary" onclick="loadBookings()">Refresh</button>
            <div id="bookingList"></div>
        </section>
    </div>
</main>
<script type="module">
    import Client, { shared } from 'https://esm.sh/@shoplineos/app-bridge@0.0.2';
    const appKey = @json($appKey);
    const fallbackHost = @json($host);
    const shopSession = @json($shopSession);
    try {
        const host = (typeof shared.getHost === 'function' ? shared.getHost() : '') || fallbackHost;
        Client.createApp({ appKey, host });
    } catch (e) { console.warn(e); }

    async function api(path, options = {}) {
        const res = await fetch(path, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + shopSession,
                ...(options.headers || {}),
            },
        });
        const json = await res.json();
        if (json.code !== 200) throw new Error(json.message || 'Request failed');
        return json.data;
    }
    function setMsg(id, text, ok) {
        const el = document.getElementById(id);
        el.className = 'msg ' + (ok ? 'ok' : (text ? 'err' : ''));
        el.textContent = text || '';
    }
    function ids(csv) { return String(csv||'').split(',').map(s=>s.trim()).filter(Boolean).map(Number); }

    window.createService = async () => {
        try {
            const row = await api('/shopline/api/services', { method:'POST', body: JSON.stringify({
                name: document.getElementById('svcName').value,
                duration_minutes: Number(document.getElementById('svcDuration').value),
                capacity: Number(document.getElementById('svcCapacity').value),
            })});
            document.getElementById('avServiceId').value = row.id;
            document.getElementById('staffServices').value = row.id;
            setMsg('svcMsg', 'Created #' + row.id, true);
            await refreshLists();
        } catch (e) { setMsg('svcMsg', e.message, false); }
    };
    window.createLocation = async () => {
        try {
            const row = await api('/shopline/api/locations', { method:'POST', body: JSON.stringify({
                name: document.getElementById('locName').value,
                address: document.getElementById('locAddress').value,
            })});
            document.getElementById('avLocationId').value = row.id;
            document.getElementById('staffLocations').value = row.id;
            setMsg('locMsg', 'Created #' + row.id, true);
            await refreshLists();
        } catch (e) { setMsg('locMsg', e.message, false); }
    };
    window.createStaff = async () => {
        try {
            const row = await api('/shopline/api/staff', { method:'POST', body: JSON.stringify({
                name: document.getElementById('staffName').value,
                service_ids: ids(document.getElementById('staffServices').value),
                location_ids: ids(document.getElementById('staffLocations').value),
            })});
            document.getElementById('schStaffId').value = row.id;
            setMsg('staffMsg', 'Created #' + row.id, true);
            await refreshLists();
        } catch (e) { setMsg('staffMsg', e.message, false); }
    };
    window.createSchedule = async () => {
        try {
            const body = {
                staff_id: Number(document.getElementById('schStaffId').value),
                weekday: Number(document.getElementById('schWeekday').value),
                start_time: document.getElementById('schStart').value,
                end_time: document.getElementById('schEnd').value,
            };
            const loc = document.getElementById('schLocId').value;
            if (loc) body.location_id = Number(loc);
            const row = await api('/shopline/api/schedules', { method:'POST', body: JSON.stringify(body) });
            setMsg('schMsg', 'Created schedule #' + row.id, true);
        } catch (e) { setMsg('schMsg', e.message, false); }
    };
    window.saveTimezone = async () => {
        try {
            const data = await api('/shopline/api/shop/timezone', { method:'PUT', body: JSON.stringify({ timezone: document.getElementById('shopTz').value })});
            document.getElementById('tzLabel').textContent = data.timezone;
            setMsg('avMsg', 'Timezone saved', true);
        } catch (e) { setMsg('avMsg', e.message, false); }
    };
    window.syncTimezone = async () => {
        try {
            const data = await api('/shopline/api/shop/sync-timezone', { method:'POST', body:'{}' });
            document.getElementById('shopTz').value = data.timezone;
            document.getElementById('tzLabel').textContent = data.timezone;
            setMsg('syncMsg', 'Synced TZ ' + data.timezone, true);
        } catch (e) { setMsg('syncMsg', e.message, false); }
    };
    window.syncProduct = async () => {
        try {
            const id = Number(document.getElementById('syncSvcId').value || document.getElementById('avServiceId').value);
            const price = document.getElementById('syncPrice').value;
            const data = await api('/shopline/api/services/' + id + '/sync-product', {
                method:'POST',
                body: JSON.stringify(price ? { price } : {}),
            });
            setMsg('syncMsg', 'Product ' + data.shopline_product_id + ' for service #' + data.service_id, true);
            await refreshLists();
        } catch (e) { setMsg('syncMsg', e.message, false); }
    };
    window.loadAvailability = async () => {
        try {
            const q = new URLSearchParams({
                service_id: document.getElementById('avServiceId').value,
                location_id: document.getElementById('avLocationId').value,
                date: document.getElementById('avDate').value,
            });
            const data = await api('/shopline/api/availability?' + q.toString());
            const box = document.getElementById('slotList');
            if (!data.list.length) { box.innerHTML = '<div class="msg">No slots</div>'; return; }
            box.innerHTML = '<table><thead><tr><th>Local</th><th>Staff</th><th>Left</th><th></th></tr></thead><tbody>' +
                data.list.map(s => `<tr>
                    <td>${s.start_at_local}<br><span class="meta">${s.start_at}</span></td>
                    <td>${s.staff_name} (#${s.staff_id})</td>
                    <td>${s.remaining}/${s.capacity}</td>
                    <td><button onclick="bookSlot(${s.slot_id})">Book</button></td>
                </tr>`).join('') + '</tbody></table>';
            setMsg('avMsg', data.list.length + ' slots', true);
        } catch (e) { setMsg('avMsg', e.message, false); }
    };
    window.bookSlot = async (slotId) => {
        try {
            const lock = await api('/shopline/api/slot-locks', { method:'POST', body: JSON.stringify({ slot_id: slotId, customer_key: 'embed-demo' })});
            const booking = await api('/shopline/api/bookings', { method:'POST', body: JSON.stringify({
                slot_id: slotId,
                lock_token: lock.lock_token,
                customer_name: 'Demo Customer',
                customer_email: 'demo@example.com',
                idempotency_key: 'embed-' + slotId + '-' + Date.now(),
            })});
            setMsg('avMsg', 'Booked ' + booking.booking_no, true);
            await loadBookings();
            await loadAvailability();
        } catch (e) { setMsg('avMsg', e.message, false); }
    };
    window.loadBookings = async () => {
        const data = await api('/shopline/api/bookings');
        document.getElementById('bookingList').innerHTML = '<table><thead><tr><th>No</th><th>Status</th><th>Start(UTC)</th><th></th></tr></thead><tbody>' +
            data.list.map(b => `<tr>
                <td>${b.booking_no}</td><td>${b.status}</td><td>${b.start_at}</td>
                <td>${['pending','confirmed'].includes(b.status) ? `<button class="danger" onclick="cancelBooking(${b.id})">Cancel</button>` : ''}</td>
            </tr>`).join('') + '</tbody></table>';
    };
    window.cancelBooking = async (id) => {
        await api('/shopline/api/bookings/' + id + '/cancel', { method:'POST', body:'{}' });
        await loadBookings();
    };
    async function refreshLists() {
        const [services, locations, staff] = await Promise.all([
            api('/shopline/api/services'),
            api('/shopline/api/locations'),
            api('/shopline/api/staff'),
        ]);
        document.getElementById('svcList').innerHTML = services.list.map(s => `#${s.id} ${s.name} (${s.duration_minutes}m)`).join('<br>');
        document.getElementById('locList').innerHTML = locations.list.map(s => `#${s.id} ${s.name}`).join('<br>');
        document.getElementById('staffList').innerHTML = staff.list.map(s => `#${s.id} ${s.name}`).join('<br>');
        if (services.list[0]) document.getElementById('avServiceId').value ||= services.list[0].id;
        if (locations.list[0]) document.getElementById('avLocationId').value ||= locations.list[0].id;
        if (staff.list[0]) document.getElementById('schStaffId').value ||= staff.list[0].id;
    }
    const today = new Date();
    document.getElementById('avDate').value = today.toISOString().slice(0,10);
    refreshLists().then(loadBookings).catch(e => setMsg('globalMsg', e.message, false));
</script>
</body>
</html>
