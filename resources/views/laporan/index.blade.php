
@extends('layouts.app')

@section('content')

@php
    $namaBulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    $metode = [
        'cash' => 'Cash',
        'transfer' => 'Transfer',
        'qris' => 'QRIS',
    ];

    $formatRupiah = function ($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    };
@endphp

<div class="container-fluid laporan-page">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Laporan Transaksi</h4>
            <p class="text-muted mb-0">
                Rekap pendapatan dan transaksi laundry berdasarkan periode.
            </p>
        </div>

        <div class="text-muted small">
            <i class="bi bi-calendar3 me-1"></i>
            {{ $namaBulan[$bulan] }} {{ $tahun }}
        </div>
    </div>

    {{-- FILTER PERIODE --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('laporan.index') }}" method="GET">
                <div class="row align-items-end g-3">

                    <div class="col-md-4">
                        <label for="bulan" class="form-label fw-semibold">
                            Pilih Bulan
                        </label>

                        <select name="bulan" id="bulan" class="form-select">
                            @foreach ($namaBulan as $nomor => $nama)
                                <option value="{{ $nomor }}"
                                    {{ (int) $bulan === $nomor ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="tahun" class="form-label fw-semibold">
                            Pilih Tahun
                        </label>

                        <select name="tahun" id="tahun" class="form-select">
                            @for ($y = now()->year - 3; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}"
                                    {{ (int) $tahun === $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>
                            Tampilkan Laporan
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- RINGKASAN --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-2">Total Pendapatan</p>
                            <h3 class="fw-bold mb-0">
                                {{ $formatRupiah($totalPendapatan) }}
                            </h3>
                        </div>

                        <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-wallet2 fs-3 text-primary"></i>
                        </div>
                    </div>

                    <small class="text-muted d-block mt-3">
                        Pendapatan dari transaksi yang sudah dibayar.
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-2">Total Transaksi Berhasil</p>
                            <h3 class="fw-bold mb-0">
                                {{ number_format($totalTransaksi, 0, ',', '.') }}
                                <small class="fs-6 fw-normal text-muted">Transaksi</small>
                            </h3>
                        </div>

                        <div class="rounded-3 bg-success bg-opacity-10 p-3">
                            <i class="bi bi-receipt fs-3 text-success"></i>
                        </div>
                    </div>

                    <small class="text-muted d-block mt-3">
                        Jumlah transaksi yang telah memiliki pembayaran.
                    </small>
                </div>
            </div>
        </div>

    </div>

    {{-- REKAP METODE PEMBAYARAN --}}
    <div class="mb-4">
        <h5 class="fw-bold mb-3">Metode Pembayaran</h5>

        <div class="row g-3">

            @foreach ($metode as $key => $nama)
                <div class="col-xl-4 col-md-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-2">{{ $nama }}</p>

                                    <h4 class="fw-bold mb-0">
                                        {{ number_format($rekapMetode->get($key)?->jumlah_transaksi ?? 0, 0, ',', '.') }}
                                        <small class="fs-6 fw-normal text-muted">Transaksi</small>
                                    </h4>
                                </div>

                                @if ($key === 'cash')
                                    <i class="bi bi-cash-stack fs-2 text-success"></i>
                                @elseif ($key === 'transfer')
                                    <i class="bi bi-bank fs-2 text-primary"></i>
                                @else
                                    <i class="bi bi-qr-code-scan fs-2 text-info"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- TABEL DETAIL TRANSAKSI --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Detail Transaksi</h5>
                    <p class="text-muted small mb-0">
                        Daftar transaksi bulan {{ $namaBulan[$bulan] }} {{ $tahun }}.
                    </p>
                </div>

                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                    {{ $totalTransaksi }} Transaksi
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Metode</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($transaksis as $index => $transaksi)
                            @php
                                $pemesanan = $transaksi->pemesanan;

                                $daftarLayanan = $pemesanan?->detailPemesanan
                                    ?->map(function ($detail) {
                                        return $detail->layanan?->nama_layanan;
                                    })
                                    ->filter()
                                    ->unique()
                                    ->implode(', ');

                                if (empty($daftarLayanan)) {
                                    $daftarLayanan = $pemesanan?->layanan?->nama_layanan ?? '-';
                                }
                            @endphp

                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    {{ $transaksi->tanggal_pembayaran
                                        ? \Carbon\Carbon::parse($transaksi->tanggal_pembayaran)->format('d/m/Y H:i')
                                        : '-' }}
                                </td>

                                <td>
                                    <span class="fw-semibold">
                                        {{ $pemesanan?->pelanggan?->nama ?? 'Pelanggan tidak ditemukan' }}
                                    </span>
                                </td>

                                <td>{{ $daftarLayanan }}</td>

                                <td>
                                    @php
                                        $warnaMetode = [
                                            'cash' => 'success',
                                            'transfer' => 'primary',
                                            'qris' => 'info',
                                        ];
                                    @endphp

                                    <span class="badge bg-{{ $warnaMetode[$transaksi->metode_pembayaran] ?? 'secondary' }} bg-opacity-10 text-{{ $warnaMetode[$transaksi->metode_pembayaran] ?? 'secondary' }}">
                                        {{ strtoupper($transaksi->metode_pembayaran) }}
                                    </span>
                                </td>

                                <td class="text-end fw-semibold">
                                    {{ $formatRupiah($pemesanan?->total_harga ?? 0) }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>

                                    <h6 class="fw-semibold mt-3">
                                        Belum Ada Transaksi
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Tidak ada transaksi berhasil pada
                                        {{ $namaBulan[$bulan] }} {{ $tahun }}.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    @if ($transaksis->isNotEmpty())
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">
                                    Total Pendapatan
                                </th>

                                <th class="text-end text-primary">
                                    {{ $formatRupiah($totalPendapatan) }}
                                </th>
                            </tr>
                        </tfoot>
                    @endif

                </table>
            </div>

        </div>
    </div>

</div>

@endsection
