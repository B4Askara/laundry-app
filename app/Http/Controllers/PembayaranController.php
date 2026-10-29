<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\Struk;
use App\Models\Reward;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    private function laundryId(): int
    {
        $laundryId = auth()->user()?->laundry_id;

        abort_if(!$laundryId, 403, 'Akun belum terhubung ke laundry.');

        return (int) $laundryId;
    }

    public function index()
    {
        $pemesanans = Pemesanan::where('laundry_id', $this->laundryId())
            ->with([
                'pelanggan',
                'detailPemesanan.layanan',
                'pengambilan',
                'pembayaran',
            ])
            ->orderBy('id_pemesanan', 'asc')
            ->get();

        return view('pembayaran.index', compact('pemesanans'));
    }

    public function create(string $id)
    {
        $laundryId = $this->laundryId();

        $pemesanan = Pemesanan::where('laundry_id', $laundryId)
            ->with([
                'pelanggan',
                'detailPemesanan.layanan',
                'detailPemesanan.reward',
                'pengambilan',
                'pembayaran',
            ])
            ->where('id_pemesanan', $id)
            ->firstOrFail();

        if ($pemesanan->pembayaran) {
            return redirect()
                ->route('pembayaran.index')
                ->with('error', 'Pemesanan ini sudah dibayar.');
        }

        // Ambil pengaturan dan gambar QRIS milik laundry yang login.
        $pengaturan = Pengaturan::where('laundry_id', $laundryId)
            ->first();

        return view(
            'pembayaran.create',
            compact('pemesanan', 'pengaturan')
        );
    }

    public function store(Request $request, string $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
        ]);

        $laundryId = $this->laundryId();

        DB::transaction(function () use ($request, $id, $laundryId) {
            $pemesanan = Pemesanan::where('laundry_id', $laundryId)
                ->where('id_pemesanan', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($pemesanan->pembayaran()->exists()) {
                abort(422, 'Pemesanan ini sudah dibayar.');
            }

            $jumlahBayar = (float) $request->jumlah_bayar;
            $totalHarga = (float) $pemesanan->total_harga;

            if ($jumlahBayar < $totalHarga) {
                abort(422, 'Jumlah pembayaran kurang dari total harga.');
            }

            $pelanggan = $pemesanan->pelanggan()
                ->where('laundry_id', $laundryId)
                ->lockForUpdate()
                ->firstOrFail();

            $pemesanan->load([
                'detailPemesanan.layanan',
                'detailPemesanan.reward',
            ]);

            $totalStempelReward = 0;

            foreach ($pemesanan->detailPemesanan as $detail) {
                if (!$detail->pakai_reward) {
                    continue;
                }

                if (!$detail->id_reward || !$detail->reward) {
                    abort(422, 'Reward pada detail pesanan tidak valid.');
                }

                $reward = Reward::where('laundry_id', $laundryId)
                    ->where('id_reward', $detail->id_reward)
                    ->first();

                if (!$reward) {
                    abort(422, 'Reward bukan milik laundry ini.');
                }

                if ((int) $reward->id_layanan !== (int) $detail->id_layanan) {
                    abort(422, 'Reward tidak sesuai dengan layanan pesanan.');
                }

                if (
                    !$detail->layanan ||
                    (int) $detail->layanan->laundry_id !== $laundryId
                ) {
                    abort(422, 'Layanan pada detail pesanan tidak valid.');
                }

                $totalStempelReward += (int) $reward->minimal_stempel;
            }

            $stempelSaatIni = (int) $pelanggan->stempel;

            if ($stempelSaatIni < $totalStempelReward) {
                abort(422, 'Stempel pelanggan tidak mencukupi untuk reward.');
            }

            // Potong stempel reward dan tambahkan satu stempel transaksi.
            $pelanggan->stempel =
                $stempelSaatIni - $totalStempelReward + 1;

            $pelanggan->save();

            $pembayaran = Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'jumlah_bayar' => $jumlahBayar,
                'metode_pembayaran' => $request->metode_pembayaran,
                'tanggal_pembayaran' => now(),
            ]);

            $nomorStruk = 'STR-' .
                now()->format('YmdHis') .
                '-' .
                $pemesanan->id_pemesanan;

            Struk::create([
                'id_pembayaran' => $pembayaran->id_pembayaran,
                'nomor_struk' => $nomorStruk,
                'tanggal_cetak' => now(),
            ]);
        });

        return redirect()
            ->route('pembayaran.struk', ['id' => $id])
            ->with('success', 'Pembayaran berhasil disimpan.');
    }

    public function struk(string $id)
    {
        $pemesanan = Pemesanan::where('laundry_id', $this->laundryId())
            ->where('id_pemesanan', $id)
            ->with([
                'pelanggan',
                'detailPemesanan.layanan',
                'detailPemesanan.reward',
                'pengambilan',
                'pembayaran',
            ])
            ->firstOrFail();

        $pembayaran = Pembayaran::where(
            'id_pemesanan',
            $pemesanan->id_pemesanan
        )->firstOrFail();

        $struk = Struk::where(
            'id_pembayaran',
            $pembayaran->id_pembayaran
        )->firstOrFail();

        return view('pembayaran.struk', compact(
            'pemesanan',
            'pembayaran',
            'struk'
        ));
    }
}
