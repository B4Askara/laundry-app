@extends('layouts.app')

@section('content')

<div class="container">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">Edit Reward</h2>

            <p class="text-muted mb-0">
                Ubah informasi reward untuk pelanggan.
            </p>
        </div>

        <a href="{{ route('reward.index') }}"
           class="btn btn-secondary">
            Kembali
        </a>

    </div>


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


    <!-- FORM -->
    <div class="card">

        <div class="card-body">

            <form
                action="{{ route('reward.update', $reward->id_reward) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <!-- LAYANAN -->
                <div class="mb-3">

                    <label class="form-label">
                        Layanan
                    </label>

                    <select
                        name="id_layanan"
                        class="form-control"
                        required
                    >

                        <option value="">
                            -- Pilih Layanan --
                        </option>

                        @foreach ($layanans as $layanan)

                            <option
                                value="{{ $layanan->id_layanan }}"
                                {{ old('id_layanan', $reward->id_layanan) == $layanan->id_layanan ? 'selected' : '' }}
                            >

                                {{ $layanan->nama_layanan }}
                                - Rp
                                {{ number_format($layanan->harga, 0, ',', '.') }}
                                / {{ $layanan->satuan }}

                            </option>

                        @endforeach

                    </select>

                    <small class="text-muted">
                        Pilih layanan yang akan diberikan secara gratis
                        ketika reward ditukarkan.
                    </small>

                </div>


                <!-- NAMA REWARD -->
                <div class="mb-3">

                    <label class="form-label">
                        Nama Reward
                    </label>

                    <input
                        type="text"
                        name="nama_reward"
                        class="form-control"
                        value="{{ old('nama_reward', $reward->nama_reward) }}"
                        placeholder="Contoh: Gratis Cuci Kiloan"
                        required
                    >

                </div>


                <!-- MINIMAL STEMPEL -->
                <div class="mb-3">

                    <label class="form-label">
                        Minimal Stempel
                    </label>

                    <input
                        type="number"
                        name="minimal_stempel"
                        class="form-control"
                        value="{{ old('minimal_stempel', $reward->minimal_stempel) }}"
                        min="1"
                        placeholder="Contoh: 10"
                        required
                    >

                    <small class="text-muted">
                        Jumlah stempel yang dibutuhkan pelanggan
                        untuk menggunakan reward.
                    </small>

                </div>


                <!-- KETERANGAN -->
                <div class="mb-3">

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        class="form-control"
                        rows="3"
                        placeholder="Contoh: Gratis 1x Cuci Kiloan"
                    >{{ old('keterangan', $reward->keterangan) }}</textarea>

                </div>


                <!-- BUTTON -->
                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Simpan Perubahan
                    </button>

                    <a
                        href="{{ route('reward.index') }}"
                        class="btn btn-secondary"
                    >
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection