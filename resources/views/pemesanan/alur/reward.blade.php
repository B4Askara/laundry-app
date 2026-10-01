@extends('layouts.app')

@section('content')

<div class="container py-4">

<div class="mb-4">
    <h2>Pilih Reward</h2>

    <p class="text-muted">
        Pilih reward yang ingin digunakan oleh pelanggan:
        <strong>{{ $pelanggan['nama'] }}</strong>
    </p>
</div>

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body">

        <div class="mb-4">

            <h5>Stempel Pelanggan</h5>

            <p class="mb-0">
                Jumlah stempel:
                <strong>{{ $pelanggan['stempel'] }}</strong>
            </p>

        </div>

        <hr>

        <form
            action="{{ route('pemesanan.alur.reward.simpan') }}"
            method="POST"
        >

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Reward
                </label>

                <select
                    name="id_reward"
                    class="form-select"
                >

                    <option value="">
                        Tidak menggunakan reward
                    </option>

                    @forelse ($rewards as $reward)

                        @php
                            $layananCocok = false;

                            foreach ($detailLayanan as $layanan) {
                                if ($layanan['id_layanan'] == $reward->id_layanan) {
                                    $layananCocok = true;
                                    break;
                                }
                            }

                            $stempelCukup =
                                $pelanggan['stempel'] >= $reward->minimal_stempel;
                        @endphp

                        <option
                            value="{{ $reward->id_reward }}"
                            @disabled(!$layananCocok || !$stempelCukup)
                        >
                            {{ $reward->nama_reward }}
                            - {{ $reward->minimal_stempel }} stempel

                            @if (!$layananCocok)
                                (Layanan tidak dipilih)
                            @elseif (!$stempelCukup)
                                (Stempel belum cukup)
                            @endif
                        </option>

                    @empty

                        <option value="" disabled>
                            Belum ada reward
                        </option>

                    @endforelse

                </select>

            </div>

            <div class="alert alert-info">
                Reward hanya bisa digunakan jika stempel pelanggan
                mencukupi dan layanan reward termasuk dalam pesanan.
            </div>

            <div class="d-flex justify-content-between mt-4">

                <a
                    href="{{ route('pemesanan.alur.poin') }}"
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
