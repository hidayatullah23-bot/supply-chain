<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    /**
     * Menampilkan daftar negara yang difavoritkan
     */
    public function index()
    {
        $userId = $this->currentUserId();

        if ($userId && ! Watchlist::where('user_id', $userId)->exists()) {
            $now = now();
            Country::query()->select('id')->orderBy('id')->chunk(500, function ($countries) use ($userId, $now) {
                Watchlist::insertOrIgnore($countries->map(fn ($country) => [
                    'user_id' => $userId,
                    'country_id' => $country->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all());
            });
        }

        $watchlists = Watchlist::with(['country.riskScore'])
            ->when($userId, fn ($query) => $query->where('user_id', $userId))
            ->latest()
            ->get();
        $countries = Country::whereNotIn('id', $watchlists->pluck('country_id'))
            ->orderBy('country_name')
            ->get(['id', 'country_name', 'country_code']);

        $summary = [
            'total' => $watchlists->count(),
            'high' => $watchlists->filter(fn ($item) => $item->country?->riskScore?->risk_level === 'High Risk')->count(),
            'medium' => $watchlists->filter(fn ($item) => $item->country?->riskScore?->risk_level === 'Medium Risk')->count(),
            'average' => round($watchlists->avg(fn ($item) => (float) ($item->country?->riskScore?->total_risk_score ?? 0)), 1),
        ];

        return view('watchlists.index', compact('watchlists', 'countries', 'summary'));
    }

    /**
     * Menambahkan negara ke Watchlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id'
        ]);

        $userId = $this->currentUserId();

        abort_unless($userId, 422, 'Buat user terlebih dahulu sebelum menambahkan watchlist.');

        $watchlist = Watchlist::firstOrCreate([
            'user_id' => $userId,
            'country_id' => $request->country_id
        ]);

        return redirect()->back()->with(
            $watchlist->wasRecentlyCreated ? 'success' : 'info',
            $watchlist->wasRecentlyCreated
                ? 'Negara berhasil ditambahkan ke watchlist.'
                : 'Negara tersebut sudah ada di watchlist.'
        );
    }

    /**
     * Menghapus negara dari Watchlist
     */
    public function destroy($id)
    {
        Watchlist::where('user_id', $this->currentUserId())->findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Negara berhasil dihapus dari Watchlist.');
    }

    private function currentUserId(): ?int
    {
        return auth()->id()
            ?? User::where('email', 'admin@supplychain.test')->value('id')
            ?? User::value('id');
    }
}
