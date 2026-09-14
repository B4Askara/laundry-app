<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Layanan;

class LayananController extends Controller
{
    /**
     * Menampilkan semua layanan.
     */
    public function index()
    {
        $layanans = Layanan::all();

        return view('layanan.index', compact('layanans'));
    }

    /**
     * Menampilkan form tambah layanan.
     */
    public function create()
    {
    return view('layanan.create');
    }

public function store(Request $request)
    {
    $request->validate([
        'nama_layanan' => 'required',
        'kategori' => 'required',
        'harga' => 'required|numeric',
        'satuan' => 'required',
    ]);

    Layanan::create([
        'nama_layanan' => $request->nama_layanan,
        'kategori' => $request->kategori,
        'harga' => $request->harga,
        'satuan' => $request->satuan,
    ]);

    return redirect()->route('layanan.index')
        ->with('success', 'Data layanan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail layanan.
     */
    public function show(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        return view('layanan.show', compact('layanan'));
    }

    /**
     * Menampilkan form edit layanan.
     */
    public function edit(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        return view('layanan.edit', compact('layanan'));
    }

    /**
     * Mengupdate layanan.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'kategori' => 'required|in:kiloan,satuan,express',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|in:KG,item',
        ]);

        $layanan = Layanan::findOrFail($id);

        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'satuan' => $request->satuan,
        ]);

        return redirect()
            ->route('layanan.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Menghapus layanan.
     */
    public function destroy(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        $layanan->delete();

        return redirect()
            ->route('layanan.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}