<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Pemesanan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Total pelanggan
        $totalPelanggan = Pelanggan::count();

        // 5 transaksi terbaru yang sudah dibayar
        $transaksiTerbaru = Pemesanan::with([
            'pelanggan',
            'detailPemesanan.layanan',
            'pembayaran'
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