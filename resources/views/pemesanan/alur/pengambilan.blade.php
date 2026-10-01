@extends('layouts.app')

@section('content')

<div class="container py-4">

```
<div class="mb-4">
    <h2>Metode Pengambilan</h2>

    <p class="text-muted">
        Pilih metode pengambilan untuk pelanggan:
        <strong>{{ $pelanggan['nama'] }}</strong>
    </p>
</div>

<div class="card">
    <div class="card-body">

        <form
            action="{{ route('pemesanan.alur.pengambilan.simpan') }}"
            method="POST"
        >

            @csrf

            @foreach ($pengambilans as $pengambilan)

                <div class="card mb-3 p-3">

                    <label
                        class="d-flex align-items-center"
                        style="cursor: pointer;"
                    >

                        <input
                            type="radio"
                            name="id_pengambilan"
                            value="{{ $pengambilan->id_pengambilan }}"
                            class="form-check-input me-3"
                            required
                        >

                        <div>

                            <strong>
                                {{ ucfirst($pengambilan->metode) }}
                            </strong>

                            <br>

                            @if ($pengambilan->metode === 'diantar')

                                <small class="text-muted">
                                    Biaya pengantaran:
                                    <strong>
                                        Rp {{ number_format($pengambilan->ongkir, 0, ',', '.') }}
                                    </strong>
                                </small>

                            @else

                                <small class="text-muted">
                                    Tidak ada biaya pengambilan
                                </small>

                            @endif

                        </div>

                    </label>

                </div>

            @endforeach


            @if ($pengambilans->isEmpty())

                <div class="alert alert-warning">
                    Belum ada metode pengambilan.
                </div>

            @endif


            <div class="d-flex justify-content-between mt-4">

                <a
                    href="{{ route('pemesanan.alur.detail') }}"
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

@endsection
