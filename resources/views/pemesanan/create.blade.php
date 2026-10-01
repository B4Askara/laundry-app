@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h2 class="mb-4">Tambah Pemesanan</h2>

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

    <form action="{{ route('pemesanan.store') }}" method="POST">

        @csrf

        <!-- ==================== PELANGGAN ==================== -->
        <div class="card mb-3">
            <div class="card-body">

                <h5 class="mb-3">Data Pelanggan</h5>

                <div class="mb-3 position-relative">

                    <label class="form-label">
                        Pelanggan
                    </label>

                    {{-- Input pencarian nama pelanggan --}}
                    <input
                        type="text"
                        id="nama_pelanggan"
                        class="form-control"
                        placeholder="Ketik nama pelanggan..."
                        autocomplete="off"
                        required
                    >

                    {{-- ID pelanggan yang dikirim ke Laravel --}}
                    <input
                        type="hidden"
                        name="id_pelanggan"
                        id="id_pelanggan"
                    >

                    {{-- Hasil autocomplete --}}
                    <div
                        id="hasil-pelanggan"
                        class="list-group position-absolute w-100"
                        style="
                            display: none;
                            z-index: 1000;
                            max-height: 250px;
                            overflow-y: auto;
                        "
                    ></div>

                </div>

                <div>
                    <strong>
                        Stempel pelanggan:
                    </strong>

                    <span id="jumlah-stempel">
                        0
                    </span>
                </div>

            </div>
        </div>


        <!-- ==================== LAYANAN ==================== -->
        <div class="card mb-3">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="mb-0">
                        Layanan
                    </h5>

                    <button
                        type="button"
                        class="btn btn-success"
                        id="tambah-layanan"
                    >
                        + Tambah Layanan
                    </button>

                </div>


                <div id="container-layanan">

                    <!-- BARIS LAYANAN PERTAMA -->
                    <div class="layanan-item border rounded p-3 mb-3">

                        <div class="row">

                            <!-- Pilih layanan -->
                            <div class="col-md-5 mb-3">

                                <label class="form-label">
                                    Layanan
                                </label>

                                <select
                                    name="layanan[0][id_layanan]"
                                    class="form-control layanan-select"
                                    required
                                >

                                    <option value="">
                                        -- Pilih Layanan --
                                    </option>

                                    @foreach ($layanans as $layanan)

                                        <option
                                            value="{{ $layanan->id_layanan }}"
                                            data-harga="{{ $layanan->harga }}"
                                        >
                                            {{ $layanan->nama_layanan }}
                                            - Rp
                                            {{ number_format($layanan->harga, 0, ',', '.') }}
                                            / {{ $layanan->satuan }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <!-- Jumlah -->
                            <div class="col-md-3 mb-3">

                                <label class="form-label">
                                    Berat / Jumlah
                                </label>

                                <input
                                    type="number"
                                    name="layanan[0][berat_jumlah]"
                                    class="form-control jumlah-input"
                                    min="0.01"
                                    step="0.01"
                                    value="1"
                                    required
                                >

                            </div>


                            <!-- Reward -->
                            <div class="col-md-4 mb-3">

                                <label class="form-label">
                                    Reward
                                </label>

                                <select
                                    name="layanan[0][id_reward]"
                                    class="form-control reward-select"
                                >

                                    <option value="">
                                        Tidak menggunakan reward
                                    </option>

                                    @foreach ($rewards as $reward)

                                        <option
                                            value="{{ $reward->id_reward }}"
                                            data-layanan="{{ $reward->id_layanan }}"
                                            data-stempel="{{ $reward->minimal_stempel }}"
                                        >
                                            {{ $reward->nama_reward }}
                                            - {{ $reward->minimal_stempel }} Stempel
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        <!-- Harga -->
                        <div class="row">

                            <div class="col-md-4">

                                <small class="text-muted">
                                    Harga normal
                                </small>

                                <div class="harga-normal">
                                    Rp 0
                                </div>

                            </div>


                            <div class="col-md-4">

                                <small class="text-muted">
                                    Subtotal
                                </small>

                                <div class="subtotal-layanan fw-bold">
                                    Rp 0
                                </div>

                            </div>


                            <div class="col-md-4 text-end">

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm hapus-layanan"
                                    style="display:none;"
                                >
                                    Hapus
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>


        <!-- ==================== PENGAMBILAN ==================== -->
        <div class="card mb-3">

            <div class="card-body">

                <h5 class="mb-3">
                    Pengambilan
                </h5>

                <div class="mb-3">

                    <label class="form-label">
                        Metode Pengambilan
                    </label>

                    <select
                        name="id_pengambilan"
                        id="id_pengambilan"
                        class="form-control"
                        required
                    >

                        <option value="">
                            -- Pilih Pengambilan --
                        </option>

                        @foreach ($pengambilans as $pengambilan)

                            <option
                                value="{{ $pengambilan->id_pengambilan }}"
                                data-ongkir="{{ $pengambilan->ongkir }}"
                            >
                                {{ ucfirst($pengambilan->metode) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Ongkir
                    </label>

                    <input
                        type="number"
                        id="ongkir"
                        class="form-control"
                        value="0"
                        readonly
                    >

                </div>

            </div>

        </div>


        <!-- ==================== TOTAL ==================== -->
        <div class="card mb-3">

            <div class="card-body">

                <h5 class="mb-3">
                    Ringkasan Pembayaran
                </h5>

                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Subtotal
                    </span>

                    <strong id="subtotal-total">
                        Rp 0
                    </strong>

                </div>


                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Ongkir
                    </span>

                    <strong id="ongkir-text">
                        Rp 0
                    </strong>

                </div>


                <hr>


                <div class="d-flex justify-content-between">

                    <span class="fs-5">
                        Total Harga
                    </span>

                    <strong
                        class="fs-5"
                        id="total-text"
                    >
                        Rp 0
                    </strong>

                </div>

            </div>

        </div>


        <!-- Input tersembunyi untuk total -->
        <input
            type="hidden"
            name="subtotal"
            id="subtotal"
            value="0"
        >

        <input
            type="hidden"
            name="ongkir"
            id="ongkir-hidden"
            value="0"
        >

        <input
            type="hidden"
            name="total_harga"
            id="total_harga"
            value="0"
        >


        <!-- ==================== TANGGAL ==================== -->
        <div class="card mb-3">

            <div class="card-body">

                <label class="form-label">
                    Tanggal
                </label>

                <input
                    type="date"
                    name="tanggal"
                    class="form-control"
                    value="{{ date('Y-m-d') }}"
                    required
                >

            </div>

        </div>


        <!-- ==================== BUTTON ==================== -->

        <button
            type="submit"
            class="btn btn-primary"
        >
            Simpan Pemesanan
        </button>

        <a
            href="{{ route('pemesanan.index') }}"
            class="btn btn-secondary"
        >
            Kembali
        </a>

    </form>

</div>


<!-- ==================== JAVASCRIPT ==================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    let indexLayanan = 1;

    const container =
        document.getElementById('container-layanan');

    const tombolTambah =
        document.getElementById('tambah-layanan');


    /* =========================================================
       PELANGGAN
    ========================================================= */

    const namaPelangganInput =
        document.getElementById('nama_pelanggan');

    const idPelangganInput =
        document.getElementById('id_pelanggan');

    const hasilPelanggan =
        document.getElementById('hasil-pelanggan');

    const jumlahStempel =
        document.getElementById('jumlah-stempel');


    /* =========================================================
       PENGAMBILAN
    ========================================================= */

    const pengambilanSelect =
        document.getElementById('id_pengambilan');

    const ongkirInput =
        document.getElementById('ongkir');

    const ongkirHidden =
        document.getElementById('ongkir-hidden');

    const subtotalHidden =
        document.getElementById('subtotal');

    const totalHargaHidden =
        document.getElementById('total_harga');


    /* =========================================================
       FORMAT RUPIAH
    ========================================================= */

    function rupiah(angka) {

        return 'Rp ' +
            Number(angka).toLocaleString('id-ID');

    }


    /* =========================================================
       AUTOCOMPLETE PELANGGAN
    ========================================================= */

    let timerPencarian;

    namaPelangganInput.addEventListener(
        'input',
        function () {

            const keyword =
                this.value.trim();

            clearTimeout(timerPencarian);

            /*
             * Kalau user mengetik ulang,
             * pelanggan harus dipilih ulang.
             */
            idPelangganInput.value = '';

            jumlahStempel.textContent = '0';


            /*
             * Kalau input kosong
             */
            if (keyword.length < 1) {

                hasilPelanggan.innerHTML = '';

                hasilPelanggan.style.display =
                    'none';

                cekSemuaReward();

                return;

            }


            /*
             * Tunggu 300ms sebelum mencari
             */
            timerPencarian = setTimeout(
                function () {

                    fetch(
                        `{{ route('pemesanan.cari-pelanggan') }}?q=${encodeURIComponent(keyword)}`,
                        {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    )
                    .then(
                        response => response.json()
                    )
                    .then(
                        data => {

                            hasilPelanggan.innerHTML =
                                '';


                            /*
                             * Tidak ditemukan
                             */
                            if (data.length === 0) {

                                hasilPelanggan.innerHTML = `
                                    <div class="list-group-item text-muted">
                                        Pelanggan tidak ditemukan
                                    </div>
                                `;

                                hasilPelanggan.style.display =
                                    'block';

                                return;

                            }


                            /*
                             * Tampilkan hasil
                             */
                            data.forEach(
                                function (pelanggan) {

                                    const item =
                                        document.createElement(
                                            'button'
                                        );

                                    item.type =
                                        'button';

                                    item.className =
                                        'list-group-item list-group-item-action';


                                    item.innerHTML = `
                                        <div>
                                            <strong>
                                                ${pelanggan.nama}
                                            </strong>
                                        </div>

                                        <small class="text-muted">
                                            ${pelanggan.nomor_hp ?? '-'}
                                            • Stempel:
                                            ${pelanggan.stempel ?? 0}
                                        </small>
                                    `;


                                    /*
                                     * Saat pelanggan dipilih
                                     */
                                    item.addEventListener(
                                        'click',
                                        function () {

                                            namaPelangganInput.value =
                                                pelanggan.nama;

                                            idPelangganInput.value =
                                                pelanggan.id_pelanggan;

                                            jumlahStempel.textContent =
                                                pelanggan.stempel ?? 0;


                                            hasilPelanggan.innerHTML =
                                                '';

                                            hasilPelanggan.style.display =
                                                'none';


                                            cekSemuaReward();

                                        }
                                    );


                                    hasilPelanggan.appendChild(
                                        item
                                    );

                                }
                            );


                            hasilPelanggan.style.display =
                                'block';

                        }
                    )
                    .catch(
                        error => {

                            console.error(
                                'Gagal mencari pelanggan:',
                                error
                            );

                            hasilPelanggan.innerHTML = `
                                <div class="list-group-item text-danger">
                                    Gagal mencari pelanggan
                                </div>
                            `;

                            hasilPelanggan.style.display =
                                'block';

                        }
                    );

                },
                300
            );

        }
    );


    /*
     * Tutup autocomplete kalau klik di luar
     */
    document.addEventListener(
        'click',
        function (event) {

            if (
                !namaPelangganInput.contains(
                    event.target
                ) &&
                !hasilPelanggan.contains(
                    event.target
                )
            ) {

                hasilPelanggan.style.display =
                    'none';

            }

        }
    );


    /* =========================================================
       TAMBAH LAYANAN
    ========================================================= */

    tombolTambah.addEventListener(
        'click',
        function () {

            const item =
                document.querySelector(
                    '.layanan-item'
                );

            const clone =
                item.cloneNode(true);


            /*
             * Ganti index name
             */

            clone.querySelector(
                '.layanan-select'
            ).name =
                `layanan[${indexLayanan}][id_layanan]`;


            clone.querySelector(
                '.jumlah-input'
            ).name =
                `layanan[${indexLayanan}][berat_jumlah]`;


            clone.querySelector(
                '.reward-select'
            ).name =
                `layanan[${indexLayanan}][id_reward]`;


            /*
             * Reset nilai
             */

            clone.querySelector(
                '.layanan-select'
            ).value = '';


            clone.querySelector(
                '.jumlah-input'
            ).value = 1;


            clone.querySelector(
                '.reward-select'
            ).value = '';


            clone.querySelector(
                '.harga-normal'
            ).textContent = 'Rp 0';


            clone.querySelector(
                '.subtotal-layanan'
            ).textContent = 'Rp 0';


            /*
             * Tampilkan tombol hapus
             */

            clone.querySelector(
                '.hapus-layanan'
            ).style.display =
                'inline-block';


            container.appendChild(clone);

            indexLayanan++;

            updateSemua();

        }
    );


    /* =========================================================
       HAPUS LAYANAN
    ========================================================= */

    container.addEventListener(
        'click',
        function (event) {

            if (
                event.target.classList.contains(
                    'hapus-layanan'
                )
            ) {

                event.target
                    .closest('.layanan-item')
                    .remove();

                updateSemua();

            }

        }
    );


    /* =========================================================
       PERUBAHAN LAYANAN / JUMLAH / REWARD
    ========================================================= */

    container.addEventListener(
        'change',
        function (event) {

            if (
                event.target.classList.contains(
                    'layanan-select'
                ) ||
                event.target.classList.contains(
                    'jumlah-input'
                ) ||
                event.target.classList.contains(
                    'reward-select'
                )
            ) {

                updateItem(
                    event.target.closest(
                        '.layanan-item'
                    )
                );

                updateSemua();

            }

        }
    );


    container.addEventListener(
        'input',
        function (event) {

            if (
                event.target.classList.contains(
                    'jumlah-input'
                )
            ) {

                updateItem(
                    event.target.closest(
                        '.layanan-item'
                    )
                );

                updateSemua();

            }

        }
    );


    /* =========================================================
       UPDATE SATU LAYANAN
    ========================================================= */

    function updateItem(item) {

        if (!item) {
            return;
        }


        const layananSelect =
            item.querySelector(
                '.layanan-select'
            );

        const jumlahInput =
            item.querySelector(
                '.jumlah-input'
            );

        const rewardSelect =
            item.querySelector(
                '.reward-select'
            );

        const hargaNormal =
            item.querySelector(
                '.harga-normal'
            );

        const subtotalLayanan =
            item.querySelector(
                '.subtotal-layanan'
            );


        const option =
            layananSelect.options[
                layananSelect.selectedIndex
            ];


        const harga =
            Number(
                option?.dataset.harga || 0
            );


        const jumlah =
            Number(
                jumlahInput.value || 0
            );


        /*
         * Tampilkan harga normal
         */

        hargaNormal.textContent =
            rupiah(harga);


        /*
         * Filter reward berdasarkan layanan
         */

        Array.from(
            rewardSelect.options
        ).forEach(
            function (rewardOption) {

                if (!rewardOption.value) {
                    return;
                }

                const idLayananReward =
                    rewardOption.dataset.layanan;


                if (
                    idLayananReward ==
                    layananSelect.value
                ) {

                    rewardOption.style.display =
                        '';

                } else {

                    rewardOption.style.display =
                        'none';

                }

            }
        );


        /*
         * Cek reward
         */

        const rewardOption =
            rewardSelect.options[
                rewardSelect.selectedIndex
            ];


        let pakaiReward = false;


        if (
            rewardOption &&
            rewardOption.value
        ) {

            const idLayananReward =
                rewardOption.dataset.layanan;


            const kebutuhanStempel =
                Number(
                    rewardOption.dataset.stempel || 0
                );


            const stempelPelanggan =
                Number(
                    jumlahStempel.textContent || 0
                );


            /*
             * Pastikan reward untuk layanan
             * yang benar dan stempel cukup.
             */

            if (
                idLayananReward ==
                layananSelect.value &&
                stempelPelanggan >=
                kebutuhanStempel
            ) {

                pakaiReward = true;

            } else {

                rewardSelect.value = '';


                if (
                    stempelPelanggan <
                    kebutuhanStempel
                ) {

                    alert(
                        'Stempel pelanggan tidak cukup untuk reward ini.'
                    );

                }

            }

        }


        /*
         * Hitung subtotal
         */

        let subtotal = 0;


        if (pakaiReward) {

            /*
             * Reward membuat layanan GRATIS
             */

            subtotal = 0;

        } else {

            subtotal =
                harga * jumlah;

        }


        subtotalLayanan.textContent =
            rupiah(subtotal);

    }


    /* =========================================================
       CEK SEMUA REWARD
    ========================================================= */

    function cekSemuaReward() {

        document
            .querySelectorAll('.layanan-item')
            .forEach(
                function (item) {

                    updateItem(item);

                }
            );

        updateSemua();

    }


    /* =========================================================
       UPDATE SEMUA HARGA
    ========================================================= */

    function updateSemua() {

        let subtotalTotal = 0;


        document
            .querySelectorAll('.layanan-item')
            .forEach(
                function (item) {

                    updateItem(item);


                    /*
                     * Ambil harga dari data,
                     * bukan dari teks Rupiah.
                     */

                    const layananSelect =
                        item.querySelector(
                            '.layanan-select'
                        );

                    const jumlahInput =
                        item.querySelector(
                            '.jumlah-input'
                        );

                    const rewardSelect =
                        item.querySelector(
                            '.reward-select'
                        );


                    const option =
                        layananSelect.options[
                            layananSelect.selectedIndex
                        ];


                    const harga =
                        Number(
                            option?.dataset.harga || 0
                        );


                    const jumlah =
                        Number(
                            jumlahInput.value || 0
                        );


                    const rewardOption =
                        rewardSelect.options[
                            rewardSelect.selectedIndex
                        ];


                    let subtotal = harga * jumlah;


                    /*
                     * Kalau menggunakan reward,
                     * subtotal menjadi gratis.
                     */

                    if (
                        rewardOption &&
                        rewardOption.value
                    ) {

                        const kebutuhanStempel =
                            Number(
                                rewardOption.dataset.stempel || 0
                            );

                        const stempelPelanggan =
                            Number(
                                jumlahStempel.textContent || 0
                            );

                        const idLayananReward =
                            rewardOption.dataset.layanan;


                        if (
                            idLayananReward ==
                            layananSelect.value &&
                            stempelPelanggan >=
                            kebutuhanStempel
                        ) {

                            subtotal = 0;

                        }

                    }


                    subtotalTotal +=
                        subtotal;

                }
            );


        /*
         * Ongkir
         */

        const option =
            pengambilanSelect.options[
                pengambilanSelect.selectedIndex
            ];


        const ongkir =
            Number(
                option?.dataset.ongkir || 0
            );


        /*
         * Total
         */

        const total =
            subtotalTotal + ongkir;


        /*
         * Tampilkan
         */

        document.getElementById(
            'subtotal-total'
        ).textContent =
            rupiah(subtotalTotal);


        document.getElementById(
            'ongkir-text'
        ).textContent =
            rupiah(ongkir);


        document.getElementById(
            'total-text'
        ).textContent =
            rupiah(total);


        /*
         * Hidden input
         */

        subtotalHidden.value =
            subtotalTotal;

        ongkirHidden.value =
            ongkir;

        totalHargaHidden.value =
            total;

        ongkirInput.value =
            ongkir;

    }


    /* =========================================================
       PERUBAHAN PENGAMBILAN
    ========================================================= */

    pengambilanSelect.addEventListener(
        'change',
        function () {

            updateSemua();

        }
    );


    /* =========================================================
       JALANKAN PERTAMA KALI
    ========================================================= */

    updateSemua();

});

</script>

@endsection