<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Port;
use App\Models\Article;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Menampilkan Halaman Utama Admin Dashboard
     */
    public function index()
    {
        $users = User::all();
        $ports = Port::all();
        $articles = Article::all();

        return view('admin.dashboard', compact('users', 'ports', 'articles'));
    }

    /**
     * Mengelola / Menambah Dataset Pelabuhan Baru
     */
    public function storePort(Request $request)
    {
        $request->validate([
            'port_name' => 'required|string|max:255',
            'country_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'harbor_size' => 'nullable|string'
        ]);

        Port::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Pelabuhan baru berhasil ditambahkan ke database!');
    }

    /**
     * Menghapus Dataset Pelabuhan
     */
    public function destroyPort($id)
    {
        Port::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Pelabuhan berhasil dihapus.');
    }

    /**
     * Mengelola / Menambah Artikel Analisis
     */
    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'source' => 'nullable|string',
            'sentiment_result' => 'required|string'
        ]);

        Article::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Artikel analisis baru berhasil ditambahkan!');
    }

    /**
     * Menghapus Artikel Analisis
     */
    public function destroyArticle($id)
    {
        Article::findOrFail($id)->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Artikel analisis berhasil dihapus.');
    }
}