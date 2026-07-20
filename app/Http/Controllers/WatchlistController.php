<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use App\Models\Country;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    /**
     * Menampilkan daftar negara yang difavoritkan
     */
    public function index()
    {
        // Untuk simulasi, kita ambil watchlist user ID 1 atau seluruh watchlist
        $watchlists = Watchlist::with(['country.riskScore'])->get();
        $countries = Country::all();

        return view('watchlists.index', compact('watchlists', 'countries'));
    }

    /**
     * Menambahkan negara ke Watchlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id'
        ]);

        Watchlist::firstOrCreate([
            'user_id' => 1, // Simulasi user aktif
            'country_id' => $request->country_id
        ]);

        return redirect()->back()->with('success', 'Negara berhasil ditambahkan ke Favorite Monitoring List!');
    }

    /**
     * Menghapus negara dari Watchlist
     */
    public function destroy($id)
    {
        Watchlist::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Negara berhasil dihapus dari Watchlist.');
    }
}