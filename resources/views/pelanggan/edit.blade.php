@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Edit Pelanggan</h4>
        </div>

        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Pelanggan</label>
                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        class="form-control"
                        value="{{ old('nama', $pelanggan->nama) }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="no_hp" class="form-label">No. HP</label>
                    <input
                        type="text"
                        name="no_hp"
                        id="no_hp"
                        class="form-control"
                        value="{{ old('no_hp', $pelanggan->no_hp) }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea
                        name="alamat"
                        id="alamat"
                        class="form-control"
                        rows="3"
                        required
                    >{{ old('alamat', $pelanggan->alamat) }}</textarea>
                </div>

                <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary">
                    Kembali
                </a>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </form>

        </div>
    </div>
</div>
@endsection