
@extends('layouts.app')

@section('content')

<div class="dashboard-header">
    <div>
        <h1>Pembayaran</h1>
        <p>Proses pembayaran pesanan pelanggan.</p>
    </div>
</div>

<div class="transaction-card">
    <div class="transaction-title">Detail Pembayaran</div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label fw-semibold">Pelanggan</label>
                <input type="text" class="form-control"
                    value="{{ $pemesanan->pelanggan->nama ?? '-' }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="text" class="form-control"
                    value="{{ $pemesanan->tanggal }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Total Harga</label>
                <input type="text" class="form-control fw-bold"
                    value="Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}"
                    readonly>
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Layanan</label>

            <div class="border rounded p-3">
                @foreach($pemesanan->detailPemesanan as $detail)
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <strong>{{ $detail->layanan->nama_layanan ?? '-' }}</strong>
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

    <form action="{{ route('pembayaran.store', $pemesanan->id_pemesanan) }}"
          method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Jumlah Bayar</label>

                <input type="number"
                    name="jumlah_bayar"
                    class="form-control"
                    min="{{ $pemesanan->total_harga }}"
                    step="1000"
                    value="{{ old('jumlah_bayar', $pemesanan->total_harga) }}"
                    required>

                @error('jumlah_bayar')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Metode Pembayaran</label>

                <select name="metode_pembayaran"
                    id="metode_pembayaran"
                    class="form-select"
                    required>
                    <option value="">-- Pilih Metode --</option>
                    <option value="cash"
                        {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>
                        Cash
                    </option>
                    <option value="transfer"
                        {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>
                        Transfer
                    </option>
                    <option value="qris"
                        {{ old('metode_pembayaran') == 'qris' ? 'selected' : '' }}>
                        QRIS
                    </option>
                </select>

                @error('metode_pembayaran')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- GAMBAR QRIS --}}
        <div id="qris-panel"
            class="border rounded p-4 mt-4 text-center"
            style="display: none;">

            <h5 class="fw-bold mb-2">Pembayaran QRIS</h5>
            <p class="text-muted">
                Scan kode QR berikut menggunakan aplikasi pembayaran.
            </p>

            @if(!empty($pengaturan?->qris_image))
                <img
                    src="{{ \Illuminate\Support\Facades\Storage::url($pengaturan->qris_image) }}"
                    alt="QRIS Laundry"
                    class="img-fluid rounded border p-2"
                    style="max-width: 300px; max-height: 350px; object-fit: contain;">

                <p class="small text-muted mt-3 mb-0">
                    Pastikan nominal pembayaran sesuai sebelum melakukan pembayaran.
                </p>
            @else
                <div class="alert alert-warning mb-0">
                    Gambar QRIS belum tersedia. Silakan upload gambar QRIS
                    melalui halaman Pengaturan terlebih dahulu.
                </div>
            @endif

            <div class="alert alert-info text-start mt-3 mb-0">
                <i class="bi bi-info-circle"></i>
                Pembayaran belum tercatat hanya dengan memilih QRIS.
                Klik tombol Proses Pembayaran setelah pembayaran dikonfirmasi.
                Sistem ini belum memverifikasi pembayaran QRIS secara otomatis.
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <a href="{{ route('pembayaran.index') }}"
                class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-credit-card"></i>
                Proses Pembayaran
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const metode = document.getElementById('metode_pembayaran');
    const panelQris = document.getElementById('qris-panel');

    function tampilkanQris() {
        panelQris.style.display = metode.value === 'qris' ? 'block' : 'none';
    }

    metode.addEventListener('change', tampilkanQris);
    tampilkanQris();
});
</script>

@endsection
