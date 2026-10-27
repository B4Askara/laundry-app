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
     * Menampilkan halaman pertama alur pemesanan.
     */
    public function alurPelanggan()
    {
        $pelanggans = Pelanggan::orderBy('nama')->get();

        return view('pemesanan.alur.pelanggan', compact('pelanggans'));
    }

    /**
     * Mencari pelanggan untuk autocomplete.
     */
    public function cariPelanggan(Request $request)
    {
        $nama = trim($request->query('q', ''));

        if ($nama === '') {
            return response()->json([]);
        }

        $pelanggans = Pelanggan::where('nama', 'like', '%' . $nama . '%')
            ->limit(8)
            ->get([
                'id_pelanggan',
                'nama',
                'no_hp',
                'alamat',
                'stempel',
            ]);

        return response()->json($pelanggans);
    }

    /**
     * Menyimpan sementara data pelanggan untuk alur pemesanan.
     */
    public function simpanPelangganAlur(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $idPelanggan = $request->id_pelanggan;

        if ($idPelanggan) {
            $pelanggan = Pelanggan::findOrFail($idPelanggan);

            session([
                'alur_pemesanan.pelanggan' => [
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'nama' => $pelanggan->nama,
                    'no_hp' => $pelanggan->no_hp,
                    'alamat' => $pelanggan->alamat,
                    'stempel' => $pelanggan->stempel,
                ],
            ]);
        } else {
            session([
                'alur_pemesanan.pelanggan' => [
                    'id_pelanggan' => null,
                    'nama' => $request->nama,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'stempel' => 0,
                ],
            ]);
        }

        return redirect()->route('pemesanan.alur.layanan');
    }

    /**
     * Menampilkan halaman pilih jenis pelayanan.
     */
    public function alurLayanan()
    {
        $layanans = Layanan::orderBy('nama_layanan')->get();
        $pelanggan = session('alur_pemesanan.pelanggan');

        if (!$pelanggan) {
            return redirect()->route('pemesanan.alur.pelanggan');
        }

        return view(
            'pemesanan.alur.layanan',
            compact('layanans', 'pelanggan')
        );
    }

    /**
     * Menyimpan pilihan layanan untuk alur pemesanan.
     */
    public function simpanLayananAlur(Request $request)
    {
        $request->validate([
            'layanan' => 'required|array|min:1',
            'layanan.*' => 'exists:layanans,id_layanan',
        ]);

        $layanans = Layanan::whereIn(
            'id_layanan',
            $request->layanan
        )->get();

        session([
            'alur_pemesanan.layanan' => $layanans->map(function ($layanan) {
                return [
                    'id_layanan' => $layanan->id_layanan,
                    'nama_layanan' => $layanan->nama_layanan,
                    'harga' => $layanan->harga,
                    'satuan' => $layanan->satuan,
                ];
            })->values()->toArray(),
        ]);

        return redirect()->route('pemesanan.alur.detail');
    }

    /**
     * Menampilkan halaman detail layanan.
     */
    public function alurDetail()
    {
        $pelanggan = session('alur_pemesanan.pelanggan');
        $layanans = session('alur_pemesanan.layanan');

        if (!$pelanggan) {
            return redirect()->route('pemesanan.alur.pelanggan');
        }

        if (!$layanans) {
            return redirect()->route('pemesanan.alur.layanan');
        }

        return view(
            'pemesanan.alur.detail',
            compact('pelanggan', 'layanans')
        );
    }

    /**
     * Menyimpan detail layanan untuk alur pemesanan.
     */
    public function simpanDetailAlur(Request $request)
    {
        $request->validate([
            'layanan' => 'required|array|min:1',
            'layanan.*.id_layanan' => 'required|exists:layanans,id_layanan',
            'layanan.*.berat_jumlah' => 'required|numeric|min:0.01',
        ]);

        $detailLayanan = [];

        foreach ($request->layanan as $item) {
            $layanan = Layanan::findOrFail($item['id_layanan']);

            $beratJumlah = (float) $item['berat_jumlah'];
            $harga = (float) $layanan->harga;

            $detailLayanan[] = [
                'id_layanan' => $layanan->id_layanan,
                'nama_layanan' => $layanan->nama_layanan,
                'harga' => $harga,
                'satuan' => $layanan->satuan,
                'berat_jumlah' => $beratJumlah,
                'subtotal' => $harga * $beratJumlah,
            ];
        }

        session([
            'alur_pemesanan.detail_layanan' => $detailLayanan,
        ]);

        return redirect()->route('pemesanan.alur.pengambilan');
    }

    /**
     * Menampilkan halaman metode pengambilan.
     */
    public function alurPengambilan()
    {
        $pelanggan = session('alur_pemesanan.pelanggan');
        $detailLayanan = session('alur_pemesanan.detail_layanan');

        if (!$pelanggan) {
            return redirect()->route('pemesanan.alur.pelanggan');
        }

        if (empty($detailLayanan)) {
            return redirect()->route('pemesanan.alur.detail');
        }

        $pengambilans = Pengambilan::all();

        return view(
            'pemesanan.alur.pengambilan',
            compact('pelanggan', 'detailLayanan', 'pengambilans')
        );
    }

    /**
     * Menyimpan metode pengambilan.
     */
    public function simpanPengambilanAlur(Request $request)
    {
        $request->validate([
            'id_pengambilan' => 'required|exists:pengambilans,id_pengambilan',
        ]);

        $pengambilan = Pengambilan::findOrFail(
            $request->id_pengambilan
        );

        session([
            'alur_pemesanan.pengambilan' => [
                'id_pengambilan' => $pengambilan->id_pengambilan,
                'metode' => $pengambilan->metode,
                'ongkir' => $pengambilan->ongkir,
            ],
        ]);

        return redirect()->route('pemesanan.alur.poin');
    }

    /**
     * Menampilkan halaman poin / stempel.
     */
    public function alurPoin()
    {
        $pelanggan = session('alur_pemesanan.pelanggan');
        $detailLayanan = session('alur_pemesanan.detail_layanan');
        $pengambilan = session('alur_pemesanan.pengambilan');

        if (!$pelanggan) {
            return redirect()->route('pemesanan.alur.pelanggan');
        }

        if (empty($detailLayanan)) {
            return redirect()->route('pemesanan.alur.detail');
        }

        if (!$pengambilan) {
            return redirect()->route('pemesanan.alur.pengambilan');
        }

        return view(
            'pemesanan.alur.poin',
            compact('pelanggan', 'detailLayanan', 'pengambilan')
        );
    }

    /**
     * Menampilkan halaman pilih reward.
     */
    public function alurReward()
    {
        $pelanggan = session('alur_pemesanan.pelanggan');
        $detailLayanan = session('alur_pemesanan.detail_layanan');
        $pengambilan = session('alur_pemesanan.pengambilan');

        if (!$pelanggan) {
            return redirect()->route('pemesanan.alur.pelanggan');
        }

        if (empty($detailLayanan)) {
            return redirect()->route('pemesanan.alur.detail');
        }

        if (!$pengambilan) {
            return redirect()->route('pemesanan.alur.pengambilan');
        }

        $rewards = Reward::with('layanan')
            ->orderBy('minimal_stempel')
            ->get();

        return view(
            'pemesanan.alur.reward',
            compact('pelanggan', 'detailLayanan', 'pengambilan', 'rewards')
        );
    }

    /**
     * Menyimpan pilihan reward.
     */
    public function simpanRewardAlur(Request $request)
    {
        $request->validate([
            'id_reward' => 'nullable|exists:rewards,id_reward',
        ]);

        $idReward = $request->id_reward;

        if ($idReward) {
            $reward = Reward::with('layanan')->findOrFail($idReward);

            $pelanggan = session('alur_pemesanan.pelanggan');
            $detailLayanan = session('alur_pemesanan.detail_layanan', []);

            if (!$pelanggan || empty($detailLayanan)) {
                return redirect()->route('pemesanan.alur.pelanggan');
            }

            $layananCocok = collect($detailLayanan)->contains(
                fn ($layanan) =>
                    (int) $layanan['id_layanan'] ===
                    (int) $reward->id_layanan
            );

            if (!$layananCocok) {
                return back()->with(
                    'error',
                    'Reward ini tidak sesuai dengan layanan yang dipilih.'
                );
            }

            if ((int) $pelanggan['stempel'] < (int) $reward->minimal_stempel) {
                return back()->with(
                    'error',
                    'Stempel pelanggan belum mencukupi untuk reward ini.'
                );
            }

            session([
                'alur_pemesanan.reward' => [
                    'id_reward' => $reward->id_reward,
                    'nama_reward' => $reward->nama_reward,
                    'minimal_stempel' => $reward->minimal_stempel,
                    'id_layanan' => $reward->id_layanan,
                ],
            ]);
        } else {
            session([
                'alur_pemesanan.reward' => null,
            ]);
        }

        return redirect()->route('pemesanan.alur.ringkasan');
    }

    /**
     * Menampilkan ringkasan pesanan sebelum pembayaran.
     */
    public function alurRingkasan()
    {
        $pelanggan = session('alur_pemesanan.pelanggan');
        $detailLayanan = session('alur_pemesanan.detail_layanan');
        $pengambilan = session('alur_pemesanan.pengambilan');
        $reward = session('alur_pemesanan.reward');

        if (!$pelanggan) {
            return redirect()->route('pemesanan.alur.pelanggan');
        }

        if (empty($detailLayanan)) {
            return redirect()->route('pemesanan.alur.layanan');
        }

        if (!$pengambilan) {
            return redirect()->route('pemesanan.alur.pengambilan');
        }

        $subtotal = 0;
        $rewardSudahDiterapkan = false;

        foreach ($detailLayanan as &$detail) {
            $detail['subtotal'] =
                (float) $detail['harga'] *
                (float) $detail['berat_jumlah'];

            $detail['pakai_reward'] = false;

            if (
                $reward &&
                !$rewardSudahDiterapkan &&
                (int) $detail['id_layanan'] === (int) $reward['id_layanan']
            ) {
                $detail['subtotal'] = 0;
                $detail['pakai_reward'] = true;
                $rewardSudahDiterapkan = true;
            }

            $subtotal += $detail['subtotal'];
        }

        unset($detail);

        $ongkir = (float) ($pengambilan['ongkir'] ?? 0);
        $totalHarga = $subtotal + $ongkir;

        return view(
            'pemesanan.alur.ringkasan',
            compact(
                'pelanggan',
                'detailLayanan',
                'pengambilan',
                'reward',
                'subtotal',
                'ongkir',
                'totalHarga'
            )
        );
    }

    /**
     * Mengonfirmasi dan menyimpan pesanan ke database.
     */
    public function konfirmasiAlur()
    {
        $pelangganData = session('alur_pemesanan.pelanggan');
        $detailLayanan = session('alur_pemesanan.detail_layanan');
        $pengambilanData = session('alur_pemesanan.pengambilan');
        $rewardData = session('alur_pemesanan.reward');

        if (!$pelangganData) {
            return redirect()->route('pemesanan.alur.pelanggan');
        }

        if (empty($detailLayanan)) {
            return redirect()->route('pemesanan.alur.layanan');
        }

        if (!$pengambilanData) {
            return redirect()->route('pemesanan.alur.pengambilan');
        }

        $pemesanan = DB::transaction(function () use (
            $pelangganData,
            $detailLayanan,
            $pengambilanData,
            $rewardData
        ) {
            if (!empty($pelangganData['id_pelanggan'])) {
                $pelanggan = Pelanggan::where(
                    'id_pelanggan',
                    $pelangganData['id_pelanggan']
                )->lockForUpdate()->firstOrFail();
            } else {
                $pelanggan = new Pelanggan();
                $pelanggan->nama = $pelangganData['nama'];
                $pelanggan->no_hp = $pelangganData['no_hp'];
                $pelanggan->alamat = $pelangganData['alamat'];
                $pelanggan->stempel = 0;
                $pelanggan->save();
            }

            $pengambilan = Pengambilan::findOrFail(
                $pengambilanData['id_pengambilan']
            );

            $reward = null;

            if ($rewardData) {
                $reward = Reward::findOrFail($rewardData['id_reward']);

                $layananCocok = collect($detailLayanan)->contains(
                    fn ($detail) =>
                        (int) $detail['id_layanan'] ===
                        (int) $reward->id_layanan
                );

                if (!$layananCocok) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'reward' => 'Reward tidak sesuai dengan layanan pesanan.',
                    ]);
                }

                if ((int) $pelanggan->stempel < (int) $reward->minimal_stempel) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'reward' => 'Stempel pelanggan tidak mencukupi untuk reward.',
                    ]);
                }
            }

            $layananPertama = Layanan::findOrFail(
                $detailLayanan[0]['id_layanan']
            );

            $subtotal = 0;
            $totalBeratJumlah = 0;
            $rewardSudahDiterapkan = false;

            foreach ($detailLayanan as $detail) {
                $layanan = Layanan::findOrFail($detail['id_layanan']);

                $beratJumlah = (float) $detail['berat_jumlah'];
                $harga = (float) $layanan->harga;
                $subtotalLayanan = $harga * $beratJumlah;

                $pakaiReward = $reward &&
                    !$rewardSudahDiterapkan &&
                    (int) $reward->id_layanan === (int) $layanan->id_layanan;

                if ($pakaiReward) {
                    $subtotalLayanan = 0;
                    $rewardSudahDiterapkan = true;
                }

                $subtotal += $subtotalLayanan;
                $totalBeratJumlah += $beratJumlah;
            }

            $ongkir = (float) $pengambilan->ongkir;

            $pemesanan = Pemesanan::create([
                'id_user' => auth()->id(),
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_layanan' => $layananPertama->id_layanan,
                'id_pengambilan' => $pengambilan->id_pengambilan,
                'id_reward' => null,
                'berat_jumlah' => $totalBeratJumlah,
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total_harga' => $subtotal + $ongkir,
                'tanggal' => now()->toDateString(),
            ]);

            $rewardSudahDiterapkan = false;

            foreach ($detailLayanan as $detail) {
                $layanan = Layanan::findOrFail($detail['id_layanan']);

                $beratJumlah = (float) $detail['berat_jumlah'];
                $harga = (float) $layanan->harga;
                $subtotalLayanan = $harga * $beratJumlah;

                $pakaiReward = $reward &&
                    !$rewardSudahDiterapkan &&
                    (int) $reward->id_layanan === (int) $layanan->id_layanan;

                if ($pakaiReward) {
                    $subtotalLayanan = 0;
                    $rewardSudahDiterapkan = true;
                }

                DetailPemesanan::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_layanan' => $layanan->id_layanan,
                    'id_reward' => $pakaiReward ? $reward->id_reward : null,
                    'berat_jumlah' => $beratJumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotalLayanan,
                    'pakai_reward' => (bool) $pakaiReward,
                ]);
            }

            return $pemesanan;
        });

        session()->forget('alur_pemesanan');

        return redirect()
            ->route('pembayaran.create', $pemesanan->id_pemesanan)
            ->with(
                'success',
                'Pesanan berhasil dikonfirmasi. Silakan lakukan pembayaran.'
            );
    }

    /**
     * Menampilkan halaman daftar pemesanan.
     */
    public function index()
    {
        $pemesanans = Pemesanan::with([
            'pelanggan',
            'layanan',
            'pengambilan',
            'reward',
            'detailPemesanan.layanan',
            'pembayaran',
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

        return view(
            'pemesanan.create',
            compact('pelanggans', 'layanans', 'pengambilans', 'rewards')
        );
    }

    /**
     * Menyimpan pemesanan melalui form lama.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggans,id_pelanggan',
            'id_pengambilan' => 'required|exists:pengambilans,id_pengambilan',
            'tanggal' => 'required|date',
            'layanan' => 'required|array|min:1',
            'layanan.*.id_layanan' => 'required|exists:layanans,id_layanan',
            'layanan.*.berat_jumlah' => 'required|numeric|min:0.01',
            'layanan.*.id_reward' => 'nullable|exists:rewards,id_reward',
        ]);

        DB::transaction(function () use ($request) {
            $pelanggan = Pelanggan::findOrFail($request->id_pelanggan);

            $pengambilan = Pengambilan::findOrFail(
                $request->id_pengambilan
            );

            $layananPertama = Layanan::findOrFail(
                $request->layanan[0]['id_layanan']
            );

            $pemesanan = Pemesanan::create([
                'id_user' => auth()->id(),
                'id_pelanggan' => $request->id_pelanggan,
                'id_layanan' => $layananPertama->id_layanan,
                'id_pengambilan' => $request->id_pengambilan,
                'id_reward' => null,
                'berat_jumlah' => 0,
                'subtotal' => 0,
                'ongkir' => $pengambilan->ongkir,
                'total_harga' => 0,
                'tanggal' => $request->tanggal,
            ]);

            $subtotal = 0;
            $totalBeratJumlah = 0;

            foreach ($request->layanan as $item) {
                $layanan = Layanan::findOrFail($item['id_layanan']);
                $beratJumlah = (float) $item['berat_jumlah'];
                $harga = (float) $layanan->harga;
                $subtotalLayanan = $harga * $beratJumlah;

                $pakaiReward = false;
                $reward = null;

                if (!empty($item['id_reward'])) {
                    $reward = Reward::with('layanan')
                        ->findOrFail($item['id_reward']);

                    if (
                        $reward->id_layanan == $layanan->id_layanan &&
                        $pelanggan->stempel >= $reward->minimal_stempel
                    ) {
                        $pakaiReward = true;
                        $subtotalLayanan = 0;
                    }
                }

                DetailPemesanan::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_layanan' => $layanan->id_layanan,
                    'id_reward' => $pakaiReward ? $reward->id_reward : null,
                    'berat_jumlah' => $beratJumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotalLayanan,
                    'pakai_reward' => $pakaiReward,
                ]);

                $subtotal += $subtotalLayanan;
                $totalBeratJumlah += $beratJumlah;
            }

            $ongkir = (float) $pengambilan->ongkir;

            $pemesanan->update([
                'berat_jumlah' => $totalBeratJumlah,
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total_harga' => $subtotal + $ongkir,
            ]);
        });

        return redirect()
            ->route('pemesanan.index')
            ->with('success', 'Pemesanan berhasil disimpan.');
    }

    /**
     * Menampilkan detail pemesanan.
     */
    public function show(string $id)
    {
        $pemesanan = Pemesanan::with([
            'pelanggan',
            'pengambilan',
            'detailPemesanan.layanan',
            'pembayaran',
        ])->findOrFail($id);

        return view('pemesanan.show', compact('pemesanan'));
    }

    /**
     * Menampilkan form edit pemesanan.
     */
    public function edit(string $id)
    {
        $pemesanan = Pemesanan::with('detailPemesanan')->findOrFail($id);

        $pelanggans = Pelanggan::all();
        $layanans = Layanan::all();
        $pengambilans = Pengambilan::all();
        $rewards = Reward::with('layanan')->get();

        return view(
            'pemesanan.edit',
            compact('pemesanan', 'layanans', 'pelanggans', 'pengambilans', 'rewards')
        );
    }

    /**
     * Mengupdate pemesanan.
     */
    public function update(Request $request, string $id)
    {
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
            ->with('success', 'Pemesanan berhasil dihapus.');
    }
}
