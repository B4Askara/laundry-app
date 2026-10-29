<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    /**
     * Mengambil ID laundry milik akun yang login.
     */
    private function laundryId(): int
    {
        $laundryId = Auth::user()?->laundry_id;

        abort_if(
            !$laundryId,
            403,
            'Akun belum terhubung ke laundry.'
        );

        return (int) $laundryId;
    }

    /**
     * Menampilkan pengaturan laundry sendiri.
     */
    public function index()
    {
        $user = Auth::user();
        $laundryId = $this->laundryId();

        $pengaturan = Pengaturan::firstOrCreate(
            ['laundry_id' => $laundryId],
            [
                'nama_laundry' => $user->laundry?->nama_laundry
                    ?? 'Laundry Saya',
            ]
        );

        return view('pengaturan.index', compact('pengaturan'));
    }

    /**
     * Menyimpan pengaturan laundry sendiri.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_laundry' => ['nullable', 'string', 'max:100'],
            'nama_bank' => ['nullable', 'string', 'max:100'],
            'nomor_rekening' => ['nullable', 'string', 'max:50'],
            'nama_pemilik_rekening' => ['nullable', 'string', 'max:100'],
            'qris_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user = Auth::user();
        $laundryId = $this->laundryId();

        $pengaturan = Pengaturan::firstOrCreate(
            ['laundry_id' => $laundryId],
            [
                'nama_laundry' => $user->laundry?->nama_laundry
                    ?? 'Laundry Saya',
            ]
        );

        $gambarLama = $pengaturan->qris_image;
        $gambarBaru = null;

        if ($request->hasFile('qris_image')) {
            $gambarBaru = $request
                ->file('qris_image')
                ->store('qris', 'public');

            if (!$gambarBaru) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'qris_image' => 'Gagal mengunggah gambar QRIS.',
                    ]);
            }
        }

        try {
            DB::transaction(function () use (
                $request,
                $pengaturan,
                $gambarBaru,
                $user
            ) {
                $pengaturan->fill($request->only([
                    'nama_laundry',
                    'nama_bank',
                    'nomor_rekening',
                    'nama_pemilik_rekening',
                ]));

                if ($gambarBaru) {
                    $pengaturan->qris_image = $gambarBaru;
                }

                $pengaturan->save();

                if ($request->filled('nama_laundry')) {
                    $user->laundry()->update([
                        'nama_laundry' => $request->nama_laundry,
                    ]);
                }
            });
        } catch (\Throwable $e) {
            // Jika database gagal, hapus file baru agar tidak menumpuk.
            if ($gambarBaru) {
                Storage::disk('public')->delete($gambarBaru);
            }

            throw $e;
        }

        // Hapus gambar lama hanya setelah data baru berhasil disimpan.
        if ($gambarBaru && $gambarLama) {
            Storage::disk('public')->delete($gambarLama);
        }

        return redirect()
            ->route('pengaturan.index')
            ->with('success', 'Pengaturan berhasil disimpan!');
    }
}
