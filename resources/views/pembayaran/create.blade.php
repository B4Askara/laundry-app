@extends('layouts.app')

@section('content')

<div class="dashboard-header">
    <div>
        <h1>Pembayaran</h1>
        <p>Proses pembayaran pesanan pelanggan.</p>
    </div>
</div>

<div class="transaction-card">

    <div class="transaction-title">
        Detail Pembayaran
    </div>

    <div class="row g-4">

        {{-- INFORMASI PESANAN --}}
        <div class="col-md-6">

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Pelanggan
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $pemesanan->pelanggan->nama ?? '-' }}"
                    readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Tanggal
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $pemesanan->tanggal }}"
                    readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    Total Harga
                </label>

                <input
                    type="text"
                    class="form-control fw-bold"
                    value="Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}"
                    readonly>
            </div>

        </div>

        {{-- DETAIL LAYANAN --}}
        <div class="col-md-6">

            <label class="form-label fw-semibold">
                Layanan
            </label>

            <div class="border rounded p-3">

                @foreach($pemesanan->detailPemesanan as $detail)

                    <div class="d-flex justify-content-between mb-2">

                        <div>
                            <strong>
                                {{ $detail->layanan->nama_layanan ?? '-' }}
                            </strong>

                            <br>

                            <small class="text-muted">
                                {{ $detail->berat_jumlah }}
                                {{ $detail->layanan->satuan ?? '' }}
                            </small>

                            @if($detail->pakai_reward)
                                <br>
                                <small class="text-success">
                                    <i class="bi bi-gift-fill"></i>
                                    Menggunakan Reward
                                </small>
                            @endif
                        </div>

                        <div class="fw-semibold">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </div>

                    </div>

                @endforeach

                <hr>

                <div class="d-flex justify-content-between">
                    <span>Ongkir</span>

                    <strong>
                        Rp {{ number_format($pemesanan->ongkir, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <span class="fw-bold">Total</span>

                    <strong class="text-primary">
                        Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                    </strong>
                </div>

            </div>

        </div>

    </div>

    <hr class="my-4">

    {{-- FORM PEMBAYARAN --}}
    <form
        action="{{ route('pembayaran.store', $pemesanan->id_pemesanan) }}"
        method="POST">

        @csrf

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Jumlah Bayar
                </label>

                <input
                    type="number"
                    name="jumlah_bayar"
                    class="form-control"
                    min="{{ $pemesanan->total_harga }}"
                    step="1000"
                    value="{{ $pemesanan->total_harga }}"
                    required>

                @error('jumlah_bayar')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Metode Pembayaran
                </label>

                <select
                    name="metode_pembayaran"
                    class="form-select"
                    required>

                    <option value="">-- Pilih Metode --</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="qris">QRIS</option>

                </select>

                @error('metode_pembayaran')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>

        <div class="mt-4 d-flex gap-2">

            <a
                href="{{ route('pembayaran.index') }}"
                class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Kembali

            </a>

            <button
                type="submit"
                class="btn btn-primary">

                <i class="bi bi-credit-card"></i>
                Proses Pembayaran

            </button>

        </div>

    </form>

</div>

@endsection