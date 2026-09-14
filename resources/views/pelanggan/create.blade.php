@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h2>Tambah Pelanggan</h2>

    <div class="card mt-3">
        <div class="card-body">

            <form action="{{ route('pelanggan.store') }}" method="POST">

                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan</label>
                    <input
                        type="text"
                        name="nama"
                        class="form-control"
                        value="{{ old('nama') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <input
                        type="text"
                        name="no_hp"
                        class="form-control"
                        value="{{ old('no_hp') }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea
                        name="alamat"
                        class="form-control"
                        rows="3"
                        required
                    >{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

@endsection