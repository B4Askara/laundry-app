<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\DetailPemesanan;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan dashboard untuk laundry milik akun yang login.
     */
    public function index()
    {
        $laundryId = auth()->user()->laundry_id;

        abort_if(
            !$laundryId,
            403,
            'Akun belum terhubung ke laundry.'
        );

        $awalHari = Carbon::today();
        $akhirHari = Carbon::tomorrow();

        // Total pelanggan milik laundry yang login.
        $totalPelanggan = Pelanggan::where(
            'laundry_id',
            $laundryId
        )->count();

        // Total pesanan yang dibuat hari ini.
        $totalTransaksi = Pemesanan::where(
            'laundry_id',
            $laundryId
        )
            ->where('tanggal', '>=', $awalHari)
            ->where('tanggal', '<', $akhirHari)
            ->count();

        // Total uang dari pembayaran yang berhasil hari ini.
        $totalPemasukan = Pembayaran::whereHas(
            'pemesanan',
            function ($query) use ($laundryId) {
                $query->where('laundry_id', $laundryId);
            }
        )
            ->where('tanggal_pembayaran', '>=', $awalHari)
            ->where('tanggal_pembayaran', '<', $akhirHari)
            ->sum('jumlah_bayar');

        // Total detail layanan yang menggunakan reward hari ini.
        $rewardDitukar = DetailPemesanan::where(
            'pakai_reward',
            true
        )
            ->whereHas('pemesanan', function ($query) use (
                $laundryId,
                $awalHari,
                $akhirHari
            ) {
                $query->where('laundry_id', $laundryId)
                    ->where('tanggal', '>=', $awalHari)
                    ->where('tanggal', '<', $akhirHari);
            })
            ->count();

        // Lima transaksi terbaru yang sudah dibayar.
        $transaksiTerbaru = Pemesanan::where(
            'laundry_id',
            $laundryId
        )
            ->with([
                'pelanggan',
                'detailPemesanan.layanan',
                'pembayaran',
            ])
            ->whereHas('pembayaran')
            ->latest('tanggal')
            ->take(5)
            ->get();

        return view('home', compact(
            'totalPelanggan',
            'totalTransaksi',
            'totalPemasukan',
            'rewardDitukar',
            'transaksiTerbaru'
        ));
    }
}
