<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Pengambilan;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    /**
     * Menampilkan daftar pemesanan.
     */
    public function index()
    {
        $pemesanans = Pemesanan::with([
            'pelanggan',
            'layanan',
            'pengambilan',
            'reward',
            'detailPemesanan.layanan'
        ])
        ->latest()
        ->get();

        return view('pemesanan.index', compact('pemesanans'));
    }

    /**
     * Menampilkan form tambah pemesanan.
     */
    public function create()
    {
        $pelanggans = Pelanggan::all();
        $layanans = Layanan::all();
        $pengambilans = Pengambilan::all();
        $rewards = Reward::with('layanan')->get();

        return view('pemesanan.create', compact(
            'pelanggans',
            'layanans',
            'pengambilans',
            'rewards'
        ));
    }

    /**
     * Menyimpan pemesanan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id_pelanggan',
            'id_pengambilan' => 'required|exists:pengambilans,id_pengambilan',
            'tanggal' => 'required|date',

            'layanan' => 'required|array|min:1',

            'layanan.*.id_layanan' =>
                'required|exists:layanans,id_layanan',

            'layanan.*.berat_jumlah' =>
                'required|numeric|min:0.01',

            'layanan.*.id_reward' =>
                'nullable|exists:rewards,id_reward',
        ]);

        DB::transaction(function () use ($request) {

            // Ambil pelanggan
            $pelanggan = Pelanggan::findOrFail(
                $request->id_pelanggan
            );

            // Ambil pengambilan
            $pengambilan = Pengambilan::findOrFail(
                $request->id_pengambilan
            );

            /*
             * Buat pemesanan utama terlebih dahulu.
             * Nilai subtotal dan total sementara 0.
             */
            $pemesanan = Pemesanan::create([
                'id_user' => auth()->id(),
                'id_pelanggan' => $request->id_pelanggan,

                // Kolom lama tetap diisi null
                // karena sekarang layanan disimpan di detail.
                'id_layanan' => null,
                'id_pengambilan' => $request->id_pengambilan,
                'id_reward' => null,

                'berat_jumlah' => 0,
                'subtotal' => 0,
                'ongkir' => $pengambilan->ongkir,
                'total_harga' => 0,
                'tanggal' => $request->tanggal,
            ]);

            $subtotal = 0;
            $rewardDipakai = 0;

            /*
             * Proses setiap layanan.
             */
            foreach ($request->layanan as $item) {

                $layanan = Layanan::findOrFail(
                    $item['id_layanan']
                );

                $beratJumlah = (float) $item['berat_jumlah'];

                $harga = (float) $layanan->harga;

                $subtotalLayanan =
                    $harga * $beratJumlah;

                $pakaiReward = false;

                /*
                 * Kalau pelanggan memilih reward
                 */
                if (!empty($item['id_reward'])) {

                    $reward = Reward::with('layanan')
                        ->findOrFail($item['id_reward']);

                    /*
                     * Pastikan reward memang untuk
                     * layanan yang sedang dipilih.
                     */
                    if (
                        $reward->id_layanan ==
                        $layanan->id_layanan
                    ) {

                        /*
                         * Cek jumlah stempel pelanggan.
                         */
                        if (
                            $pelanggan->stempel >=
                            $reward->minimal_stempel
                        ) {

                            $pakaiReward = true;

                            // Harga layanan menjadi GRATIS
                            $subtotalLayanan = 0;

                            // Kurangi stempel
                            $rewardDipakai +=
                                $reward->minimal_stempel;
                        }
                    }
                }

                /*
                 * Simpan detail pemesanan.
                 */
                DetailPemesanan::create([
                    'id_pemesanan' =>
                        $pemesanan->id_pemesanan,

                    'id_layanan' =>
                        $layanan->id_layanan,

                    'berat_jumlah' =>
                        $beratJumlah,

                    'harga' =>
                        $harga,

                    'subtotal' =>
                        $subtotalLayanan,

                    'pakai_reward' =>
                        $pakaiReward,
                ]);

                $subtotal += $subtotalLayanan;
            }

            /*
             * Kurangi stempel yang digunakan untuk reward.
             */
            $pelanggan->stempel =
                $pelanggan->stempel - $rewardDipakai;

            /*
             * Setiap transaksi mendapatkan 1 stempel.
             */
            $pelanggan->stempel += 1;

            $pelanggan->save();

            /*
             * Hitung total akhir.
             */
            $ongkir = $pengambilan->ongkir;

            $totalHarga =
                $subtotal + $ongkir;

            /*
             * Update pemesanan utama.
             */
            $pemesanan->update([
                'subtotal' =>
                    $subtotal,

                'ongkir' =>
                    $ongkir,

                'total_harga' =>
                    $totalHarga,
            ]);
        });

        return redirect()
            ->route('pemesanan.index')
            ->with(
                'success',
                'Pemesanan berhasil disimpan.'
            );
    }

    /**
     * Menampilkan detail pemesanan.
     */
    public function show(string $id)
    {
        $pemesanan = Pemesanan::with([
            'pelanggan',
            'pengambilan',
            'detailPemesanan.layanan'
        ])->findOrFail($id);

        return view(
            'pemesanan.show',
            compact('pemesanan')
        );
    }

    /**
     * Menampilkan form edit pemesanan.
     */
    public function edit(string $id)
    {
        $pemesanan = Pemesanan::with(
            'detailPemesanan'
        )->findOrFail($id);

        $pelanggans = Pelanggan::all();
        $layanans = Layanan::all();
        $pengambilans = Pengambilan::all();
        $rewards = Reward::with('layanan')->get();

        return view(
            'pemesanan.edit',
            compact(
                'pemesanan',
                'pelanggans',
                'layanans',
                'pengambilans',
                'rewards'
            )
        );
    }

    /**
     * Mengupdate pemesanan.
     */
    public function update(
        Request $request,
        string $id
    ) {
        // Untuk sementara kita fokus menyelesaikan
        // proses pemesanan + reward terlebih dahulu.

        return redirect()
            ->route('pemesanan.index')
            ->with(
                'success',
                'Fitur edit akan disesuaikan dengan detail pemesanan.'
            );
    }

    /**
     * Menghapus pemesanan.
     */
    public function destroy(string $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        $pemesanan->delete();

        return redirect()
            ->route('pemesanan.index')
            ->with(
                'success',
                'Pemesanan berhasil dihapus.'
            );
    }
}