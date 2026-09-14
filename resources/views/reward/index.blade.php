@extends('layouts.app')

@section('content')

<div class="dashboard-header">
    <div>
        <h1>Reward Stempel</h1>
        <p>Kelola reward yang dapat ditukarkan oleh pelanggan.</p>
    </div>

    <a href="{{ route('reward.create') }}" class="btn-new-order">
        <i class="bi bi-plus-lg"></i>
        Tambah Reward
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="transaction-card">

    <div class="transaction-title">
        Daftar Reward
    </div>

    <div class="table-responsive">

        <table class="transaction-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Reward</th>
                    <th>Layanan</th>
                    <th>Minimal Stempel</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($rewards as $reward)

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
                            {{ $reward->layanan->nama_layanan ?? '-' }}
                        </td>

                        <td>
                            <span class="reward-stamp-badge">
                                <i class="bi bi-ticket-perforated-fill"></i>
                                {{ $reward->minimal_stempel }} Stempel
                            </span>
                        </td>

                        <td>
                            {{ $reward->keterangan ?: '-' }}
                        </td>

                        <td>

                            <a href="{{ route('reward.edit', $reward->id_reward) }}"
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-fill"></i>
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
                                    class="btn btn-sm btn-danger"
                                >
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-5">

                            <div class="text-muted mb-2">
                                <i class="bi bi-ticket-perforated"
                                   style="font-size: 35px;"></i>
                            </div>

                            <div class="text-muted">
                                Belum ada reward.
                            </div>

                            <a href="{{ route('reward.create') }}"
                               class="btn btn-primary mt-3">
                                <i class="bi bi-plus-lg"></i>
                                Tambah Reward Pertama
                            </a>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection