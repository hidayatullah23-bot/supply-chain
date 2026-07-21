<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Supply Chain RiskIntel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @include('components.dark-theme')
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR 7 FITUR -->
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
                <a href="{{ route('watchlists.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'watchlists' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
                    <i class="fa-solid fa-star"></i><span>2. Watchlist Favorit</span>
                </a>
                <a href="{{ route('countries.compare') }}" class="flex items-center space-x-3 py-2 px-3 rounded hover:bg-gray-800 text-gray-300">
                    <i class="fa-solid fa-right-left"></i><span>3. Perbandingan Negara</span>
                </a>
                <a href="{{ route('ports.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'ports' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
                    <i class="fa-solid fa-anchor"></i><span>4. Data Pelabuhan Dunia</span>
                </a>
                <a href="{{ route('suppliers.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'suppliers' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
                    <i class="fa-solid fa-truck-field"></i><span>5. Direktori Supplier</span>
                </a>
                <a href="{{ route('warehouses.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'warehouses' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
                    <i class="fa-solid fa-warehouse"></i><span>6. Manajemen Gudang</span>
                </a>

                <div class="px-2 pt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Analisis Rantai Pasok</div>
                <a href="{{ route('risk-scores.index') }}" class="flex items-center space-x-3 py-2 px-3 rounded {{ $slug == 'risk-scores' ? 'bg-gray-800 text-yellow-400' : 'hover:bg-gray-800 text-gray-300' }}">
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
                <h1 class="text-xl font-bold text-gray-800">{{ $title }}</h1>
                <div class="text-sm text-gray-600 font-medium">Sistem Monitoring Global</div>
            </header>

            <main class="p-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-bold text-gray-800">Modul: {{ $title }}</h2>
                        @if($slug == 'ports')
                            <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded shadow">
                                <i class="fa-solid fa-plus mr-1"></i> Kelola Data Pelabuhan
                            </a>
                        @endif
                    </div>

                    @if($slug == 'ports')
                        <!-- TABEL KHUSUS PELABUHAN, LOKASI & NEGARA -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pelabuhan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Negara</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kapasitas (TEUs)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                    @forelse($ports as $index => $port)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{ $port->port_name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600"><i class="fa-solid fa-location-dot text-red-500 mr-1"></i> {{ $port->location ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-600"><i class="fa-solid fa-globe text-green-500 mr-1"></i> {{ $port->country ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ number_format($port->capacity ?? 0) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data pelabuhan. Silakan tambahkan melalui <a href="{{ route('admin.dashboard') }}" class="text-blue-600 underline">Admin Panel</a>.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- TAMPILAN UNTUK FITUR LAINNYA -->
                        <div class="border-4 border-dashed border-gray-200 rounded-lg p-12 text-center">
                            <i class="fa-solid fa-folder-open text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500 font-medium">Modul {{ $title }} aktif dan siap menampilkan data pemantauan.</p>
                        </div>
                    @endif
                </div>
            </main>
        </div>

    </div>
</body>
</html>