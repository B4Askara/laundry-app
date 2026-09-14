@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Pemesanan</h2>

        <a href="{{ route('pemesanan.create') }}" class="btn btn-primary">
            + Tambah Pemesanan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Berat/Jumlah</th>
                            <th>Total Harga</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pemesanans as $pemesanan)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $pemesanan->pelanggan->nama ?? '-' }}
                                </td>

                                <td>
                                    {{ $pemesanan->layanan->nama_layanan ?? '-' }}
                                </td>

                                <td>
                                    {{ $pemesanan->berat_jumlah }}
                                </td>

                                <td>
                                    Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                </td>

                                <td>
                                    {{ $pemesanan->tanggal }}
                                </td>

                                <td>

                                    <a href="{{ route('pemesanan.show', $pemesanan->id_pemesanan) }}"
                                       class="btn btn-info btn-sm">
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="text-center">
                                    Belum ada data pemesanan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

@endsection