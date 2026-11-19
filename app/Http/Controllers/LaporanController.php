<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $laundryId = auth()->user()?->laundry_id;

        abort_if(!$laundryId, 403, 'Akun belum terhubung ke laundry.');

        // Bulan dan tahun yang dipilih
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        // Validasi periode
        if ($bulan < 1 || $bulan > 12) {
            $bulan = now()->month;
        }

        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = now()->year;
        }

        // Query pembayaran milik laundry yang sedang login
        $query = Pembayaran::whereHas('pemesanan', function ($q) use ($laundryId) {
            $q->where('laundry_id', $laundryId);
        })
        ->whereMonth('tanggal_pembayaran', $bulan)
        ->whereYear('tanggal_pembayaran', $tahun);

        // Total transaksi
        $totalTransaksi = (clone $query)->count();

        // Total pendapatan berdasarkan harga pesanan
        $totalPendapatan = (clone $query)
            ->join(
                'pemesanans',
                'pembayarans.id_pemesanan',
                '=',
                'pemesanans.id_pemesanan'
            )
            ->sum('pemesanans.total_harga');

        // Rekap metode pembayaran
        $rekapMetode = (clone $query)
            ->select(
                'metode_pembayaran',
                DB::raw('COUNT(*) as jumlah_transaksi')
            )
            ->groupBy('metode_pembayaran')
            ->get()
            ->keyBy('metode_pembayaran');

        // Detail transaksi
        $transaksis = (clone $query)
            ->with([
                'pemesanan.pelanggan',
                'pemesanan.detailPemesanan.layanan',
            ])
            ->orderBy('tanggal_pembayaran', 'desc')
            ->get();

        return view('laporan.index', compact(
            'bulan',
            'tahun',
            'totalTransaksi',
            'totalPendapatan',
            'rekapMetode',
            'transaksis'
        ));
    }
}
