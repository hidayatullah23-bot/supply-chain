<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Negara - Supply Chain</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- MENU NAVIGASI UTAMA -->
<nav class="bg-slate-900 p-4 text-white shadow mb-8">
    <div class="max-w-7xl mx-auto flex gap-6 font-medium">
        <a href="{{ route('countries.index') }}" class="text-blue-400 border-b-2 border-blue-400 pb-1">🌍 Data Negara</a>
        <a href="{{ route('suppliers.index') }}" class="hover:text-blue-400 transition pb-1">🏭 Data Supplier</a>
        <a href="{{ route('warehouses.index') }}" class="hover:text-blue-400 transition pb-1">🏠 Data Gudang</a>
    </div>
</nav>

<div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md mb-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">🌍 Daftar Negara (Countries)</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-slate-800 text-white text-left text-sm font-semibold">
                    <th class="py-3 px-4 border">No</th>
                    <th class="py-3 px-4 border">Kode Negara</th>
                    <th class="py-3 px-4 border">Nama Negara</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($countries as $index => $country)
                <tr class="hover:bg-gray-50 transition border-b">
                    <td class="py-3 px-4 border font-medium">{{ $countries->firstItem() + $index }}</td>
                    <td class="py-3 px-4 border font-mono font-bold text-blue-600">{{ $country->country_code }}</td>
                    <td class="py-3 px-4 border font-semibold">{{ $country->country_name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-6 text-center text-gray-500">Tidak ada data negara.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $countries->links() }}
    </div>
</div>

</body>
</html>