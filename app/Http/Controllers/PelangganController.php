<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    /**
     * Mengambil ID laundry milik akun yang sedang login.
     */
    private function laundryId(): int
    {
        $laundryId = Auth::user()->laundry_id;

        abort_if(!$laundryId, 403, 'Akun belum terhubung ke laundry.');

        return (int) $laundryId;
    }

    public function index()
    {
        $pelanggans = Pelanggan::where('laundry_id', $this->laundryId())
            ->latest()
            ->get();

        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:30',
            'alamat' => 'required|string|max:1000',
        ]);

        Pelanggan::create([
            'laundry_id' => $this->laundryId(),
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'stempel' => 0,
        ]);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $pelanggan = Pelanggan::where('laundry_id', $this->laundryId())
            ->where('id_pelanggan', $id)
            ->firstOrFail();

        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:30',
            'alamat' => 'required|string|max:1000',
        ]);

        $pelanggan = Pelanggan::where('laundry_id', $this->laundryId())
            ->where('id_pelanggan', $id)
            ->firstOrFail();

        $pelanggan->update([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diubah.');
    }

    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::where('laundry_id', $this->laundryId())
            ->where('id_pelanggan', $id)
            ->firstOrFail();

        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
