@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="mb-4">
        <h2>Pilih Jenis Pelayanan</h2>
        <p class="text-muted">
            Pilih satu atau lebih layanan untuk pelanggan:
            <strong>{{ $pelanggan['nama'] }}</strong>
        </p>
    </div>

    <div class="card">
        <div class="card-body">

            <form
                action="{{ route('pemesanan.alur.layanan.simpan') }}"
                method="POST"
            >

                @csrf

                <div class="row">

                    @forelse ($layanans as $layanan)

                        <div class="col-md-4 mb-3">

                            <label class="card h-100 p-3"
                                   style="cursor: pointer;">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="layanan[]"
                                        value="{{ $layanan->id_layanan }}"
                                    >

                                    <span class="form-check-label">
                                        <strong>
                                            {{ $layanan->nama_layanan }}
                                        </strong>
                                    </span>

                                </div>

                            </label>

                        </div>

                    @empty

                        <div class="col-12">
                            <div class="alert alert-warning">
                                Belum ada data layanan.
                            </div>
                        </div>

                    @endforelse

                </div>

                <div class="d-flex justify-content-between mt-4">

                    <a
                        href="{{ route('pemesanan.alur.pelanggan') }}"
                        class="btn btn-secondary"
                    >
                        ← Kembali
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Lanjut
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
