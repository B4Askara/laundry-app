@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Layanan</h2>

        <a href="{{ route('layanan.create') }}" class="btn btn-primary">
            + Tambah Layanan
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
                            <th>Nama Layanan</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Satuan</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($layanans as $layanan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $layanan->nama_layanan }}</td>

                                <td>{{ ucfirst($layanan->kategori) }}</td>

                                <td>
                                    Rp {{ number_format($layanan->harga, 0, ',', '.') }}
                                </td>

                                <td>{{ $layanan->satuan }}</td>

                                <td>
                                    <a href="{{ route('layanan.show', $layanan->id_layanan) }}"
                                       class="btn btn-info btn-sm">
                                        Detail
                                    </a>

                                    <a href="{{ route('layanan.edit', $layanan->id_layanan) }}"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('layanan.destroy', $layanan->id_layanan) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    Belum ada data layanan.
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