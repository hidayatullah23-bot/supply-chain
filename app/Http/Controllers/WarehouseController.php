<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $warehouses = Warehouse::with('country')
            ->when($search, fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('warehouse_name', 'like', "%{$search}%")
                    ->orWhere('warehouse_code', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhereHas('country', fn ($country) => $country->where('country_name', 'like', "%{$search}%"));
            }))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Warehouse::count(),
            'active' => Warehouse::where('status', 'active')->count(),
            'capacity' => (int) Warehouse::sum('capacity_m3'),
            'countries' => Warehouse::distinct()->count('country_id'),
        ];
        $regions = Warehouse::with('country')->get()->groupBy(fn ($warehouse) => $warehouse->country?->region ?? 'Other')
            ->map->count()->sortDesc()->take(5);

        return view('warehouses.index', compact('warehouses', 'stats', 'regions', 'search'));
    }

    public function create()
    {
        return view('warehouses.create', ['countries' => Country::orderBy('country_name')->get()]);
    }

    public function store(Request $request)
    {
        Warehouse::create($this->validated($request));
        return redirect()->route('warehouses.index')->with('success', 'Gudang baru berhasil ditambahkan ke jaringan global.');
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', ['warehouse' => $warehouse, 'countries' => Country::orderBy('country_name')->get()]);
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $warehouse->update($this->validated($request, $warehouse));
        return redirect()->route('warehouses.index')->with('success', 'Data gudang berhasil diperbarui.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return back()->with('success', 'Gudang berhasil dihapus dari jaringan.');
    }

    private function validated(Request $request, ?Warehouse $warehouse = null): array
    {
        return $request->validate([
            'country_id' => ['required', 'exists:countries,id'],
            'warehouse_name' => ['required', 'string', 'max:150'],
            'warehouse_code' => ['required', 'string', 'max:40', Rule::unique('warehouses')->ignore($warehouse)],
            'location' => ['nullable', 'string', 'max:500'],
            'capacity_m3' => ['required', 'integer', 'min:1'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);
    }
}
