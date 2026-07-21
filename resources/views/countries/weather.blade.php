<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Peta Cuaca Global</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        html,body{height:100%;margin:0;background:#e9eef5;font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif}.app{height:100%;display:flex;flex-direction:column}.topbar{height:68px;background:#0d1526;color:#fff;display:flex;align-items:center;justify-content:space-between;padding:0 24px;box-shadow:0 3px 15px #0d152644;z-index:1100}.map-shell{position:relative;flex:1;min-height:560px}#map{position:absolute;inset:0;z-index:1}.control-panel{position:absolute;z-index:1000;top:18px;left:18px;width:min(390px,calc(100% - 36px));background:rgba(255,255,255,.96);backdrop-filter:blur(12px);border-radius:18px;box-shadow:0 12px 35px rgba(15,23,42,.22);overflow:hidden}.panel-head{padding:19px 20px;background:linear-gradient(125deg,#17213b,#304e89);color:#fff}.panel-body{padding:17px 20px}.legend-item{display:flex;align-items:center;gap:9px;font-size:.82rem;padding:6px 0;color:#49566b}.legend-dot{width:13px;height:13px;border-radius:50%;border:2px solid #fff;box-shadow:0 1px 5px #33415566;flex:none}.weather-stat{padding:10px;border-radius:12px;text-align:center}.weather-stat b{display:block;font-size:1.15rem}.weather-stat small{font-size:.68rem;color:#718096}.filter-btn{font-size:.73rem;border-radius:20px}.filter-btn.active{color:#fff!important}.map-legend{position:absolute;z-index:1000;right:18px;bottom:30px;background:rgba(255,255,255,.96);border-radius:14px;padding:13px 16px;box-shadow:0 8px 25px rgba(15,23,42,.18);font-size:.78rem}.weather-marker{border-radius:50%;border:2px solid #fff;box-shadow:0 2px 8px #1118;transition:.2s}.weather-marker:hover{transform:scale(1.3)}.leaflet-popup-content{margin:15px;min-width:230px}.popup-flag{width:42px;height:30px;object-fit:cover;border-radius:6px;border:1px solid #ddd}.weather-grid{display:grid;grid-template-columns:1fr 1fr;gap:7px;margin-top:11px}.weather-cell{background:#f2f5f9;border-radius:8px;padding:8px}.weather-cell small{display:block;color:#748196;font-size:.68rem}.weather-cell b{font-size:.88rem}.temperature-scale{position:absolute;z-index:1000;right:18px;top:18px;background:white;border-radius:12px;padding:10px 13px;box-shadow:0 8px 25px rgba(15,23,42,.16);font-size:.72rem}.temp-gradient{height:8px;width:150px;border-radius:8px;background:linear-gradient(90deg,#2563eb,#20b86b,#f4b740,#ef4c5c);margin:5px 0}.search-results{max-height:170px;overflow:auto;display:none}.search-result{cursor:pointer;font-size:.82rem}.search-result:hover{background:#f1f5f9}.mobile-toggle{display:none}.panel-collapsed .panel-body{display:none}
        @media(max-width:767px){.control-panel{top:10px;left:10px;width:calc(100% - 20px)}.temperature-scale,.map-legend{display:none}.mobile-toggle{display:inline-block}.topbar{height:60px;padding:0 14px}.panel-head{padding:14px 16px}.panel-body{padding:14px 16px}}
    </style>
    @include('components.dark-theme')
</head>
<body>
<div class="app">
    <header class="topbar">
        <a class="text-white text-decoration-none fw-bold" href="{{ route('countries.index') }}"><i class="fa-solid fa-cloud-sun-rain text-warning me-2"></i>Peta Cuaca Global</a>
        <div class="d-flex align-items-center gap-3"><span class="small d-none d-sm-inline">Temperatur Â· Hujan Â· Angin Â· Badai</span><a href="{{ route('countries.index') }}" class="btn btn-outline-light btn-sm"><i class="fa-solid fa-arrow-left"></i></a></div>
    </header>

    <div class="map-shell">
        <div id="map"></div>

        <section class="control-panel" id="controlPanel">
            <div class="panel-head d-flex justify-content-between align-items-start"><div><div class="small text-white-50 mb-1">LIVE WEATHER MONITOR</div><h1 class="h5 fw-bold mb-1">Kondisi Cuaca Dunia</h1><div class="small text-white-50" id="updatedText">Data cuaca terbaru seluruh negara</div></div><button class="btn btn-sm btn-outline-light mobile-toggle" id="panelToggle"><i class="fa-solid fa-chevron-up"></i></button></div>
            <div class="panel-body">
                <div class="input-group mb-2"><span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass"></i></span><input id="weatherSearch" class="form-control border-start-0" placeholder="Cari negara..."></div>
                <div class="list-group search-results mb-3" id="searchResults"></div>
                <div class="row g-2 mb-3"><div class="col-3"><div class="weather-stat bg-success-subtle"><b id="normalCount">0</b><small>Normal</small></div></div><div class="col-3"><div class="weather-stat bg-primary-subtle"><b id="rainCount">0</b><small>Hujan</small></div></div><div class="col-3"><div class="weather-stat bg-danger-subtle"><b id="windCount">0</b><small>Angin</small></div></div><div class="col-3"><div class="weather-stat bg-purple-subtle" style="background:#eee9ff"><b id="stormCount">0</b><small>Badai</small></div></div></div>
                <div class="fw-bold small mb-1">Keterangan warna marker</div>
                <div class="legend-item"><span class="legend-dot" style="background:#16a34a"></span><span><b>Hijau â€” Normal/Cerah</b><br>Hujan 0 mm dan angin di bawah 35 km/j</span></div>
                <div class="legend-item"><span class="legend-dot" style="background:#2563eb"></span><span><b>Biru â€” Hujan</b><br>Terdapat curah hujan di lokasi</span></div>
                <div class="legend-item"><span class="legend-dot" style="background:#dc2626"></span><span><b>Merah â€” Angin Kencang</b><br>Kecepatan angin lebih dari 35 km/j</span></div>
                <div class="legend-item"><span class="legend-dot" style="background:#7c3aed"></span><span><b>Ungu â€” Badai Petir</b><br>Kode cuaca Open-Meteo 95 atau lebih</span></div>
                <div class="d-flex flex-wrap gap-1 mt-2" id="filters"><button class="btn btn-dark filter-btn active" data-filter="all">Semua</button><button class="btn btn-outline-success filter-btn" data-filter="normal">Normal</button><button class="btn btn-outline-primary filter-btn" data-filter="rain">Hujan</button><button class="btn btn-outline-danger filter-btn" data-filter="wind">Angin</button><button class="btn btn-outline-secondary filter-btn" data-filter="storm">Badai</button></div>
            </div>
        </section>

        <aside class="temperature-scale"><b>Skala Temperatur</b><div class="temp-gradient"></div><div class="d-flex justify-content-between"><span>&lt; 10Â°C</span><span>20Â°C</span><span>&gt; 35Â°C</span></div></aside>
        <aside class="map-legend"><div class="fw-bold mb-1"><i class="fa-solid fa-circle-info text-primary me-1"></i>Cara menggunakan</div><div>Klik marker untuk detail lengkap.<br>Gunakan pencarian untuk menuju negara.</div></aside>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map=L.map('map',{worldCopyJump:false,minZoom:2,maxBounds:[[-85,-180],[85,180]],maxBoundsViscosity:1}).setView([15,10],2);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap',noWrap:true,maxZoom:18}).addTo(map);
    const countries=@json($countries);
    const markerData=[];
    const counts={normal:0,rain:0,wind:0,storm:0};
    const colorFor=type=>({normal:'#16a34a',rain:'#2563eb',wind:'#dc2626',storm:'#7c3aed'}[type]);
    const classify=weather=>{if((weather?.weather_code||0)>=95)return'storm';if((weather?.wind_speed||0)>35)return'wind';if((weather?.precipitation||0)>0)return'rain';return'normal'};
    const escapeHtml=value=>String(value??'').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));

    countries.forEach(country=>{
        const weather=country.weather_forecasts?.[0];
        if(country.latitude===null||country.longitude===null)return;
        const type=classify(weather);counts[type]++;
        const icon=L.divIcon({className:'',html:`<div class="weather-marker" style="background:${colorFor(type)};width:18px;height:18px"></div>`,iconSize:[18,18],iconAnchor:[9,9]});
        const code=String(country.country_code||'').toLowerCase();
        const marker=L.marker([Number(country.latitude),Number(country.longitude)],{icon}).addTo(map).bindPopup(`<div class="d-flex align-items-center gap-2"><img class="popup-flag" src="https://flagcdn.com/w80/${code}.png" alt=""><div><small class="text-muted">${escapeHtml(country.country_code)}</small><h6 class="fw-bold mb-0">${escapeHtml(country.country_name)}</h6></div></div><div class="weather-grid"><div class="weather-cell"><small>Temperatur</small><b>${weather?.temperature??'-'} Â°C</b></div><div class="weather-cell"><small>Kondisi</small><b>${escapeHtml(weather?.condition_status??'Belum ada')}</b></div><div class="weather-cell"><small>Curah Hujan</small><b>${weather?.precipitation??'-'} mm</b></div><div class="weather-cell"><small>Kecepatan Angin</small><b>${weather?.wind_speed??'-'} km/j</b></div></div><a class="btn btn-primary btn-sm w-100 mt-3" href="/countries/${country.id}">Buka Dashboard Negara</a>`);
        markerData.push({country,weather,type,marker});
    });
    Object.keys(counts).forEach(type=>document.getElementById(type+'Count').textContent=counts[type]);

    document.getElementById('weatherSearch').addEventListener('input',function(){const q=this.value.toLowerCase().trim(),box=document.getElementById('searchResults');box.innerHTML='';if(!q){box.style.display='none';return}const found=markerData.filter(x=>(x.country.country_name+' '+x.country.country_code).toLowerCase().includes(q)).slice(0,8);found.forEach(item=>{const button=document.createElement('button');button.className='list-group-item list-group-item-action search-result';button.innerHTML=`<b>${escapeHtml(item.country.country_name)}</b> <span class="text-muted">${escapeHtml(item.country.country_code)} Â· ${item.weather?.temperature??'-'}Â°C</span>`;button.onclick=()=>{map.setView(item.marker.getLatLng(),6);item.marker.openPopup();box.style.display='none'};box.appendChild(button)});if(!found.length)box.innerHTML='<div class="list-group-item text-muted small">Negara tidak ditemukan</div>';box.style.display='block'});
    document.querySelectorAll('.filter-btn').forEach(button=>button.addEventListener('click',function(){document.querySelectorAll('.filter-btn').forEach(x=>x.classList.remove('active'));this.classList.add('active');const filter=this.dataset.filter;markerData.forEach(item=>{const visible=filter==='all'||item.type===filter;if(visible&&!map.hasLayer(item.marker))item.marker.addTo(map);if(!visible&&map.hasLayer(item.marker))map.removeLayer(item.marker)})}));
    document.getElementById('panelToggle').addEventListener('click',function(){document.getElementById('controlPanel').classList.toggle('panel-collapsed');this.querySelector('i').classList.toggle('fa-chevron-down')});
</script>
</body>
</html>
