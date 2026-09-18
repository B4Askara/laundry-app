<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Struk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    /**
     * Menampilkan daftar transaksi.
     */
    public function index()
    {
        $pemesanans = Pemesanan::with([
            'pelanggan',
            'detailPemesanan.layanan',
            'pengambilan',
            'pembayaran'
        ])
        ->latest()
        ->get();

        return view(
            'pembayaran.index',
            compact('pemesanans')
        );
    }

    /**
     * Menampilkan form pembayaran.
     */
    public function create(string $id)
    {
        $pemesanan = Pemesanan::with([
            'pelanggan',
            'detailPemesanan.layanan',
            'detailPemesanan.reward',
            'pengambilan',
            'pembayaran'
        ])->findOrFail($id);

        // Kalau sudah dibayar, jangan bayar lagi.
        if ($pemesanan->pembayaran) {
            return redirect()
                ->route('pembayaran.index')
                ->with(
                    'error',
                    'Pemesanan ini sudah dibayar.'
                );
        }

        return view(
            'pembayaran.create',
            compact('pemesanan')
        );
    }

    /**
     * Menyimpan pembayaran.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'jumlah_bayar' =>
                'required|numeric|min:0',

            'metode_pembayaran' =>
                'required|in:cash,transfer,qris',
        ]);

        DB::transaction(function () use (
            $request,
            $id
        ) {
            /*
             * Kunci pemesanan supaya tidak dibayar
             * dua kali secara bersamaan.
             */
            $pemesanan = Pemesanan::with([
                'detailPemesanan.reward',
                'pelanggan'
            ])
            ->lockForUpdate()
            ->findOrFail($id);

            /*
             * Pastikan belum pernah dibayar.
             */
            if ($pemesanan->pembayaran) {
                abort(
                    422,
                    'Pemesanan ini sudah dibayar.'
                );
            }

            $jumlahBayar =
                (float) $request->jumlah_bayar;

            $totalHarga =
                (float) $pemesanan->total_harga;

            /*
             * Pastikan uang pembayaran cukup.
             */
            if ($jumlahBayar < $totalHarga) {
                abort(
                    422,
                    'Jumlah pembayaran kurang dari total harga.'
                );
            }

            /*
             * Kunci data pelanggan.
             */
            $pelanggan = $pemesanan->pelanggan()
                ->lockForUpdate()
                ->first();

            /*
             * Hitung total stempel yang diperlukan
             * untuk reward yang dipakai.
             */
            $totalStempelReward = 0;

            foreach (
                $pemesanan->detailPemesanan
                as $detail
            ) {
                if (
                    $detail->pakai_reward &&
                    $detail->reward
                ) {
                    $totalStempelReward +=
                        $detail->reward->minimal_stempel;
                }
            }

            /*
             * Pastikan stempel pelanggan cukup.
             */
            if (
                $pelanggan->stempel <
                $totalStempelReward
            ) {
                abort(
                    422,
                    'Stempel pelanggan tidak mencukupi untuk reward.'
                );
            }

            /*
             * Potong stempel untuk reward.
             */
            if ($totalStempelReward > 0) {
                $pelanggan->stempel -=
                    $totalStempelReward;
            }

            /*
             * Setiap transaksi berhasil
             * mendapatkan 1 stempel.
             */
            $pelanggan->stempel += 1;

            $pelanggan->save();

            /*
             * Simpan pembayaran.
             */
            $pembayaran = Pembayaran::create([
                'id_pemesanan' =>
                    $pemesanan->id_pemesanan,

                'jumlah_bayar' =>
                    $jumlahBayar,

                'metode_pembayaran' =>
                    $request->metode_pembayaran,

                'tanggal_pembayaran' =>
                    now(),
            ]);

            /*
             * Buat nomor struk.
             */
            $nomorStruk =
                'STR-' .
                now()->format('YmdHis') .
                '-' .
                $pemesanan->id_pemesanan;

            /*
             * Simpan data struk.
             */
            Struk::create([
                'id_pembayaran' =>
                    $pembayaran->id_pembayaran,

                'nomor_struk' =>
                    $nomorStruk,

                'tanggal_cetak' =>
                    now(),
            ]);
        });

        return redirect()
            ->route('pembayaran.index')
            ->with(
                'success',
                'Pembayaran berhasil disimpan.'
            );
    }
}