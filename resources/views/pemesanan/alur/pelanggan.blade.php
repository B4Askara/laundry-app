@extends('layouts.app')

@section('content')

<div class="container py-4">

```
<div class="mb-4">
    <h2>Data Pelanggan</h2>
    <p class="text-muted">
        Cari pelanggan yang sudah terdaftar atau masukkan data pelanggan baru.
    </p>
</div>

<div class="card">
    <div class="card-body">

        <form action="#" method="POST" id="formPelanggan">
            @csrf

            {{-- ID pelanggan lama --}}
            <input type="hidden" id="id_pelanggan" name="id_pelanggan">

            {{-- Nama --}}
            <div class="mb-3 position-relative">

                <label for="nama" class="form-label">
                    Nama Pelanggan
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="nama"
                    name="nama"
                    placeholder="Ketik nama pelanggan..."
                    autocomplete="off"
                    required
                >

                {{-- Hasil pencarian --}}
                <div
                    id="hasilPelanggan"
                    class="list-group position-absolute w-100"
                    style="z-index: 1000; display: none;"
                ></div>

            </div>

            {{-- No HP --}}
            <div class="mb-3">

                <label for="no_hp" class="form-label">
                    No. HP
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="no_hp"
                    name="no_hp"
                    placeholder="Masukkan nomor HP"
                    required
                >

            </div>

            {{-- Alamat --}}
            <div class="mb-3">

                <label for="alamat" class="form-label">
                    Alamat
                </label>

                <textarea
                    class="form-control"
                    id="alamat"
                    name="alamat"
                    rows="3"
                    placeholder="Masukkan alamat pelanggan"
                    required
                ></textarea>

            </div>

            {{-- Stempel --}}
            <div class="mb-3">

                <label for="stempel" class="form-label">
                    Stempel
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="stempel"
                    value="0"
                    readonly
                >

            </div>

            <div class="d-flex justify-content-end">

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

<script>

document.addEventListener('DOMContentLoaded', function () {

    const namaInput = document.getElementById('nama');
    const noHpInput = document.getElementById('no_hp');
    const alamatInput = document.getElementById('alamat');
    const stempelInput = document.getElementById('stempel');
    const idPelangganInput = document.getElementById('id_pelanggan');
    const hasilPelanggan = document.getElementById('hasilPelanggan');

    let timeout = null;

    namaInput.addEventListener('input', function () {

        const keyword = this.value.trim();

        clearTimeout(timeout);

        // Kalau kosong, sembunyikan hasil
        if (keyword.length === 0) {
            hasilPelanggan.style.display = 'none';
            hasilPelanggan.innerHTML = '';
            idPelangganInput.value = '';
            return;
        }

        timeout = setTimeout(function () {

            fetch(
                '{{ route("pemesanan.cari-pelanggan") }}?q=' +
                encodeURIComponent(keyword)
            )
                .then(response => response.json())
                .then(data => {

                    hasilPelanggan.innerHTML = '';

                    if (data.length === 0) {

                        hasilPelanggan.style.display = 'none';
                        return;

                    }

                    data.forEach(function (pelanggan) {

                        const item = document.createElement('button');

                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action';

                        item.innerHTML = `
                            <strong>${pelanggan.nama}</strong>
                            <br>
                            <small class="text-muted">
                                ${pelanggan.no_hp ?? '-'}
                            </small>
                        `;

                        item.addEventListener('click', function () {

                            // Isi data pelanggan
                            namaInput.value = pelanggan.nama;
                            noHpInput.value = pelanggan.no_hp ?? '';
                            alamatInput.value = pelanggan.alamat ?? '';
                            stempelInput.value = pelanggan.stempel ?? 0;

                            // Simpan ID pelanggan
                            idPelangganInput.value = pelanggan.id_pelanggan;

                            // Sembunyikan hasil pencarian
                            hasilPelanggan.style.display = 'none';
                            hasilPelanggan.innerHTML = '';

                        });

                        hasilPelanggan.appendChild(item);

                    });

                    hasilPelanggan.style.display = 'block';

                })
                .catch(error => {

                    console.error('Gagal mencari pelanggan:', error);

                });

        }, 300);

    });


    // Kalau nama diubah manual setelah memilih pelanggan,
    // ID pelanggan lama dihapus supaya tidak salah data.
    namaInput.addEventListener('change', function () {

        if (idPelangganInput.value !== '') {
            idPelangganInput.value = '';
            stempelInput.value = 0;
        }

    });


    // Klik di luar hasil pencarian → tutup autocomplete
    document.addEventListener('click', function (event) {

        if (
            !namaInput.contains(event.target) &&
            !hasilPelanggan.contains(event.target)
        ) {
            hasilPelanggan.style.display = 'none';
        }

    });

});

</script>

@endsection
