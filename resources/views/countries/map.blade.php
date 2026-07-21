<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Global - SupplyChain RiskIntel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
    <style>
        html, body { height: 100%; margin: 0; background: #eef2f7; }
        .app { height: 100%; display: flex; flex-direction: column; }
        .navbar { background: linear-gradient(90deg, #0f172a, #1e293b); }
        .map-shell { position: relative; flex: 1; min-height: 560px; }
        #map { position: absolute; inset: 0; z-index: 1; }
        .map-panel { position: absolute; z-index: 1000; top: 18px; left: 18px; width: min(390px, calc(100% - 36px)); border: 0; border-radius: 1rem; box-shadow: 0 .6rem 2rem rgba(15,23,42,.22); }
        .legend { position: absolute; z-index: 1000; right: 18px; bottom: 28px; background: white; padding: .8rem 1rem; border-radius: .8rem; box-shadow: 0 .4rem 1.2rem rgba(15,23,42,.18); font-size: .82rem; }
        .legend-dot { display: inline-block; width: 11px; height: 11px; border-radius: 50%; margin-right: 5px; }
        .country-pin { width: 22px; height: 22px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid white; box-shadow: 0 2px 6px #334155; }
        .country-pin span { display: block; transform: rotate(45deg); color: white; font-size: 9px; text-align: center; line-height: 18px; font-weight: 700; }
        .port-pin { width: 12px; height: 12px; border-radius: 50%; background: #2563eb; border: 2px solid white; box-shadow: 0 1px 4px #1e3a8a; }
        .loading-cover { position: absolute; inset: 0; z-index: 1100; background: rgba(238,242,247,.88); display: grid; place-items: center; }
        @media(max-width: 576px) { .map-panel { top: 10px; left: 10px; width: calc(100% - 20px); } .legend { display:none; } }
    </style>
    @include('components.dark-theme')
</head>
<body>
<div class="app">
    <nav class="navbar navbar-dark shadow-sm">
        <div class="container-fluid px-3 px-lg-4 py-2">
            <a class="navbar-brand fw-bold" href="{{ route('countries.index') }}"><i class="fa-solid fa-earth-asia text-warning me-2"></i>Global Supply Chain Map</a>
            <a href="{{ route('countries.index') }}" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-arrow-left me-1"></i>Master Negara</a>
        </div>
    </nav>

    <div class="map-shell">
        <div id="map"></div>
        <div id="loading" class="loading-cover"><div class="text-center"><div class="spinner-border text-primary mb-3"></div><div class="fw-semibold">Memuat seluruh marker negara dan pelabuhan...</div></div></div>

        <section class="card map-panel">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div><h1 class="h5 fw-bold mb-1">Monitoring Global</h1><small class="text-muted">Klik marker untuk melihat detail.</small></div>
                    <span class="badge text-bg-success" id="loadStatus">Memuat</span>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input id="mapSearch" class="form-control" placeholder="Cari negara atau pelabuhan...">
                    <button id="searchButton" class="btn btn-primary">Cari</button>
                </div>
                <div id="searchResult" class="list-group mb-3 d-none" style="max-height:180px;overflow:auto"></div>
                <div class="row g-2 text-center">
                    <div class="col-6"><div class="bg-primary-subtle rounded-3 p-2"><div class="fs-5 fw-bold text-primary" id="countryTotal">{{ number_format($countryCount) }}</div><small>Marker Negara</small></div></div>
                    <div class="col-6"><div class="bg-info-subtle rounded-3 p-2"><div class="fs-5 fw-bold text-info-emphasis" id="portTotal">{{ number_format($portCount) }}</div><small>Marker Pelabuhan</small></div></div>
                </div>
            </div>
        </section>

        <aside class="legend">
            <div class="fw-bold mb-2">Legenda</div>
            <div><span class="legend-dot" style="background:#16a34a"></span>Negara risiko rendah</div>
            <div><span class="legend-dot" style="background:#f59e0b"></span>Negara risiko sedang</div>
            <div><span class="legend-dot" style="background:#dc2626"></span>Negara risiko tinggi</div>
            <div><span class="legend-dot" style="background:#2563eb"></span>Pelabuhan</div>
        </aside>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
    const map = L.map('map', { worldCopyJump: true, minZoom: 2 }).setView([12, 15], 2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: '&copy; OpenStreetMap contributors' }).addTo(map);

    const countriesLayer = L.layerGroup().addTo(map);
    const portsLayer = L.markerClusterGroup({ chunkedLoading: true, chunkInterval: 120, chunkDelay: 30, maxClusterRadius: 45 }).addTo(map);
    L.control.layers(null, { 'Negara': countriesLayer, 'Pelabuhan': portsLayer }, { collapsed: false, position: 'bottomleft' }).addTo(map);

    const locations = [];
    const escapeHtml = value => String(value ?? '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[char]));
    const countryIcon = (level, code) => {
        const color = level === 'High Risk' ? '#dc2626' : (level === 'Medium Risk' ? '#f59e0b' : '#16a34a');
        return L.divIcon({ className: '', html: `<div class="country-pin" style="background:${color}"><span>${escapeHtml(code)}</span></div>`, iconSize: [22,22], iconAnchor: [11,22] });
    };
    const portIcon = L.divIcon({ className: '', html: '<div class="port-pin"></div>', iconSize: [12,12], iconAnchor: [6,6] });

    Promise.all([
        fetch('{{ url('/api/countries') }}').then(response => response.json()),
        fetch('{{ url('/api/ports') }}').then(response => response.json())
    ]).then(([countryResponse, portResponse]) => {
        const countries = countryResponse.data || [];
        const ports = portResponse.data || [];

        countries.forEach(country => {
            if (country.latitude === null || country.longitude === null) return;
            const risk = country.risk_score || {};
            const marker = L.marker([Number(country.latitude), Number(country.longitude)], { icon: countryIcon(risk.risk_level, country.country_code) })
                .bindPopup(`<div style="min-width:210px"><small class="text-muted">NEGARA</small><h6 class="fw-bold mt-1">${escapeHtml(country.country_name)}</h6><div>Kode ISO: <b>${escapeHtml(country.country_code)}</b></div><div>Skor risiko: <b>${escapeHtml(risk.total_risk_score ?? 'Belum dihitung')}</b></div><div class="mb-2">Level: <b>${escapeHtml(risk.risk_level ?? '-')}</b></div><a class="btn btn-primary btn-sm" href="{{ url('/countries') }}/${country.id}">Buka dashboard</a></div>`);
            marker.addTo(countriesLayer);
            locations.push({ name: country.country_name, detail: `Negara Â· ${country.country_code}`, marker, layer: countriesLayer });
        });

        ports.forEach(port => {
            if (port.latitude === null || port.longitude === null) return;
            const marker = L.marker([Number(port.latitude), Number(port.longitude)], { icon: portIcon })
                .bindPopup(`<div style="min-width:190px"><small class="text-primary">PELABUHAN</small><h6 class="fw-bold mt-1">${escapeHtml(port.port_name)}</h6><div><i class="fa-solid fa-flag me-1"></i>${escapeHtml(port.country_name)}</div><small class="text-muted">${escapeHtml(port.latitude)}, ${escapeHtml(port.longitude)}</small></div>`);
            portsLayer.addLayer(marker);
            locations.push({ name: port.port_name, detail: `Pelabuhan Â· ${port.country_name}`, marker, layer: portsLayer });
        });

        document.getElementById('countryTotal').textContent = countries.length.toLocaleString('id-ID');
        document.getElementById('portTotal').textContent = ports.length.toLocaleString('id-ID');
        document.getElementById('loadStatus').textContent = 'Siap';
        document.getElementById('loading').remove();
    }).catch(error => {
        document.getElementById('loading').innerHTML = '<div class="alert alert-danger">Data peta gagal dimuat. Silakan muat ulang halaman.</div>';
        document.getElementById('loadStatus').className = 'badge text-bg-danger';
        document.getElementById('loadStatus').textContent = 'Gagal';
        console.error(error);
    });

    function searchLocations() {
        const keyword = document.getElementById('mapSearch').value.toLowerCase().trim();
        const box = document.getElementById('searchResult');
        box.innerHTML = '';
        if (!keyword) { box.classList.add('d-none'); return; }
        const results = locations.filter(item => `${item.name} ${item.detail}`.toLowerCase().includes(keyword)).slice(0, 20);
        results.forEach(item => {
            const button = document.createElement('button');
            button.type = 'button'; button.className = 'list-group-item list-group-item-action py-2';
            button.innerHTML = `<div class="fw-semibold">${escapeHtml(item.name)}</div><small class="text-muted">${escapeHtml(item.detail)}</small>`;
            button.onclick = () => {
                if (item.layer === portsLayer) portsLayer.zoomToShowLayer(item.marker, () => item.marker.openPopup());
                else { map.setView(item.marker.getLatLng(), 6); item.marker.openPopup(); }
                box.classList.add('d-none');
            };
            box.appendChild(button);
        });
        if (!results.length) box.innerHTML = '<div class="list-group-item text-muted">Lokasi tidak ditemukan.</div>';
        box.classList.remove('d-none');
    }
    document.getElementById('searchButton').addEventListener('click', searchLocations);
    document.getElementById('mapSearch').addEventListener('keydown', event => { if (event.key === 'Enter') searchLocations(); });
</script>
</body>
</html>
