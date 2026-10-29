
@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Pengaturan</h3>
        <p class="text-muted mb-0">
            Atur identitas laundry dan informasi pembayaran.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Pengaturan gagal disimpan.</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengaturan.update') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-shop me-2"></i>
                    Identitas Laundry
                </h5>

                <p class="text-muted small mb-4">
                    Informasi dasar usaha laundry.
                </p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nama Laundry
                    </label>

                    <input type="text"
                           name="nama_laundry"
                           class="form-control"
                           value="{{ old('nama_laundry', $pengaturan->nama_laundry) }}"
                           placeholder="Contoh: Laundry Bersih">
                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-bank me-2"></i>
                    Informasi Transfer Bank
                </h5>

                <p class="text-muted small mb-4">
                    Isi rekening tujuan pembayaran milik laundry.
                </p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nama Bank
                    </label>

                    <input type="text"
                           name="nama_bank"
                           class="form-control"
                           value="{{ old('nama_bank', $pengaturan->nama_bank) }}"
                           placeholder="Contoh: BCA, BRI, BNI, Mandiri">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Nomor Rekening
                    </label>

                    <input type="text"
                           name="nomor_rekening"
                           class="form-control"
                           value="{{ old('nomor_rekening', $pengaturan->nomor_rekening) }}"
                           placeholder="Masukkan nomor rekening">
                </div>

                <div class="mb-0">
                    <label class="form-label fw-semibold">
                        Nama Pemilik Rekening
                    </label>

                    <input type="text"
                           name="nama_pemilik_rekening"
                           class="form-control"
                           value="{{ old('nama_pemilik_rekening', $pengaturan->nama_pemilik_rekening) }}"
                           placeholder="Nama sesuai rekening">
                </div>

            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-qr-code me-2"></i>
                    QRIS
                </h5>

                <p class="text-muted small mb-4">
                    Upload gambar QRIS milik laundry.
                    Format JPG, PNG, atau WEBP, maksimal 2 MB.
                </p>

                @if ($pengaturan->qris_image)
                    <div class="mb-3">
                        <p class="fw-semibold mb-2">QRIS saat ini</p>

                        <img
                            src="{{ asset('storage/' . $pengaturan->qris_image) }}"
                            alt="QRIS Laundry"
                            style="max-width: 260px; max-height: 300px; object-fit: contain;"
                            class="img-thumbnail"
                        >
                    </div>
                @endif

                <div class="mb-0">
                    <label class="form-label fw-semibold">
                        Ganti atau Upload QRIS
                    </label>

                    <input type="file"
                           name="qris_image"
                           class="form-control"
                           accept=".jpg,.jpeg,.png,.webp">

                    <small class="text-muted">
                        Kosongkan jika tidak ingin mengganti QRIS yang tersimpan.
                    </small>
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-floppy me-2"></i>
                Simpan Pengaturan
            </button>
        </div>

    </form>
</div>
@endsection
