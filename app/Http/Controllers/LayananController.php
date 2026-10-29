<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Layanan;

class LayananController extends Controller
{
    /**
     * Mengambil ID laundry akun yang sedang login.
     */
    private function laundryId(): int
    {
        $laundryId = Auth::user()->laundry_id;

        abort_if(!$laundryId, 403, 'Akun belum terhubung ke laundry.');

        return (int) $laundryId;
    }

    /**
     * Menampilkan layanan milik laundry sendiri.
     */
    public function index()
    {
        $layanans = Layanan::where('laundry_id', $this->laundryId())
            ->orderBy('nama_layanan')
            ->get();

        return view('layanan.index', compact('layanans'));
    }

    /**
     * Menampilkan form tambah layanan.
     */
    public function create()
    {
        return view('layanan.create');
    }

    /**
     * Menyimpan layanan baru ke laundry sendiri.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'kategori' => 'required|in:kiloan,satuan,express',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|in:KG,item',
        ]);

        Layanan::create([
            'laundry_id' => $this->laundryId(),
            'nama_layanan' => $request->nama_layanan,
            'kategori' => $request->kategori,
            'harga' => $request->harga,
            'satuan' => $request->satuan,
        ]);

        return redirect()
            ->route('layanan.index')
            ->with('success', 'Data layanan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail layanan milik laundry sendiri.
     */
    public function show(string $id)
    {
        $layanan = Layanan::where('laundry_id', $this->laundryId())
            ->where('id_layanan', $id)
            ->firstOrFail();

        return view('layanan.show', compact('layanan'));
    }

    /**
     * Menampilkan form edit layanan milik laundry sendiri.
     */
    public function edit(string $id)
    {
        $layanan = Layanan::where('laundry_id', $this->laundryId())
            ->where('id_layanan', $id)
            ->firstOrFail();

        return view('layanan.edit', compact('layanan'));
    }

    /**
     * Memperbarui layanan milik laundry sendiri.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'kategori' => 'required|in:kiloan,satuan,express',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|in:KG,item',
        ]);

        $layanan = Layanan::where('laundry_id', $this->laundryId())
            ->where('id_layanan', $id)
            ->firstOrFail();

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
     * Menghapus layanan milik laundry sendiri.
     */
    public function destroy(string $id)
    {
        $layanan = Layanan::where('laundry_id', $this->laundryId())
            ->where('id_layanan', $id)
            ->firstOrFail();

        $layanan->delete();

        return redirect()
            ->route('layanan.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
