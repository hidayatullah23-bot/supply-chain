<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>News Intelligence Â· {{ $countryData->country_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --navy:#0b1220; --navy-2:#111c31; --ink:#162033; --muted:#738096; --surface:#fff; --page:#f1f5f9; --accent:#6d5dfc; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--page); color:var(--ink); font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif; }
        .app-shell { min-height:100vh; display:flex; }
        .sidebar { width:270px; background:linear-gradient(180deg,var(--navy),var(--navy-2)); color:#dce5f4; padding:24px 18px; position:fixed; inset:0 auto 0 0; z-index:1040; overflow-y:auto; }
        .brand { display:flex; align-items:center; gap:11px; color:#fff; text-decoration:none; font-weight:800; font-size:1.05rem; padding:0 8px 26px; }
        .brand-icon { width:38px; height:38px; display:grid; place-items:center; border-radius:11px; background:linear-gradient(135deg,#ffd84d,#ff9d00); color:#101827; }
        .nav-label { color:#71819c; font-size:.68rem; font-weight:800; letter-spacing:.13em; padding:18px 12px 8px; }
        .side-link { display:flex; align-items:center; gap:12px; padding:11px 13px; color:#aebbd0; text-decoration:none; border-radius:11px; margin:3px 0; font-size:.9rem; }
        .side-link:hover,.side-link.active { color:#fff; background:rgba(109,93,252,.2); }
        .side-link.active { box-shadow:inset 3px 0 #8b7fff; }
        .main { flex:1; min-width:0; margin-left:270px; }
        .topbar { height:72px; background:rgba(255,255,255,.9); backdrop-filter:blur(12px); border-bottom:1px solid #e7ebf1; display:flex; align-items:center; justify-content:space-between; padding:0 32px; position:sticky; top:0; z-index:1020; }
        .content { padding:28px 32px 45px; max-width:1600px; margin:auto; }
        .hero { border-radius:22px; padding:28px 30px; color:#fff; background:radial-gradient(circle at 85% 20%,rgba(88,214,255,.28),transparent 28%),linear-gradient(125deg,#141d36,#263768 60%,#6457d9); box-shadow:0 18px 45px rgba(31,42,78,.2); }
        .hero-select { background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.25); color:#fff; }
        .hero-select option { color:#182033; }
        .metric-card,.panel,.news-card { background:var(--surface); border:0; border-radius:17px; box-shadow:0 7px 28px rgba(31,44,68,.07); }
        .metric-card { padding:18px; display:flex; align-items:center; gap:14px; height:100%; }
        .metric-icon { width:46px; height:46px; border-radius:14px; display:grid; place-items:center; font-size:1.1rem; }
        .metric-value { font-size:1.55rem; font-weight:800; line-height:1; }
        .metric-label { color:var(--muted); font-size:.78rem; margin-top:5px; }
        .panel { padding:22px; }
        .chart-wrap { height:270px; position:relative; }
        .news-card { overflow:hidden; transition:.2s ease; height:100%; }
        .news-card:hover { transform:translateY(-3px); box-shadow:0 14px 38px rgba(31,44,68,.13); }
        .news-image-wrap { position:relative; height:210px; overflow:hidden; background:#dce4ee; }
        .news-image { width:100%; height:100%; object-fit:cover; transition:transform .35s ease; }
        .news-card:hover .news-image { transform:scale(1.035); }
        .sentiment-badge { position:absolute; left:15px; top:15px; backdrop-filter:blur(8px); }
        .news-body { padding:19px; }
        .news-title { font-size:1rem; line-height:1.38; font-weight:750; min-height:2.76em; }
        .news-description { color:#68758a; font-size:.86rem; line-height:1.58; min-height:4.1em; }
        .search-box { width:min(360px,100%); }
        .mobile-menu { display:none; }
        .empty-filter { display:none; }
        @media(max-width:991px){ .sidebar{transform:translateX(-100%);transition:.25s}.sidebar.open{transform:none}.main{margin-left:0}.mobile-menu{display:inline-flex}.content{padding:20px}.topbar{padding:0 20px}.hero{padding:24px} }
        @media(max-width:575px){ .content{padding:14px}.hero{border-radius:16px}.topbar{height:64px}.search-box{width:100%} }
    </style>
    @include('components.dark-theme')
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="{{ route('countries.index') }}"><span class="brand-icon"><i class="fa-solid fa-boxes-stacked"></i></span><span>SupplyChain<br><small class="fw-normal text-warning">Risk Intelligence</small></span></a>
        <div class="nav-label">MONITORING</div>
        <a class="side-link" href="{{ route('countries.index') }}"><i class="fa-solid fa-earth-asia"></i>Master Negara</a>
        <a class="side-link" href="{{ route('global.map') }}"><i class="fa-solid fa-map-location-dot"></i>Peta Global</a>
        <a class="side-link" href="{{ route('weather.map') }}"><i class="fa-solid fa-cloud-bolt"></i>Cuaca Global</a>
        <a class="side-link" href="{{ route('watchlists.index') }}"><i class="fa-solid fa-star"></i>Watchlist</a>
        <div class="nav-label">INTELLIGENCE</div>
        <a class="side-link active" href="#"><i class="fa-solid fa-newspaper"></i>News Intelligence</a>
        <a class="side-link" href="{{ route('countries.compare') }}"><i class="fa-solid fa-code-compare"></i>Perbandingan</a>
        <a class="side-link" href="{{ route('risk-scores.index') }}"><i class="fa-solid fa-chart-line"></i>Risk Scores</a>
        @if(auth()->user()?->isAdmin())
            <div class="nav-label">SYSTEM</div>
            <a class="side-link" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-shield-halved"></i>Admin Panel</a>
        @endif
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="d-flex align-items-center gap-3"><button class="btn btn-light mobile-menu" id="menuButton"><i class="fa-solid fa-bars"></i></button><div><div class="fw-bold">News Intelligence</div><small class="text-muted">Real-time global supply chain monitoring</small></div></div>
            <div class="d-flex align-items-center gap-2"><span class="d-none d-md-inline text-muted small">{{ auth()->user()?->name ?? 'Guest' }}</span><span class="rounded-circle bg-dark text-white d-grid align-items-center text-center fw-bold" style="width:38px;height:38px">{{ strtoupper(substr(auth()->user()?->name ?? 'G',0,1)) }}</span></div>
        </header>

        <main class="content">
            <section class="hero mb-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7"><span class="badge rounded-pill bg-warning text-dark mb-3"><i class="fa-solid fa-satellite-dish me-1"></i>LIVE INTELLIGENCE</span><div class="d-flex align-items-center gap-3 mb-2"><x-country-flag :country="$countryData" :size="58"/><h1 class="display-6 fw-bold mb-0">Berita Rantai Pasok {{ $countryData->country_name }}</h1></div><p class="text-white-50 mb-0">Pantau berita perdagangan, logistik, ekonomi, dan geopolitik dengan analisis sentimen otomatis.</p></div>
                    <div class="col-lg-5"><label class="small text-white-50 mb-2">Ganti negara yang dipantau</label><select id="countrySelector" class="form-select hero-select"><option value="">Pilih negara...</option>@foreach($countryOptions as $country)<option value="{{ $country->id }}" @selected($country->id===$countryData->id)>{{ $country->country_name }} ({{ $country->country_code }})</option>@endforeach</select></div>
                </div>
            </section>

            <section class="row g-3 mb-4">
                <div class="col-6 col-xl-3"><div class="metric-card"><span class="metric-icon bg-primary-subtle text-primary"><i class="fa-regular fa-newspaper"></i></span><div><div class="metric-value">{{ $newsList->count() }}</div><div class="metric-label">Total Artikel</div></div></div></div>
                <div class="col-6 col-xl-3"><div class="metric-card"><span class="metric-icon bg-success-subtle text-success"><i class="fa-solid fa-arrow-trend-up"></i></span><div><div class="metric-value">{{ $sentimentSummary['Positive'] }}%</div><div class="metric-label">Sentimen Positif</div></div></div></div>
                <div class="col-6 col-xl-3"><div class="metric-card"><span class="metric-icon bg-secondary-subtle text-secondary"><i class="fa-solid fa-minus"></i></span><div><div class="metric-value">{{ $sentimentSummary['Neutral'] }}%</div><div class="metric-label">Sentimen Netral</div></div></div></div>
                <div class="col-6 col-xl-3"><div class="metric-card"><span class="metric-icon bg-danger-subtle text-danger"><i class="fa-solid fa-triangle-exclamation"></i></span><div><div class="metric-value">{{ $sentimentSummary['Negative'] }}%</div><div class="metric-label">Sentimen Negatif</div></div></div></div>
            </section>

            <section class="row g-4 mb-4">
                <div class="col-xl-4"><div class="panel h-100"><div class="d-flex justify-content-between align-items-start"><div><h2 class="h5 fw-bold mb-1">Distribusi Sentimen</h2><p class="small text-muted">Komposisi berita saat ini</p></div><span class="badge text-bg-light border">Lexicon AI</span></div><div class="chart-wrap"><canvas id="sentimentChart"></canvas></div></div></div>
                <div class="col-xl-8"><div class="panel h-100"><h2 class="h5 fw-bold">Insight Keputusan</h2><p class="text-muted small">Ringkasan cepat berdasarkan sentimen berita {{ $countryData->country_name }}.</p><div class="row g-3 mt-1"><div class="col-md-4"><div class="p-3 rounded-4 bg-success-subtle h-100"><i class="fa-solid fa-circle-check text-success mb-3"></i><div class="fw-bold">Peluang</div><small>{{ $sentimentSummary['Positive'] }}% berita memberi sinyal positif untuk perdagangan.</small></div></div><div class="col-md-4"><div class="p-3 rounded-4 bg-warning-subtle h-100"><i class="fa-solid fa-eye text-warning mb-3"></i><div class="fw-bold">Perlu Dipantau</div><small>{{ $sentimentSummary['Neutral'] }}% berita belum menunjukkan arah risiko kuat.</small></div></div><div class="col-md-4"><div class="p-3 rounded-4 bg-danger-subtle h-100"><i class="fa-solid fa-shield-halved text-danger mb-3"></i><div class="fw-bold">Ancaman</div><small>{{ $sentimentSummary['Negative'] }}% berita memerlukan mitigasi risiko.</small></div></div></div></div></div>
            </section>

            <section>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3"><div><h2 class="h4 fw-bold mb-1">Berita Terkini</h2><p class="text-muted small mb-0">Diperbarui dari GNews dan dianalisis otomatis</p></div><div class="input-group search-box"><span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span><input id="newsSearch" class="form-control border-start-0" placeholder="Cari judul atau isi berita..."></div></div>
                <div class="row g-4" id="newsGrid">
                    @forelse($newsList as $news)
                        @php
                            $fallbackImages=['https://images.unsplash.com/photo-1494412574643-ff11b0a5c1c3?auto=format&fit=crop&w=900&q=80','https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=900&q=80','https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=900&q=80','https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=900&q=80'];
                            $fallback=$fallbackImages[$news->id % count($fallbackImages)]; $image=$news->image_url ?: $fallback;
                            $color=$news->sentiment_status==='Positive'?'success':($news->sentiment_status==='Negative'?'danger':'secondary');
                        @endphp
                        <div class="col-md-6 col-xxl-4 news-item" data-search="{{ strtolower($news->title.' '.$news->description) }}" data-sentiment="{{ $news->sentiment_status }}"><article class="news-card"><div class="news-image-wrap"><img src="{{ $image }}" class="news-image" alt="{{ $news->title }}" loading="lazy" onerror="this.onerror=null;this.src='{{ $fallback }}'"><span class="badge text-bg-{{ $color }} sentiment-badge">{{ $news->sentiment_status }}</span></div><div class="news-body"><div class="d-flex justify-content-between text-muted small mb-2"><span><i class="fa-regular fa-clock me-1"></i>{{ $news->created_at->diffForHumans() }}</span><span>+{{ $news->sentiment_score_positive }} / -{{ $news->sentiment_score_negative }}</span></div><h3 class="news-title">{{ $news->title }}</h3><p class="news-description">{{ \Illuminate\Support\Str::limit($news->description,180) }}</p><a href="{{ $news->source_url }}" target="_blank" rel="noopener" class="btn btn-dark btn-sm">Baca selengkapnya <i class="fa-solid fa-arrow-up-right-from-square ms-1"></i></a></div></article></div>
                    @empty
                        <div class="col-12"><div class="panel text-center py-5"><i class="fa-regular fa-newspaper fa-3x text-muted mb-3"></i><h3 class="h5">Belum ada berita</h3><p class="text-muted mb-0">Pastikan GNEWS_API_KEY sudah dikonfigurasi.</p></div></div>
                    @endforelse
                </div>
                <div class="panel empty-filter text-center mt-3" id="emptyFilter">Berita yang dicari tidak ditemukan.</div>
            </section>
        </main>
    </div>
</div>

<script>
    new Chart(document.getElementById('sentimentChart'),{type:'doughnut',data:{labels:['Positive','Neutral','Negative'],datasets:[{data:@json($sentimentSummary->values()),backgroundColor:['#21b36b','#708199','#ef4c5c'],borderWidth:0,hoverOffset:8}]},options:{responsive:true,maintainAspectRatio:false,cutout:'70%',plugins:{legend:{position:'bottom',labels:{usePointStyle:true,padding:18}}}}});
    document.getElementById('countrySelector').addEventListener('change',function(){if(this.value)location.href='{{ url('/countries') }}/'+this.value+'/news'});
    document.getElementById('newsSearch').addEventListener('input',function(){const q=this.value.toLowerCase().trim();let shown=0;document.querySelectorAll('.news-item').forEach(item=>{const visible=item.dataset.search.includes(q);item.classList.toggle('d-none',!visible);if(visible)shown++});document.getElementById('emptyFilter').style.display=shown?'none':'block'});
    document.getElementById('menuButton').addEventListener('click',()=>document.getElementById('sidebar').classList.toggle('open'));
</script>
</body>
</html>
