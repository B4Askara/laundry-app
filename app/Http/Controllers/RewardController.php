<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\Layanan;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    /**
     * Menampilkan semua reward.
     */
    public function index()
    {
        $rewards = Reward::with('layanan')->get();

        return view('reward.index', compact('rewards'));
    }

    /**
     * Form tambah reward.
     */
    public function create()
    {
        $layanans = Layanan::all();

        return view('reward.create', compact('layanans'));
    }

    /**
     * Menyimpan reward baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_layanan' => 'required|exists:layanans,id_layanan',
            'nama_reward' => 'required|string|max:255',
            'minimal_stempel' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        Reward::create([
            'id_layanan' => $request->id_layanan,
            'nama_reward' => $request->nama_reward,
            'minimal_stempel' => $request->minimal_stempel,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('reward.index')
            ->with('success', 'Reward berhasil ditambahkan.');
    }

    /**
     * Form edit reward.
     */
    public function edit(Reward $reward)
    {
        $layanans = Layanan::all();

        return view('reward.edit', compact(
            'reward',
            'layanans'
        ));
    }

    /**
     * Mengupdate reward.
     */
    public function update(Request $request, Reward $reward)
    {
        $request->validate([
            'id_layanan' => 'required|exists:layanans,id_layanan',
            'nama_reward' => 'required|string|max:255',
            'minimal_stempel' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $reward->update([
            'id_layanan' => $request->id_layanan,
            'nama_reward' => $request->nama_reward,
            'minimal_stempel' => $request->minimal_stempel,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()
            ->route('reward.index')
            ->with('success', 'Reward berhasil diperbarui.');
    }

    /**
     * Menghapus reward.
     */
    public function destroy(Reward $reward)
    {
        $reward->delete();

        return redirect()
            ->route('reward.index')
            ->with('success', 'Reward berhasil dihapus.');
    }
}