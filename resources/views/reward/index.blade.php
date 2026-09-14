@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">Reward Stempel</h2>

            <p class="text-muted mb-0">
                Kelola reward yang dapat ditukarkan pelanggan.
            </p>
        </div>

        <a href="{{ route('reward.create') }}"
           class="btn btn-primary">

            + Tambah Reward

        </a>

    </div>


    <!-- PESAN SUKSES -->

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    <!-- ERROR -->

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>Terjadi kesalahan:</strong>

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- TABEL REWARD -->

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                Nama Reward
                            </th>

                            <th>
                                Layanan
                            </th>

                            <th width="150">
                                Minimal Stempel
                            </th>

                            <th>
                                Keterangan
                            </th>

                            <th width="180">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($rewards as $reward)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>


                                <td>
                                    <strong>
                                        {{ $reward->nama_reward }}
                                    </strong>
                                </td>


                                <td>

                                    @if ($reward->layanan)

                                        {{ $reward->layanan->nama_layanan }}

                                    @else

                                        <span class="text-muted">
                                            Layanan tidak ditemukan
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <span class="badge bg-primary">

                                        {{ $reward->minimal_stempel }}
                                        Stempel

                                    </span>

                                </td>


                                <td>

                                    {{ $reward->keterangan ?: '-' }}

                                </td>


                                <td>

                                    <a href="{{ route('reward.edit', $reward->id_reward) }}"
                                       class="btn btn-warning btn-sm">

                                        Edit

                                    </a>


                                    <form
                                        action="{{ route('reward.destroy', $reward->id_reward) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus reward ini?')"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                        >

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center py-4"
                                >

                                    <div class="text-muted">

                                        Belum ada reward.

                                    </div>

                                    <a
                                        href="{{ route('reward.create') }}"
                                        class="btn btn-primary mt-2"
                                    >

                                        + Tambah Reward Pertama

                                    </a>

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