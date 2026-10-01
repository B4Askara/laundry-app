@extends('layouts.app')

@section('content')

<div class="container py-4">

```
<div class="mb-4">
    <h2>Detail Layanan</h2>

    <p class="text-muted">
        Masukkan berat atau jumlah untuk setiap layanan pelanggan:
        <strong>{{ $pelanggan['nama'] }}</strong>
    </p>
</div>

<div class="card">
    <div class="card-body">

        <form
            action="{{ route('pemesanan.alur.detail.simpan') }}"
            method="POST"
        >
            @csrf

            @foreach ($layanans as $index => $layanan)

                <div class="card mb-3 p-3">

                    <div class="row align-items-end">

                        {{-- LAYANAN --}}
                        <div class="col-md-5">

                            <label class="form-label">
                                Layanan
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                value="{{ $layanan['nama_layanan'] }}"
                                readonly
                            >

                            <input
                                type="hidden"
                                name="layanan[{{ $index }}][id_layanan]"
                                value="{{ $layanan['id_layanan'] }}"
                            >

                        </div>


                        {{-- HARGA --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Harga
                            </label>

                            <input
                                type="text"
                                class="form-control harga"
                                value="Rp {{ number_format($layanan['harga'], 0, ',', '.') }}"
                                data-harga="{{ $layanan['harga'] }}"
                                readonly
                            >

                        </div>


                        {{-- BERAT / JUMLAH --}}
                        <div class="col-md-2">

                            <label class="form-label">
                                {{ $layanan['satuan'] }}
                            </label>

                            <input
                                type="number"
                                class="form-control berat-jumlah"
                                name="layanan[{{ $index }}][berat_jumlah]"
                                min="0.01"
                                step="0.01"
                                value="1"
                                required
                            >

                        </div>


                        {{-- SUBTOTAL --}}
                        <div class="col-md-2">

                            <label class="form-label">
                                Subtotal
                            </label>

                            <input
                                type="text"
                                class="form-control subtotal"
                                value="Rp {{ number_format($layanan['harga'], 0, ',', '.') }}"
                                readonly
                            >

                        </div>

                    </div>

                </div>

            @endforeach


            {{-- TOMBOL --}}
            <div class="d-flex justify-content-between mt-4">

                <a
                    href="{{ route('pemesanan.alur.layanan') }}"
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
```

</div>

{{-- HITUNG SUBTOTAL --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const rows = document.querySelectorAll('.card.mb-3');

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function hitungSubtotal() {

        rows.forEach(function (row) {

            const hargaInput = row.querySelector('.harga');
            const jumlahInput = row.querySelector('.berat-jumlah');
            const subtotalInput = row.querySelector('.subtotal');

            if (!hargaInput || !jumlahInput || !subtotalInput) {
                return;
            }

            const harga = parseFloat(
                hargaInput.dataset.harga
            ) || 0;

            const jumlah = parseFloat(
                jumlahInput.value
            ) || 0;

            const subtotal = harga * jumlah;

            subtotalInput.value = formatRupiah(subtotal);

        });

    }


    document.querySelectorAll('.berat-jumlah').forEach(function (input) {

        input.addEventListener('input', hitungSubtotal);

    });


    hitungSubtotal();

});

</script>

@endsection
