
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Ringkasan Pesanan</h2>
            <p class="text-muted mb-0">
                Periksa kembali pesanan sebelum melanjutkan ke pembayaran.
            </p>
        </div>
        <span class="badge bg-primary fs-6">Langkah 6</span>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- DATA PELANGGAN --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Data Pelanggan</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <small class="text-muted">Nama</small>
                    <div class="fw-semibold">
                        {{ $pelanggan['nama'] ?? '-' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Nomor HP</small>
                    <div class="fw-semibold">
                        {{ $pelanggan['no_hp'] ?? '-' }}
                    </div>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">Alamat</small>
                    <div class="fw-semibold">
                        {{ $pelanggan['alamat'] ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAIL LAYANAN --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Detail Layanan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Layanan</th>
                            <th>Harga</th>
                            <th>Berat/Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailLayanan as $detail)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold">
                                        {{ $detail['nama_layanan'] }}
                                    </div>

                                    @if (!empty($detail['pakai_reward']))
                                        <span class="badge bg-success">
                                            Reward Gratis
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    Rp {{ number_format($detail['harga'], 0, ',', '.') }}
                                </td>
                                <td>
                                    {{ rtrim(rtrim(number_format($detail['berat_jumlah'], 2, ',', '.'), '0'), ',') }}
                                    {{ $detail['satuan'] ?? '' }}
                                </td>
                                <td class="fw-semibold">
                                    Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PENGAMBILAN DAN TOTAL --}}
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Pengambilan</h5>

                    <p class="mb-2">
                        <span class="text-muted">Metode:</span>
                        <strong>{{ $pengambilan['metode'] ?? '-' }}</strong>
                    </p>

                    <p class="mb-0">
                        <span class="text-muted">Ongkir:</span>
                        <strong>
                            Rp {{ number_format($ongkir, 0, ',', '.') }}
                        </strong>
                    </p>

                    <hr>

                    @if ($reward)
                        <h6 class="fw-bold">Reward Digunakan</h6>
                        <p class="mb-1">
                            {{ $reward['nama_reward'] ?? 'Reward' }}
                        </p>
                        <small class="text-muted">
                            Potongan diberikan pada layanan yang memenuhi syarat reward.
                        </small>
                    @else
                        <p class="text-muted mb-0">
                            Tidak menggunakan reward.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Total Pembayaran</h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal layanan</span>
                        <span>
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Ongkir</span>
                        <span>
                            Rp {{ number_format($ongkir, 0, ',', '.') }}
                        </span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">Total</span>
                        <span class="fw-bold fs-4 text-primary">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TOMBOL AKSI --}}
    <div class="d-flex flex-wrap justify-content-between gap-2 mt-4">
        <button
            type="button"
            class="btn btn-outline-secondary"
            onclick="window.history.back()">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </button>

        <form
            action="{{ route('pemesanan.alur.konfirmasi') }}"
            method="POST"
            onsubmit="this.querySelector('button[type=submit]').disabled = true;
                      this.querySelector('button[type=submit]').innerText = 'Memproses...';">
            @csrf

            <button type="submit" class="btn btn-primary px-4">
                Konfirmasi & Lanjut Pembayaran
                <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </form>
    </div>
</div>
@endsection
