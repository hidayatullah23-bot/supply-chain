<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gangguan Transportasi Global</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body{background:#eef2f7}.hero{background:linear-gradient(130deg,#101827,#253b65);color:#fff;border-radius:20px}.metric{border:0;border-radius:16px;box-shadow:0 8px 25px #0f172a12}#disruptionMap{height:430px;border-radius:18px}.source-badge{font-size:.7rem}
    </style>
    @include('components.dark-theme')
</head>
<body>
<nav class="navbar navbar-dark bg-dark"><div class="container"><a class="navbar-brand fw-bold" href="{{ route('countries.index') }}">SupplyChain RiskIntel</a><a class="btn btn-outline-light btn-sm" href="{{ route('countries.index') }}">Dashboard</a></div></nav>
<main class="container py-4">
    <section class="hero p-4 mb-4"><h1 class="h2 fw-bold">Gangguan Transportasi Global</h1><p class="mb-0 text-white-50">Pantau sinyal risiko operasional, negara terdampak, tingkat keparahan, dan asal data.</p></section>
    <section class="row g-3 mb-4">@foreach($stats as $label=>$value)<div class="col-6 col-lg-3"><div class="card metric"><div class="card-body"><small class="text-uppercase text-muted">{{ $label }}</small><div class="display-6 fw-bold">{{ $value }}</div></div></div></div>@endforeach</section>
    <section class="card metric mb-4"><div class="card-body"><h2 class="h5 fw-bold">Peta Gangguan</h2><div id="disruptionMap"></div></div></section>
    <section class="card metric"><div class="card-header bg-white"><form class="d-flex gap-2"><input class="form-control" name="search" value="{{ $search }}" placeholder="Cari negara atau gangguan..."><button class="btn btn-primary">Cari</button></form></div><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Gangguan</th><th>Negara</th><th>Severity</th><th>Sumber</th></tr></thead><tbody>
    @forelse($disruptions as $item)<tr><td><b>{{ $item->title }}</b><div class="small text-muted">{{ $item->description }}</div></td><td>{{ $item->country?->country_name }}</td><td><span class="badge text-bg-{{ $item->severity_level==='High'?'danger':'warning' }}">{{ $item->severity_level }}</span></td><td><span class="badge source-badge text-bg-{{ $item->is_estimated?'secondary':'success' }}">{{ $item->is_estimated?'Baseline/estimasi':'Aktual' }}</span><div class="small text-muted">{{ $item->data_source }}</div></td></tr>@empty<tr><td colspan="4" class="text-center py-5">Belum ada gangguan.</td></tr>@endforelse
    </tbody></table></div><div class="card-footer">{{ $disruptions->links('pagination::bootstrap-5') }}</div></section>
</main>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map=L.map('disruptionMap').setView([15,10],2);L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap'}).addTo(map);
const items=@json($mapData);items.forEach(item=>{const c=item.country;if(!c||c.latitude===null||c.longitude===null)return;const color=item.severity_level==='High'?'#dc3545':'#ffc107';L.circleMarker([Number(c.latitude),Number(c.longitude)],{radius:8,color:'#fff',weight:2,fillColor:color,fillOpacity:.9}).addTo(map).bindPopup(`<b>${item.title}</b><br>${c.country_name}<br><small>${item.data_source}</small>`)});
</script>
</body>
</html>
