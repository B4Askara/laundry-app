@extends('layouts.app')

@section('content')

<style>
    .pelanggan-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 0;
    }

    .pelanggan-header {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 26px;
    }

    .pelanggan-back {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #fff;
        color: #475569;
        text-decoration: none;
        transition: 0.2s;
    }

    .pelanggan-back:hover {
        background: #f1f5f9;
        color: #2563eb;
    }

    .pelanggan-header h2 {
        margin: 0 0 5px;
        color: #172033;
        font-size: 25px;
        font-weight: 600;
    }

    .pelanggan-header p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .pelanggan-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 28px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
    }

    .pelanggan-form-group {
        margin-bottom: 20px;
    }

    .pelanggan-form-group label {
        display: block;
        margin-bottom: 8px;
        color: #263449;
        font-size: 14px;
        font-weight: 500;
    }

    .pelanggan-form-control {
        width: 100%;
        min-height: 43px;
        padding: 10px 13px;
        border: 1px solid #dce2ea;
        border-radius: 7px;
        background: #fff;
        color: #263449;
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
    }

    .pelanggan-form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.10);
    }

    .pelanggan-form-control::placeholder {
        color: #94a3b8;
    }

    textarea.pelanggan-form-control {
        min-height: 100px;
        resize: vertical;
    }

    .pelanggan-radio-group {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 22px;
        margin: 4px 0 22px;
    }

    .pelanggan-radio {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #334155;
        font-size: 14px;
        cursor: pointer;
    }

    .pelanggan-radio input {
        width: 16px;
        height: 16px;
        accent-color: #2563eb;
        cursor: pointer;
    }

    .pelanggan-search-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border: none;
        border-radius: 7px;
        background: #2563eb;
        color: #fff;
        font-family: inherit;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
    }

    .pelanggan-search-btn:hover {
        background: #1d4ed8;
    }

    .pelanggan-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 28px;
    }

    .pelanggan-next-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 17px;
        border: none;
        border-radius: 7px;
        background: #2563eb;
        color: #fff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
    }

    .pelanggan-next-btn:hover {
        background: #1d4ed8;
    }

    .pelanggan-search-results {
        top: 100%;
        left: 0;
        max-height: 220px;
        overflow-y: auto;
        box-shadow: 0 5px 15px rgba(15, 23, 42, 0.10);
    }

    .pelanggan-search-results .list-group-item {
        padding: 11px 13px;
        border-color: #edf0f5;
        font-size: 13px;
        text-align: left;
    }

    @media (max-width: 600px) {
        .pelanggan-page {
            padding: 15px 0;
        }

        .pelanggan-header h2 {
            font-size: 21px;
        }

        .pelanggan-card {
            padding: 18px;
        }

        .pelanggan-radio-group {
            gap: 14px;
        }

        .pelanggan-search-btn,
        .pelanggan-next-btn {
            justify-content: center;
        }
    }
</style>

<div class="container pelanggan-page">

    {{-- HEADER --}}
    <div class="pelanggan-header">

        <a href="{{ route('pemesanan.index') }}"
           class="pelanggan-back"
           aria-label="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>

        <div>
            <h2>Data Pelanggan</h2>
            <p>Cari pelanggan yang sudah terdaftar atau masukkan data pelanggan baru.</p>
        </div>

    </div>


    {{-- FORM PELANGGAN --}}
    <div class="pelanggan-card">

        <form action="#" method="POST" id="formPelanggan">
            @csrf

            <input type="hidden" id="id_pelanggan" name="id_pelanggan">

            {{-- NAMA PELANGGAN --}}
            <div class="pelanggan-form-group position-relative">

                <label for="nama">Nama Pelanggan</label>

                <input
                    type="text"
                    class="pelanggan-form-control"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama pelanggan"
                    autocomplete="off"
                    required
                >

                {{-- HASIL PENCARIAN --}}
                <div
                    id="hasilPelanggan"
                    class="list-group position-absolute w-100 pelanggan-search-results"
                    style="z-index: 1000; display: none;"
                ></div>

            </div>


            {{-- NOMOR HP --}}
            <div class="pelanggan-form-group">

                <label for="no_hp">No. HP</label>

                <input
                    type="text"
                    class="pelanggan-form-control"
                    id="no_hp"
                    name="no_hp"
                    placeholder="Masukkan nomor HP"
                    required
                >

            </div>


            {{-- ALAMAT --}}
            <div class="pelanggan-form-group">

                <label for="alamat">Alamat</label>

                <textarea
                    class="pelanggan-form-control"
                    id="alamat"
                    name="alamat"
                    rows="3"
                    placeholder="Masukkan alamat pelanggan"
                    required
                ></textarea>

            </div>


            {{-- PILIH JENIS PELANGGAN --}}
            <div class="pelanggan-radio-group">

                <label class="pelanggan-radio">
                    <input
                        type="radio"
                        name="jenis_pelanggan"
                        value="lama"
                        id="pelangganLama"
                        checked
                    >
                    Pelanggan Lama
                </label>

                <label class="pelanggan-radio">
                    <input
                        type="radio"
                        name="jenis_pelanggan"
                        value="baru"
                        id="pelangganBaru"
                    >
                    Pelanggan Baru
                </label>

            </div>


            {{-- TOMBOL CARI --}}
            <button
                type="button"
                class="pelanggan-search-btn"
                id="cariPelangganBtn"
            >
                <i class="bi bi-search"></i>
                Cari Pelanggan
            </button>


            {{-- STEMPEL --}}
            <input
                type="hidden"
                id="stempel"
                value="0"
            >


            {{-- TOMBOL LANJUT --}}
            <div class="pelanggan-footer">

                <button
                    type="submit"
                    class="pelanggan-next-btn"
                >
                    Selanjutnya
                    <i class="bi bi-chevron-right"></i>
                </button>

            </div>

        </form>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const namaInput = document.getElementById('nama');
    const noHpInput = document.getElementById('no_hp');
    const alamatInput = document.getElementById('alamat');
    const stempelInput = document.getElementById('stempel');
    const idPelangganInput = document.getElementById('id_pelanggan');

    const hasilPelanggan = document.getElementById('hasilPelanggan');
    const cariPelangganBtn = document.getElementById('cariPelangganBtn');

    const pelangganLama = document.getElementById('pelangganLama');
    const pelangganBaru = document.getElementById('pelangganBaru');

    let timeout = null;


    // ========================================
    // CARI PELANGGAN
    // ========================================

    function cariPelanggan() {

        const keyword = namaInput.value.trim();

        if (keyword.length === 0) {
            hasilPelanggan.innerHTML = '';
            hasilPelanggan.style.display = 'none';
            return;
        }

        fetch(
            '{{ route("pemesanan.cari-pelanggan") }}?q=' +
            encodeURIComponent(keyword)
        )
        .then(response => response.json())
        .then(data => {

            hasilPelanggan.innerHTML = '';

            if (data.length === 0) {

                const kosong = document.createElement('div');
                kosong.className = 'list-group-item text-muted';
                kosong.textContent = 'Pelanggan tidak ditemukan.';

                hasilPelanggan.appendChild(kosong);
                hasilPelanggan.style.display = 'block';

                return;
            }

            data.forEach(function (pelanggan) {

                const item = document.createElement('button');

                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';

                const nama = document.createElement('strong');
                nama.textContent = pelanggan.nama;

                const nomor = document.createElement('small');
                nomor.className = 'text-muted d-block';
                nomor.textContent = pelanggan.no_hp ?? '-';

                item.appendChild(nama);
                item.appendChild(nomor);

                item.addEventListener('click', function () {

                    namaInput.value = pelanggan.nama;
                    noHpInput.value = pelanggan.no_hp ?? '';
                    alamatInput.value = pelanggan.alamat ?? '';
                    stempelInput.value = pelanggan.stempel ?? 0;

                    idPelangganInput.value = pelanggan.id_pelanggan;

                    pelangganLama.checked = true;

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

    }


    // ========================================
    // AUTOCOMPLETE NAMA
    // ========================================

    namaInput.addEventListener('input', function () {

        const keyword = this.value.trim();

        clearTimeout(timeout);

        if (idPelangganInput.value !== '') {
            idPelangganInput.value = '';
            stempelInput.value = 0;
        }

        if (pelangganBaru.checked || keyword.length === 0) {
            hasilPelanggan.style.display = 'none';
            hasilPelanggan.innerHTML = '';
            return;
        }

        timeout = setTimeout(cariPelanggan, 300);

    });


    // ========================================
    // TOMBOL CARI PELANGGAN
    // ========================================

    cariPelangganBtn.addEventListener('click', function () {

        if (pelangganBaru.checked) {
            alert('Pilih Pelanggan Lama untuk mencari pelanggan terdaftar.');
            return;
        }

        cariPelanggan();

    });


    // ========================================
    // PILIH PELANGGAN BARU
    // ========================================

    pelangganBaru.addEventListener('change', function () {

        if (this.checked) {

            idPelangganInput.value = '';
            stempelInput.value = 0;

            hasilPelanggan.innerHTML = '';
            hasilPelanggan.style.display = 'none';

        }

    });


    // ========================================
    // PILIH PELANGGAN LAMA
    // ========================================

    pelangganLama.addEventListener('change', function () {

        if (this.checked && namaInput.value.trim() !== '') {
            cariPelanggan();
        }

    });


    // ========================================
    // TUTUP HASIL PENCARIAN
    // ========================================

    document.addEventListener('click', function (event) {

        if (
            !namaInput.contains(event.target) &&
            !hasilPelanggan.contains(event.target) &&
            !cariPelangganBtn.contains(event.target)
        ) {
            hasilPelanggan.style.display = 'none';
        }

    });

});
</script>

@endsection
