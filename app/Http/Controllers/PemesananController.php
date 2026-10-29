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
use Illuminate\Validation\Rule;

class PemesananController extends Controller
{
    /**
     * Mengambil ID laundry akun yang sedang login.
     */
    private function laundryId(): int
    {
        $laundryId = auth()->user()?->laundry_id;

        abort_if(!$laundryId, 403, 'Akun belum terhubung ke laundry.');

        return (int) $laundryId;
    }

    /**
     * Halaman pertama alur pemesanan.
     */
    public function alurPelanggan()
    {
        $pelanggans = Pelanggan::where('laundry_id', $this->laundryId())
            ->orderBy('nama')
            ->get();

        return view('pemesanan.alur.pelanggan', compact('pelanggans'));
    }

    /**
     * Autocomplete pelanggan milik laundry sendiri.
     */
    public function cariPelanggan(Request $request)
    {
        $nama = trim($request->query('q', ''));

        if ($nama === '') {
            return response()->json([]);
        }

        $pelanggans = Pelanggan::where('laundry_id', $this->laundryId())
            ->where('nama', 'like', '%' . $nama . '%')
            ->orderBy('nama')
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
     * Menyimpan sementara pilihan pelanggan.
     */
    public function simpanPelangganAlur(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'nullable|integer',
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        $laundryId = $this->laundryId();
        $idPelanggan = $request->input('id_pelanggan');

        if ($idPelanggan) {
            $pelanggan = Pelanggan::where('laundry_id', $laundryId)
                ->where('id_pelanggan', $idPelanggan)
                ->firstOrFail();

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
     * Halaman pilih layanan.
     */
    public function alurLayanan()
    {
        $pelanggan = session('alur_pemesanan.pelanggan');

        if (!$pelanggan) {
            return redirect()->route('pemesanan.alur.pelanggan');
        }

        $layanans = Layanan::where('laundry_id', $this->laundryId())
            ->orderBy('nama_layanan')
            ->get();

        return view('pemesanan.alur.layanan', compact('layanans', 'pelanggan'));
    }

    /**
     * Menyimpan pilihan layanan.
     */
    public function simpanLayananAlur(Request $request)
    {
        $laundryId = $this->laundryId();

        $request->validate([
            'layanan' => 'required|array|min:1',
            'layanan.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('layanans', 'id_layanan')
                    ->where('laundry_id', $laundryId),
            ],
        ]);

        $layanans = Layanan::where('laundry_id', $laundryId)
            ->whereIn('id_layanan', $request->layanan)
            ->get();

        if ($layanans->count() !== count($request->layanan)) {
            abort(422, 'Ada layanan yang tidak valid.');
        }

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

        session()->forget([
            'alur_pemesanan.detail_layanan',
            'alur_pemesanan.pengambilan',
            'alur_pemesanan.reward',
        ]);

        return redirect()->route('pemesanan.alur.detail');
    }

    /**
     * Halaman detail layanan.
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

        return view('pemesanan.alur.detail', compact('pelanggan', 'layanans'));
    }

    /**
     * Menyimpan berat atau jumlah layanan.
     */
    public function simpanDetailAlur(Request $request)
    {
        $laundryId = $this->laundryId();

        $request->validate([
            'layanan' => 'required|array|min:1',
            'layanan.*.id_layanan' => [
                'required',
                'integer',
                Rule::exists('layanans', 'id_layanan')
                    ->where('laundry_id', $laundryId),
            ],
            'layanan.*.berat_jumlah' => 'required|numeric|min:0.01',
        ]);

        $detailLayanan = [];

        foreach ($request->layanan as $item) {
            $layanan = Layanan::where('laundry_id', $laundryId)
                ->where('id_layanan', $item['id_layanan'])
                ->firstOrFail();

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

        session()->forget([
            'alur_pemesanan.pengambilan',
            'alur_pemesanan.reward',
        ]);

        return redirect()->route('pemesanan.alur.pengambilan');
    }

    /**
     * Halaman metode pengambilan.
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

        $pengambilan = Pengambilan::findOrFail($request->id_pengambilan);

        session([
            'alur_pemesanan.pengambilan' => [
                'id_pengambilan' => $pengambilan->id_pengambilan,
                'metode' => $pengambilan->metode,
                'ongkir' => $pengambilan->ongkir,
            ],
        ]);

        session()->forget('alur_pemesanan.reward');

        return redirect()->route('pemesanan.alur.poin');
    }

    /**
     * Halaman poin atau stempel.
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
     * Halaman pilih reward milik laundry sendiri.
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

        $rewards = Reward::where('laundry_id', $this->laundryId())
            ->with('layanan')
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
        $laundryId = $this->laundryId();

        $request->validate([
            'id_reward' => [
                'nullable',
                'integer',
                Rule::exists('rewards', 'id_reward')
                    ->where('laundry_id', $laundryId),
            ],
        ]);

        $idReward = $request->input('id_reward');

        if ($idReward) {
            $reward = Reward::where('laundry_id', $laundryId)
                ->with('layanan')
                ->where('id_reward', $idReward)
                ->firstOrFail();

            $pelanggan = session('alur_pemesanan.pelanggan');
            $detailLayanan = session('alur_pemesanan.detail_layanan', []);

            if (!$pelanggan || empty($detailLayanan)) {
                return redirect()->route('pemesanan.alur.pelanggan');
            }

            $layananCocok = collect($detailLayanan)->contains(
                fn ($detail) =>
                    (int) $detail['id_layanan'] === (int) $reward->id_layanan
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
            session(['alur_pemesanan.reward' => null]);
        }

        return redirect()->route('pemesanan.alur.ringkasan');
    }

    /**
     * Ringkasan pesanan sebelum pembayaran.
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
                (float) $detail['harga'] * (float) $detail['berat_jumlah'];

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
     * Konfirmasi dan simpan pesanan.
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

        $laundryId = $this->laundryId();

        $pemesanan = DB::transaction(function () use (
            $pelangganData,
            $detailLayanan,
            $pengambilanData,
            $rewardData,
            $laundryId
        ) {
            if (!empty($pelangganData['id_pelanggan'])) {
                $pelanggan = Pelanggan::where('laundry_id', $laundryId)
                    ->where('id_pelanggan', $pelangganData['id_pelanggan'])
                    ->lockForUpdate()
                    ->firstOrFail();
            } else {
                $pelanggan = Pelanggan::create([
                    'laundry_id' => $laundryId,
                    'nama' => $pelangganData['nama'],
                    'no_hp' => $pelangganData['no_hp'],
                    'alamat' => $pelangganData['alamat'],
                    'stempel' => 0,
                ]);
            }

            $pengambilan = Pengambilan::findOrFail(
                $pengambilanData['id_pengambilan']
            );

            $reward = null;

            if ($rewardData) {
                $reward = Reward::where('laundry_id', $laundryId)
                    ->where('id_reward', $rewardData['id_reward'])
                    ->firstOrFail();

                $layananCocok = collect($detailLayanan)->contains(
                    fn ($detail) =>
                        (int) $detail['id_layanan'] === (int) $reward->id_layanan
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

            $layananPertama = null;
            $subtotal = 0;
            $totalBeratJumlah = 0;
            $rewardSudahDiterapkan = false;
            $detailTersimpan = [];

            foreach ($detailLayanan as $detail) {
                $layanan = Layanan::where('laundry_id', $laundryId)
                    ->where('id_layanan', $detail['id_layanan'])
                    ->firstOrFail();

                if (!$layananPertama) {
                    $layananPertama = $layanan;
                }

                $beratJumlah = (float) $detail['berat_jumlah'];

                if ($beratJumlah <= 0) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'layanan' => 'Berat atau jumlah layanan harus lebih dari nol.',
                    ]);
                }

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

                $detailTersimpan[] = [
                    'id_layanan' => $layanan->id_layanan,
                    'id_reward' => $pakaiReward ? $reward->id_reward : null,
                    'berat_jumlah' => $beratJumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotalLayanan,
                    'pakai_reward' => (bool) $pakaiReward,
                ];
            }

            if (!$layananPertama) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'layanan' => 'Pilih minimal satu layanan.',
                ]);
            }

            if ($reward && !$rewardSudahDiterapkan) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'reward' => 'Layanan untuk reward tidak ditemukan dalam pesanan.',
                ]);
            }

            $ongkir = (float) $pengambilan->ongkir;

            $pemesanan = Pemesanan::create([
                'laundry_id' => $laundryId,
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

            foreach ($detailTersimpan as $detail) {
                $detail['id_pemesanan'] = $pemesanan->id_pemesanan;
                DetailPemesanan::create($detail);
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
     * Daftar pesanan milik laundry sendiri.
     */
    public function index()
    {
        $pemesanans = Pemesanan::where('laundry_id', $this->laundryId())
            ->with([
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
     * Form tambah pesanan.
     */
    public function create()
    {
        $laundryId = $this->laundryId();

        $pelanggans = Pelanggan::where('laundry_id', $laundryId)->get();
        $layanans = Layanan::where('laundry_id', $laundryId)->get();
        $pengambilans = Pengambilan::all();

        $rewards = Reward::where('laundry_id', $laundryId)
            ->with('layanan')
            ->get();

        return view(
            'pemesanan.create',
            compact('pelanggans', 'layanans', 'pengambilans', 'rewards')
        );
    }

    /**
     * Menyimpan pesanan melalui form lama.
     */
    public function store(Request $request)
    {
        $laundryId = $this->laundryId();

        $request->validate([
            'id_pelanggan' => [
                'required',
                'integer',
                Rule::exists('pelanggans', 'id_pelanggan')
                    ->where('laundry_id', $laundryId),
            ],
            'id_pengambilan' => 'required|exists:pengambilans,id_pengambilan',
            'tanggal' => 'required|date',
            'layanan' => 'required|array|min:1',
            'layanan.*.id_layanan' => [
                'required',
                'integer',
                Rule::exists('layanans', 'id_layanan')
                    ->where('laundry_id', $laundryId),
            ],
            'layanan.*.berat_jumlah' => 'required|numeric|min:0.01',
            'layanan.*.id_reward' => [
                'nullable',
                'integer',
                Rule::exists('rewards', 'id_reward')
                    ->where('laundry_id', $laundryId),
            ],
        ]);

        DB::transaction(function () use ($request, $laundryId) {
            $pelanggan = Pelanggan::where('laundry_id', $laundryId)
                ->where('id_pelanggan', $request->id_pelanggan)
                ->lockForUpdate()
                ->firstOrFail();

            $pengambilan = Pengambilan::findOrFail(
                $request->id_pengambilan
            );

            $subtotal = 0;
            $totalBeratJumlah = 0;
            $layananPertama = null;
            $detailTersimpan = [];
            $rewardSudahDiterapkan = false;

            foreach ($request->layanan as $item) {
                $layanan = Layanan::where('laundry_id', $laundryId)
                    ->where('id_layanan', $item['id_layanan'])
                    ->firstOrFail();

                if (!$layananPertama) {
                    $layananPertama = $layanan;
                }

                $beratJumlah = (float) $item['berat_jumlah'];
                $harga = (float) $layanan->harga;
                $subtotalLayanan = $harga * $beratJumlah;
                $reward = null;
                $pakaiReward = false;

                if (!empty($item['id_reward'])) {
                    $reward = Reward::where('laundry_id', $laundryId)
                        ->where('id_reward', $item['id_reward'])
                        ->firstOrFail();

                    if (
                        !$rewardSudahDiterapkan &&
                        (int) $reward->id_layanan === (int) $layanan->id_layanan &&
                        (int) $pelanggan->stempel >= (int) $reward->minimal_stempel
                    ) {
                        $pakaiReward = true;
                        $rewardSudahDiterapkan = true;
                        $subtotalLayanan = 0;
                    }
                }

                $subtotal += $subtotalLayanan;
                $totalBeratJumlah += $beratJumlah;

                $detailTersimpan[] = [
                    'id_layanan' => $layanan->id_layanan,
                    'id_reward' => $pakaiReward ? $reward->id_reward : null,
                    'berat_jumlah' => $beratJumlah,
                    'harga' => $harga,
                    'subtotal' => $subtotalLayanan,
                    'pakai_reward' => $pakaiReward,
                ];
            }

            $ongkir = (float) $pengambilan->ongkir;

            $pemesanan = Pemesanan::create([
                'laundry_id' => $laundryId,
                'id_user' => auth()->id(),
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'id_layanan' => $layananPertama->id_layanan,
                'id_pengambilan' => $pengambilan->id_pengambilan,
                'id_reward' => null,
                'berat_jumlah' => $totalBeratJumlah,
                'subtotal' => $subtotal,
                'ongkir' => $ongkir,
                'total_harga' => $subtotal + $ongkir,
                'tanggal' => $request->tanggal,
            ]);

            foreach ($detailTersimpan as $detail) {
                $detail['id_pemesanan'] = $pemesanan->id_pemesanan;
                DetailPemesanan::create($detail);
            }
        });

        return redirect()
            ->route('pemesanan.index')
            ->with('success', 'Pemesanan berhasil disimpan.');
    }

    /**
     * Detail pesanan. Pesanan laundry lain menghasilkan 404.
     */
    public function show(string $id)
    {
        $pemesanan = Pemesanan::where('laundry_id', $this->laundryId())
            ->with([
                'pelanggan',
                'layanan',
                'reward',
                'pengambilan',
                'detailPemesanan.layanan',
                'pembayaran',
            ])
            ->where('id_pemesanan', $id)
            ->firstOrFail();

        return view('pemesanan.show', compact('pemesanan'));
    }

    /**
     * Form edit pesanan.
     */
    public function edit(string $id)
    {
        $laundryId = $this->laundryId();

        $pemesanan = Pemesanan::where('laundry_id', $laundryId)
            ->with('detailPemesanan')
            ->where('id_pemesanan', $id)
            ->firstOrFail();

        $pelanggans = Pelanggan::where('laundry_id', $laundryId)->get();
        $layanans = Layanan::where('laundry_id', $laundryId)->get();
        $pengambilans = Pengambilan::all();

        $rewards = Reward::where('laundry_id', $laundryId)
            ->with('layanan')
            ->get();

        return view(
            'pemesanan.edit',
            compact('pemesanan', 'layanans', 'pelanggans', 'pengambilans', 'rewards')
        );
    }

    /**
     * Fitur edit lama belum diimplementasikan.
     */
    public function update(Request $request, string $id)
    {
        Pemesanan::where('laundry_id', $this->laundryId())
            ->where('id_pemesanan', $id)
            ->firstOrFail();

        return redirect()
            ->route('pemesanan.index')
            ->with(
                'success',
                'Fitur edit akan disesuaikan dengan detail pemesanan.'
            );
    }

    /**
     * Menghapus pesanan milik laundry sendiri.
     */
    public function destroy(string $id)
    {
        $pemesanan = Pemesanan::where('laundry_id', $this->laundryId())
            ->where('id_pemesanan', $id)
            ->firstOrFail();

        $pemesanan->delete();

        return redirect()
            ->route('pemesanan.index')
            ->with('success', 'Pemesanan berhasil dihapus.');
    }
}
