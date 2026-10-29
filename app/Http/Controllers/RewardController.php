<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RewardController extends Controller
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
     * Menampilkan reward milik laundry sendiri.
     */
    public function index()
    {
        $rewards = Reward::where('laundry_id', $this->laundryId())
            ->with('layanan')
            ->get();

        return view('reward.index', compact('rewards'));
    }

    /**
     * Form tambah reward.
     */
    public function create()
    {
        $layanans = Layanan::where('laundry_id', $this->laundryId())
            ->orderBy('nama_layanan')
            ->get();

        return view('reward.create', compact('layanans'));
    }

    /**
     * Menyimpan reward baru.
     */
    public function store(Request $request)
    {
        $laundryId = $this->laundryId();

        $request->validate([
            'id_layanan' => [
                'required',
                Rule::exists('layanans', 'id_layanan')
                    ->where('laundry_id', $laundryId),
            ],
            'nama_reward' => 'required|string|max:255',
            'minimal_stempel' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        Reward::create([
            'laundry_id' => $laundryId,
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
     * Form edit reward milik laundry sendiri.
     */
    public function edit(Reward $reward)
    {
        abort_unless(
            (int) $reward->laundry_id === $this->laundryId(),
            404
        );

        $layanans = Layanan::where('laundry_id', $this->laundryId())
            ->orderBy('nama_layanan')
            ->get();

        return view('reward.edit', compact('reward', 'layanans'));
    }

    /**
     * Memperbarui reward milik laundry sendiri.
     */
    public function update(Request $request, Reward $reward)
    {
        $laundryId = $this->laundryId();

        abort_unless(
            (int) $reward->laundry_id === $laundryId,
            404
        );

        $request->validate([
            'id_layanan' => [
                'required',
                Rule::exists('layanans', 'id_layanan')
                    ->where('laundry_id', $laundryId),
            ],
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
     * Menghapus reward milik laundry sendiri.
     */
    public function destroy(Reward $reward)
    {
        abort_unless(
            (int) $reward->laundry_id === $this->laundryId(),
            404
        );

        $reward->delete();

        return redirect()
            ->route('reward.index')
            ->with('success', 'Reward berhasil dihapus.');
    }
}
