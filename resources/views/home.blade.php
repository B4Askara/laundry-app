@extends('layouts.app')

@section('content')

<div class="dashboard-header">

    <div>
        <h1>Selamat Datang 👋</h1>
        <p>Berikut ringkasan hari ini.</p>
    </div>

    <a href="{{ route('pemesanan.alur.pelanggan') }}" class="btn-new-order">
        <i class="bi bi-plus-lg"></i>
        Pemesanan baru
    </a>

</div>


{{-- STATISTIK --}}
<div class="dashboard-stats">

    <div class="stat-card">
        <div class="stat-number stat-green">
            28
        </div>

        <div class="stat-title">
            Total Transaksi
        </div>

        <div class="stat-subtitle">
            Hari ini
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-number stat-orange">
            Rp. 2.350.000
        </div>

        <div class="stat-title">
            Total Pemasukkan
        </div>

        <div class="stat-subtitle">
            Hari ini
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-number stat-blue">
            {{ $totalPelanggan }}
        </div>

        <div class="stat-title">
            Total Pelanggan
        </div>

        <div class="stat-subtitle">
            Terdaftar
        </div>
    </div>

</div>


{{-- RIWAYAT TRANSAKSI --}}
<div class="transaction-card">

    <div class="transaction-title">
        Riwayat Transaksi Terbaru
    </div>

    <div class="table-responsive">

        <table class="transaction-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Layanan</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                @forelse($transaksiTerbaru as $pemesanan)

                    <tr>

                        {{-- NO --}}
                        <td>
                            {{ $loop->iteration }}
                        </td>


                        {{-- NAMA PELANGGAN --}}
                        <td>
                            <strong>
                                {{ $pemesanan->pelanggan->nama ?? '-' }}
                            </strong>
                        </td>


                        {{-- LAYANAN --}}
                        <td>

                            @if($pemesanan->detailPemesanan->count() > 0)

                                {{ $pemesanan->detailPemesanan->first()->layanan->nama_layanan ?? '-' }}

                                @if($pemesanan->detailPemesanan->count() > 1)
                                    <small class="text-muted">
                                        + {{ $pemesanan->detailPemesanan->count() - 1 }} layanan
                                    </small>
                                @endif

                            @else
                                -
                            @endif

                        </td>


                        {{-- TOTAL --}}
                        <td>
                            Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if($pemesanan->pembayaran)

                                <span class="status-selesai">
                                    Selesai
                                </span>

                            @else

                                <span class="badge bg-warning text-dark">
                                    Belum Dibayar
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Belum ada transaksi.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection