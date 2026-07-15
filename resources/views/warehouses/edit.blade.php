<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gudang - Supply Chain</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<nav class="bg-slate-900 p-4 text-white shadow mb-8">
    <div class="max-w-7xl mx-auto flex gap-6 font-medium">
        <a href="{{ route('countries.index') }}" class="hover:text-blue-400 transition">🌍 Data Negara</a>
        <a href="{{ route('suppliers.index') }}" class="hover:text-blue-400 transition">🏭 Data Supplier</a>
        <a href="{{ route('warehouses.index') }}" class="text-blue-400 border-b-2 border-blue-400 pb-1">🏠 Data Gudang</a>
    </div>
</nav>

<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md mb-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800">📝 Edit Data Gudang</h1>
        <a href="{{ route('warehouses.index') }}" class="text-gray-600 hover:underline text-sm">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Gudang *</label>
                <input type="text" name="warehouse_code" value="{{ old('warehouse_code', $warehouse->warehouse_code) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Gudang *</label>
                <input type="text" name="warehouse_name" value="{{ old('warehouse_name', $warehouse->warehouse_name) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Negara Lokasi *</label>
                <select name="country_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                    <option value="">-- Pilih Negara --</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ old('country_id', $warehouse->country_id) == $country->id ? 'selected' : '' }}>
                            {{ $country->country_name }} ({{ $country->country_code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Gudang (m³) *</label>
                <input type="number" name="capacity_m3" value="{{ old('capacity_m3', $warehouse->capacity_m3) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat / Lokasi Detail</label>
            <textarea name="location" rows="3" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('location', $warehouse->location) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status Keaktifan *</label>
            <select name="status" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                <option value="active" {{ old('status', $warehouse->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $warehouse->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">Simpan Perubahan</button>
        </div>
    </form>
</div>

</body>
</html>