<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pemesanan;

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

        // Total pelanggan laundry sendiri.
        $totalPelanggan = Pelanggan::where(
            'laundry_id',
            $laundryId
        )->count();

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
            'transaksiTerbaru'
        ));
    }
}
