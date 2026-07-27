<?php

namespace App\Http\Controllers;

use App\Models\Disruption;
use Illuminate\Http\Request;

class DisruptionController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $query = Disruption::with('country')
            ->when($search, fn ($builder) => $builder->where(fn ($builder) => $builder
                ->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('country', fn ($country) => $country->where('country_name', 'like', "%{$search}%"))))
            ->latest();

        $disruptions = $query->paginate(20)->withQueryString();
        $mapData = Disruption::with('country')->latest()->get();
        $stats = [
            'total' => Disruption::count(),
            'high' => Disruption::where('severity_level', 'High')->count(),
            'medium' => Disruption::where('severity_level', 'Medium')->count(),
            'countries' => Disruption::distinct()->count('affected_country_id'),
        ];

        return view('disruptions.index', compact('disruptions', 'mapData', 'stats', 'search'));
    }

    public function api()
    {
        return response()->json([
            'status' => 'success',
            'data' => Disruption::with('country')->latest()->get(),
        ]);
    }
}
