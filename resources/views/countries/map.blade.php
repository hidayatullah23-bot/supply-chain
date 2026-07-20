<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global Map & Port Monitoring - Supply Chain Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 600px; width: 100%; border-radius: 8px; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>🗺️ Global Supply Chain & Risk Map</h2>
            <a href="/countries" class="btn btn-secondary">← Kembali ke Daftar</a>
        </div>

        <div class="card shadow-sm p-3">
            <div id="map"></div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Inisialisasi peta global
        var map = L.map('map').setView([0, 0], 2);

        // Tambahkan tile layer OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Data negara dari database Laravel
        var countries = @json($countries);

        countries.forEach(function(country) {
            if (country.latitude && country.longitude) {
                var riskScore = country.risk_score ? country.risk_score.total_risk_score : 'Belum Dikalkulasi';
                var riskLevel = country.risk_score ? country.risk_score.risk_level : 'N/A';
                
                var popupContent = `
                    <div>
                        <h5><strong>${country.country_name}</strong></h5>
                        <p class="mb-1">Kode: ${country.country_code ?? '-'}</p>
                        <p class="mb-1">Total Risk: <strong>${riskScore}</strong></p>
                        <p class="mb-2">Level: <strong>${riskLevel}</strong></p>
                        <a href="/countries/${country.id}/dashboard" class="btn btn-sm btn-primary text-white">Buka Dashboard</a>
                    </div>
                `;

                L.marker([country.latitude, country.longitude]).addTo(map)
                    .bindPopup(popupContent);
            }
        });
    </script>
</body>
</html>