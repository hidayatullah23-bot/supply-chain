<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Supplier - Supply Chain</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- MENU NAVIGASI UTAMA -->
<nav class="bg-slate-900 p-4 text-white shadow mb-8">
    <div class="max-w-7xl mx-auto flex gap-6 font-medium">
        <a href="{{ route('countries.index') }}" class="hover:text-blue-400 transition pb-1">🌍 Data Negara</a>
        <a href="{{ route('suppliers.index') }}" class="text-blue-400 border-b-2 border-blue-400 pb-1">🏭 Data Supplier</a>
        <a href="{{ route('warehouses.index') }}" class="hover:text-blue-400 transition pb-1">🏠 Data Gudang</a>
    </div>
</nav>

<div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md mb-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">🏭 Daftar Pemasok (Suppliers)</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <a href="{{ route('suppliers.create') }}">
            <button class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded transition">
                + Tambah Supplier
            </button>
        </a>

        <form action="{{ route('suppliers.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <input 
                type="text" 
                name="search" 
                value="{{ $search ?? '' }}" 
                placeholder="Cari nama supplier, PIC, email..." 
                class="border rounded px-3 py-2 w-full md:w-80 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                Cari
            </button>
            @if(!empty($search))
                <a href="{{ route('suppliers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded transition flex items-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-slate-800 text-white text-left text-sm font-semibold">
                    <th class="py-3 px-4 border">No</th>
                    <th class="py-3 px-4 border">Nama Perusahaan</th>
                    <th class="py-3 px-4 border">Kontak PIC</th>
                    <th class="py-3 px-4 border">Email</th>
                    <th class="py-3 px-4 border">Negara Asal</th>
                    <th class="py-3 px-4 border">Status</th>
                    <th class="py-3 px-4 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($suppliers as $index => $supplier)
                <tr class="hover:bg-gray-50 transition border-b">
                    <td class="py-3 px-4 border font-medium">
                        {{ $suppliers->firstItem() + $index }}
                    </td>
                    <td class="py-3 px-4 border font-semibold text-gray-900">{{ $supplier->supplier_name }}</td>
                    <td class="py-3 px-4 border">{{ $supplier->contact_name ?? '-' }}</td>
                    <td class="py-3 px-4 border font-mono text-gray-600">{{ $supplier->email }}</td>
                    <td class="py-3 px-4 border">
                        <span class="bg-blue-50 text-blue-800 text-xs px-2.5 py-1 rounded font-medium">
                            🌍 {{ $supplier->country->country_name ?? 'Tidak Diketahui' }}
                        </span>
                    </td>
                    <td class="py-3 px-4 border">
                        @if($supplier->status == 'active')
                            <span class="bg-green-100 text-green-800 text-xs px-2.5 py-0.5 rounded-full font-medium">Active</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs px-2.5 py-0.5 rounded-full font-medium">Inactive</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 border text-center whitespace-nowrap">
                        <a href="{{ route('suppliers.edit', $supplier->id) }}">
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-3 py-1 rounded mr-1 transition">Edit</button>
                        </a>
                
                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1 rounded transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-6 text-center text-gray-500">Data supplier tidak ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $suppliers->links() }}
    </div>
</div>

</body>
</html>