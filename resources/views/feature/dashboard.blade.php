<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Supply Chain RiskIntel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('components.dark-theme')
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <div class="w-64 bg-gray-900 text-white flex flex-col shadow-lg overflow-y-auto">
            <div class="p-4 text-lg font-bold bg-gray-800 flex items-center space-x-2">
                <i class="fa-solid fa-boxes-stacked text-yellow-400"></i>
                <span>SupplyChain RiskIntel</span>
            </div>

            <nav class="mt-4 flex-1 pb-6 text-sm space-y-1 px-2">
                <div class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Menu Utama</div>
                <a href="{{ route('countries.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-gray-800 text-gray-300">
                    <i class="fa-solid fa-earth-asia"></i><span>1. Master Negara</span>
                </a>
                <a href="{{ route('feature.show', 'watchlists') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'watchlists' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
                    <i class="fa-solid fa-star"></i><span>2. Watchlist Favorit</span>
                </a>
                <a href="{{ route('countries.compare') }}" class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-gray-800 text-gray-300">
                    <i class="fa-solid fa-right-left"></i><span>3. Perbandingan Negara</span>
                </a>
                <a href="{{ route('feature.show', 'ports') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'ports' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
                    <i class="fa-solid fa-anchor"></i><span>4. Data Pelabuhan Dunia</span>
                </a>
                <a href="{{ route('feature.show', 'suppliers') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'suppliers' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
                    <i class="fa-solid fa-truck-field"></i><span>5. Direktori Supplier</span>
                </a>
                <a href="{{ route('feature.show', 'warehouses') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'warehouses' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
                    <i class="fa-solid fa-warehouse"></i><span>6. Manajemen Gudang</span>
                </a>

                <div class="px-2 pt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Analisis Rantai Pasok</div>
                <a href="{{ route('feature.show', 'risk-scores') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'risk-scores' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
                    <i class="fa-solid fa-chart-line"></i><span>7. Skor Risiko (Risk Scores)</span>
                </a>
                
                <div class="pt-4 px-2">
                    <a href="{{ route('admin.dashboard') }}" class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded shadow">
                        <i class="fa-solid fa-user-shield mr-1"></i> Admin Panel
                    </a>
                </div>
            </nav>
        </div>

        <!-- KONTEN UTAMA -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800">{{ $title }} Dashboard</h1>
                <div class="text-sm text-gray-600 font-medium">Sistem Monitoring Global</div>
            </header>

            <main class="p-6 space-y-6">
                <!-- WIDGET INTERAKTIF (PETA, KURS, CUACA, GRAFIK KURS) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Peta & Kode Wilayah -->
                    <div class="bg-white rounded-lg shadow-md p-5 border-t-4 border-blue-500">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center"><i class="fa-solid fa-map-location-dot text-blue-500 mr-2"></i> Peta & Kode Wilayah</h3>
                        <div class="bg-blue-50 p-3 rounded text-sm text-blue-900 mb-3">
                            <p class="font-semibold">Zona Pantau Utama:</p>
                            <ul class="list-disc pl-5 mt-1 text-xs space-y-1">
                                <li>Singapura [SG] - Asia Tenggara</li>
                                <li>Rotterdam [NL] - Eropa Barat</li>
                                <li>Shanghai [CN] - Asia Timur</li>
                            </ul>
                        </div>
                        <div class="h-28 bg-gray-100 rounded flex items-center justify-center text-gray-500 text-xs font-semibold border border-dashed border-gray-300">
                            [ Peta Interaktif Modul {{ $title }} ]
                        </div>
                    </div>

                    <!-- Kurs & Grafik Mata Uang -->
                    <div class="bg-white rounded-lg shadow-md p-5 border-t-4 border-green-500">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center"><i class="fa-solid fa-money-bill-transfer text-green-500 mr-2"></i> Kurs & Grafik Mata Uang</h3>
                        <div class="text-xs space-y-2 mb-2">
                            <div class="flex justify-between border-b pb-1"><span>USD / IDR</span><span class="font-bold text-green-600">Rp 16.250</span></div>
                            <div class="flex justify-between border-b pb-1"><span>EUR / IDR</span><span class="font-bold text-green-600">Rp 17.600</span></div>
                        </div>
                        <div class="h-20">
                            <canvas id="currencyChart"></canvas>
                        </div>
                    </div>

                    <!-- Cuaca Logistik -->
                    <div class="bg-white rounded-lg shadow-md p-5 border-t-4 border-amber-500">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center"><i class="fa-solid fa-cloud-sun text-amber-500 mr-2"></i> Cuaca Jalur Logistik</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded text-xs">
                                <span class="font-medium">Selat Malaka</span>
                                <span class="text-amber-600 font-bold"><i class="fa-solid fa-cloud-rain mr-1"></i> Berawan (30Â°C)</span>
                            </div>
                            <div class="flex items-center justify-between bg-gray-50 p-2 rounded text-xs">
                                <span class="font-medium">Pelabuhan Shanghai</span>
                                <span class="text-blue-600 font-bold"><i class="fa-solid fa-sun mr-1"></i> Cerah (26Â°C)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABEL DATA SPESIFIK FITUR -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-gray-800">Tabel Data: {{ $title }}</h2>
                        @if($slug == 'ports')
                            <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded shadow">
                                <i class="fa-solid fa-plus mr-1"></i> Kelola Data Pelabuhan
                            </a>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
                                <tr>
                                    <th class="px-6 py-3 text-left">No</th>
                                    @if($slug == 'ports')
                                        <th class="px-6 py-3 text-left">Nama Pelabuhan</th>
                                        <th class="px-6 py-3 text-left">Lokasi</th>
                                        <th class="px-6 py-3 text-left">Negara</th>
                                        <th class="px-6 py-3 text-left">Kapasitas (TEUs)</th>
                                    @elseif($slug == 'watchlists')
                                        <th class="px-6 py-3 text-left">Entitas / Wilayah</th>
                                        <th class="px-6 py-3 text-left">Kategori</th>
                                        <th class="px-6 py-3 text-left">Status Pantau</th>
                                        <th class="px-6 py-3 text-left">Level Risiko</th>
                                    @elseif($slug == 'suppliers')
                                        <th class="px-6 py-3 text-left">Nama Supplier</th>
                                        <th class="px-6 py-3 text-left">Lokasi</th>
                                        <th class="px-6 py-3 text-left">Komoditas / Material</th>
                                        <th class="px-6 py-3 text-left">Status</th>
                                    @elseif($slug == 'warehouses')
                                        <th class="px-6 py-3 text-left">Kode Gudang</th>
                                        <th class="px-6 py-3 text-left">Lokasi</th>
                                        <th class="px-6 py-3 text-left">Kapasitas Terpakai</th>
                                        <th class="px-6 py-3 text-left">Status Operasional</th>
                                    @elseif($slug == 'risk-scores')
                                        <th class="px-6 py-3 text-left">Entitas Pantau</th>
                                        <th class="px-6 py-3 text-left">Faktor Penilaian</th>
                                        <th class="px-6 py-3 text-left">Skor Risiko</th>
                                        <th class="px-6 py-3 text-left">Klasifikasi</th>
                                    @elseif($slug == 'articles')
                                        <th class="px-6 py-3 text-left">Judul Berita Krisis</th>
                                        <th class="px-6 py-3 text-left">Sumber</th>
                                        <th class="px-6 py-3 text-left">Tanggal</th>
                                        <th class="px-6 py-3 text-left">Dampak</th>
                                    @elseif($slug == 'sentiments')
                                        <th class="px-6 py-3 text-left">Topik Analisis</th>
                                        <th class="px-6 py-3 text-left">Sentimen Pasar</th>
                                        <th class="px-6 py-3 text-left">Skor Probabilitas</th>
                                        <th class="px-6 py-3 text-left">Tren</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                @if($slug == 'ports')
                                    @forelse($ports as $i => $p)
                                        <tr>
                                            <td class="px-6 py-4">{{ $i + 1 }}</td>
                                            <td class="px-6 py-4 font-bold">{{ $p->port_name ?? '-' }}</td>
                                            <td class="px-6 py-4">{{ $p->latitude ?? '-' }}, {{ $p->longitude ?? '-' }}</td>
                                            <td class="px-6 py-4">{{ $p->country_name ?? '-' }}</td>
                                            <td class="px-6 py-4 font-semibold">{{ $p->harbor_size ?? 'Publik' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data pelabuhan.</td></tr>
                                    @endforelse
                                @else
                                    @foreach($currentData as $i => $row)
                                        <tr>
                                            <td class="px-6 py-4">{{ $i + 1 }}</td>
                                            <td class="px-6 py-4 font-bold">{{ $row['name'] ?? $row['code'] ?? $row['entity'] ?? $row['title'] ?? $row['topic'] }}</td>
                                            <td class="px-6 py-4">{{ $row['category'] ?? $row['location'] ?? $row['source'] ?? $row['sentiment'] }}</td>
                                            <td class="px-6 py-4">{{ $row['status'] ?? $row['capacity'] ?? $row['score'] ?? $row['date'] ?? $row['trend'] }}</td>
                                            <td class="px-6 py-4 font-semibold">{{ $row['risk'] ?? $row['level'] ?? $row['impact'] ?? '' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const ctx = document.getElementById("currencyChart").getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Sen", "Sel", "Rab", "Kam", "Jum"],
                datasets: [{
                    data: [16200, 16220, 16190, 16240, 16250],
                    borderColor: "#10b981",
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { display: false }, x: { ticks: { font: { size: 9 } } } }
            }
        });
    </script>
</body>
</html>
