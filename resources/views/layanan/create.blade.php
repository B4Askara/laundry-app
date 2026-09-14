@extends('layouts.app')

@section('content')

<div class="container">

    <h2 class="mb-4">Tambah Layanan</h2>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('layanan.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Layanan</label>
                    <input type="text"
                           name="nama_layanan"
                           class="form-control"
                           placeholder="Contoh: Cuci Kiloan"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Kiloan">Kiloan</option>
                        <option value="Satuan">Satuan</option>
                        <option value="Express">Express</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number"
                           name="harga"
                           class="form-control"
                           placeholder="Contoh: 7000"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <input type="text"
                           name="satuan"
                           class="form-control"
                           placeholder="Contoh: Kg"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="{{ route('layanan.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

@endsection