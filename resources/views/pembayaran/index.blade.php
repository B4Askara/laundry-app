@extends('layouts.app')

@section('content')

<div class="dashboard-header">
    <div>
        <h1>Transaksi</h1>
        <p>Kelola pembayaran pesanan pelanggan.</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="transaction-card">

    <div class="transaction-title">
        Daftar Transaksi
    </div>

    <div class="table-responsive">

        <table class="transaction-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($pemesanans as $pemesanan)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            <strong>
                                {{ $pemesanan->pelanggan->nama ?? '-' }}
                            </strong>
                        </td>

                        <td>
                            {{ $pemesanan->tanggal }}
                        </td>

                        <td>
                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </td>

                        <td>

                            @if($pemesanan->pembayaran)
                                <span class="status-selesai">
                                    Sudah Dibayar
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    Belum Dibayar
                                </span>
                            @endif

                        </td>

                        <td>

                            @if(!$pemesanan->pembayaran)

                                <a href="{{ route('pembayaran.create', $pemesanan->id_pemesanan) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-credit-card"></i>
                                    Bayar
                                </a>

                            @else

                                <button class="btn btn-sm btn-success" disabled>
                                    <i class="bi bi-check-lg"></i>
                                    Lunas
                                </button>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-5">

                            <div class="text-muted mb-2">
                                <i class="bi bi-receipt"
                                   style="font-size: 35px;"></i>
                            </div>

                            <div class="text-muted">
                                Belum ada transaksi.
                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection