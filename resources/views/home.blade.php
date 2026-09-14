@extends('layouts.app')

@section('content')

<div class="dashboard-header">

    <div>
        <h1>Selamat Datang 👋</h1>
        <p>Berikut ringkasan hari ini.</p>
    </div>

    <a href="{{ route('pemesanan.create') }}" class="btn-new-order">
        <i class="bi bi-plus-lg"></i>
        Pemesanan baru
    </a>

</div>


{{-- STATISTIK --}}
<div class="dashboard-stats">

    <div class="stat-card">
        <div class="stat-number stat-green">
            28
        </div>

        <div class="stat-title">
            Total Transaksi
        </div>

        <div class="stat-subtitle">
            Hari ini
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-number stat-orange">
            Rp. 2.350.000
        </div>

        <div class="stat-title">
            Total Pemasukkan
        </div>

        <div class="stat-subtitle">
            Hari ini
        </div>
    </div>


    <div class="stat-card">
        <div class="stat-number stat-blue">
            18
        </div>

        <div class="stat-title">
            Total Pelanggan
        </div>

        <div class="stat-subtitle">
            Hari ini
        </div>
    </div>

</div>


{{-- RIWAYAT TRANSAKSI --}}
<div class="transaction-card">

    <div class="transaction-title">
        Riwayat Transaksi Terbaru
    </div>

    <div class="table-responsive">

        <table class="transaction-table">

            <thead>
                <tr>
                    <th>No</th>
                    <th>NamaPelanggan</th>
                    <th>Layanan</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>1</td>
                    <td>Iqbal Sanjaya</td>
                    <td>Cuci Kiloan</td>
                    <td>Rp20.000</td>
                    <td>
                        <span class="status-selesai">
                            Selesai
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>1</td>
                    <td>Iqbal Sanjaya</td>
                    <td>Cuci Kiloan</td>
                    <td>Rp20.000</td>
                    <td>
                        <span class="status-selesai">
                            Selesai
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>1</td>
                    <td>Iqbal Sanjaya</td>
                    <td>Cuci Kiloan</td>
                    <td>Rp20.000</td>
                    <td>
                        <span class="status-selesai">
                            Selesai
                        </span>
                    </td>
                </tr>

                <tr>
                    <td>1</td>
                    <td>Iqbal Sanjaya</td>
                    <td>Cuci Kiloan</td>
                    <td>Rp20.000</td>
                    <td>
                        <span class="status-selesai">
                            Selesai
                        </span>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection