@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="mb-4">
        <h2>Poin / Stempel</h2>

        <p class="text-muted">
            Cek jumlah stempel pelanggan sebelum memilih reward.
        </p>
    </div>

    <div class="card">
        <div class="card-body">

            <div class="mb-4">

                <h5>
                    {{ $pelanggan['nama'] }}
                </h5>

                <p class="mb-1">
                    No. HP: {{ $pelanggan['no_hp'] }}
                </p>

                <p class="mb-0">
                    Jumlah Stempel:
                    <strong>{{ $pelanggan['stempel'] }}</strong>
                </p>

            </div>

            <hr>

            <div class="mb-3">

                <strong>
                    Layanan yang dipilih:
                </strong>

                <ul class="mt-2">

                    @foreach ($detailLayanan as $layanan)

                        <li>
                            {{ $layanan['nama_layanan'] }}
                            —
                            {{ $layanan['berat_jumlah'] }}
                            {{ $layanan['satuan'] }}
                        </li>

                    @endforeach

                </ul>

            </div>

            <div class="alert alert-info">

                Setiap transaksi yang berhasil dibayar akan mendapatkan
                <strong>+1 stempel</strong>.

            </div>

            <div class="d-flex justify-content-between mt-4">

                <a
                    href="{{ route('pemesanan.alur.pengambilan') }}"
                    class="btn btn-secondary"
                >
                    ← Kembali
                </a>

                <a
                    href="{{ route('pemesanan.alur.reward') }}"
                    class="btn btn-primary"
                >
                    Lanjut
                </a>

            </div>

        </div>
    </div>

</div>

@endsection
