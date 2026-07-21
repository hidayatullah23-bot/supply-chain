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
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-800">Modul {{ $title }}</h2>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Aktif</span>
                    </div>
                    <p class="text-gray-600 mb-6">Halaman ini menampilkan data dan parameter operasional untuk **{{ $title }}** dalam ekosistem Supply Chain RiskIntel.</p>
                    
                    <div class="border-4 border-dashed border-gray-200 rounded-lg p-12 text-center">
                        <i class="fa-solid fa-folder-open text-gray-400 text-4xl mb-3"></i>
                        <p class="text-gray-500 font-medium">Dataset dan pemantauan real-time untuk modul ini siap diintegrasikan.</p>
                    </div>
                </div>
            </main>
        </div>

    </div>
</body>
</html>