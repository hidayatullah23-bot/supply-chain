<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier - Supply Chain</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<nav class="bg-slate-900 p-4 text-white shadow mb-8">
    <div class="max-w-7xl mx-auto flex gap-6 font-medium">
        <a href="{{ route('countries.index') }}" class="hover:text-blue-400 transition">🌍 Data Negara</a>
        <a href="{{ route('suppliers.index') }}" class="text-blue-400 border-b-2 border-blue-400 pb-1">🏭 Data Supplier</a>
    </div>
</nav>

<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md mb-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800">📝 Edit Data Supplier</h1>
        <a href="{{ route('suppliers.index') }}" class="text-gray-600 hover:underline text-sm">Kembali</a>
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

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan *</label>
            <input type="text" name="supplier_name" value="{{ old('supplier_name', $supplier->supplier_name) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama PIC (Kontak)</label>
                <input type="text" name="contact_name" value="{{ old('contact_name', $supplier->contact_name) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Negara Asal *</label>
                <select name="country_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                    <option value="">-- Pilih Negara --</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" {{ old('country_id', $supplier->country_id) == $country->id ? 'selected' : '' }}>
                            {{ $country->country_name }} ({{ $country->country_code }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email', $supplier->email) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Perusahaan</label>
            <textarea name="address" rows="3" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('address', $supplier->address) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status Keaktifan *</label>
            <select name="status" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                <option value="active" {{ old('status', $supplier->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $supplier->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">Simpan Perubahan</button>
        </div>
    </form>
</div>

</body>
</html>