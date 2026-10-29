
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran Laundry</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 15px;
            background: #f1f5f9;
            font-family: Arial, Helvetica, sans-serif;
            color: #1e293b;
        }

        .struk-container {
            max-width: 480px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px dashed #cbd5e1;
        }

        .logo {
            width: 58px;
            height: 58px;
            margin: 0 auto 12px;
            border-radius: 14px;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            font-weight: bold;
        }

        .header h1 {
            margin: 0;
            font-size: 23px;
            color: #2563eb;
        }

        .header p {
            margin: 7px 0 0;
            font-size: 12px;
            color: #64748b;
        }

        .status {
            display: inline-block;
            margin-top: 14px;
            padding: 6px 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .section {
            padding: 18px 0;
            border-bottom: 1px dashed #cbd5e1;
        }

        .section-title {
            margin: 0 0 12px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
        }

        .info-row,
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .info-row:last-child,
        .total-row:last-child {
            margin-bottom: 0;
        }

        .info-row span:first-child,
        .total-row span:first-child {
            color: #64748b;
        }

        .info-row strong,
        .total-row strong {
            text-align: right;
            overflow-wrap: anywhere;
        }

        .item {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .item:last-child {
            border-bottom: none;
        }

        .item-name {
            font-size: 14px;
            font-weight: bold;
        }

        .item-detail {
            margin-top: 6px;
            font-size: 12px;
            color: #64748b;
        }

        .item-subtotal {
            margin-top: 8px;
            text-align: right;
            font-size: 13px;
            font-weight: bold;
        }

        .reward-label {
            display: inline-block;
            margin-top: 6px;
            padding: 4px 7px;
            border-radius: 5px;
            background: #fef3c7;
            color: #92400e;
            font-size: 10px;
        }

        .grand-total {
            margin-top: 14px;
            padding: 15px;
            background: #eff6ff;
            border-radius: 8px;
        }

        .grand-total .total-row {
            font-size: 15px;
            font-weight: bold;
            color: #1d4ed8;
        }

        .grand-total .total-row span:first-child {
            color: #1d4ed8;
        }

        .footer {
            padding-top: 22px;
            text-align: center;
            font-size: 12px;
            line-height: 1.7;
            color: #64748b;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px auto 0;
            max-width: 480px;
        }

        .btn {
            padding: 11px 18px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-print {
            background: #2563eb;
            color: white;
        }

        .btn-back {
            background: #e2e8f0;
            color: #334155;
        }

        @media print {
            @page {
                size: auto;
                margin: 10mm;
            }

            body {
                padding: 0;
                background: white;
            }

            .struk-container {
                max-width: 100%;
                padding: 10px;
                box-shadow: none;
                border-radius: 0;
            }

            .actions {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="struk-container" id="area-struk">

        <div class="header">
            <div class="logo">L</div>
            <h1>LAUNDRY</h1>
            <p>Struk Pembayaran Laundry</p>
            <div class="status">PEMBAYARAN BERHASIL</div>
        </div>

        <div class="section">
            <h3 class="section-title">Informasi Transaksi</h3>

            <div class="info-row">
                <span>Nomor Struk</span>
                <strong>{{ $struk->nomor_struk ?? '-' }}</strong>
            </div>

            <div class="info-row">
                <span>Tanggal Bayar</span>
                <strong>
                    {{ $pembayaran->tanggal_pembayaran
                        ? \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d/m/Y H:i')
                        : '-' }}
                </strong>
            </div>

            <div class="info-row">
                <span>ID Pesanan</span>
                <strong>#{{ $pemesanan->id_pemesanan }}</strong>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">Data Pelanggan</h3>

            <div class="info-row">
                <span>Nama</span>
                <strong>{{ $pemesanan->pelanggan->nama ?? '-' }}</strong>
            </div>

            <div class="info-row">
                <span>No. HP</span>
                <strong>{{ $pemesanan->pelanggan->no_hp ?? '-' }}</strong>
            </div>

            <div class="info-row">
                <span>Pengambilan</span>
                <strong>
                    {{ $pemesanan->pengambilan->metode_pengambilan
                        ?? $pemesanan->pengambilan->jenis_pengambilan
                        ?? '-' }}
                </strong>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">Rincian Layanan</h3>

            @forelse ($pemesanan->detailPemesanan as $detail)
                <div class="item">
                    <div class="item-name">
                        {{ $detail->layanan->nama_layanan ?? 'Layanan' }}
                    </div>

                    <div class="item-detail">
                        Jumlah / Berat:
                        {{ $detail->berat_jumlah }}
                        {{ $detail->layanan->satuan ?? '' }}
                    </div>

                    @if ($detail->pakai_reward)
                        <span class="reward-label">Menggunakan Reward</span>
                    @endif

                    <div class="item-subtotal">
                        Rp {{ number_format($detail->subtotal ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            @empty
                <p>Tidak ada detail layanan.</p>
            @endforelse
        </div>

        <div class="section">
            <h3 class="section-title">Rincian Pembayaran</h3>

            <div class="total-row">
                <span>Subtotal Layanan</span>
                <strong>
                    Rp {{ number_format(
                        $pemesanan->detailPemesanan->sum('subtotal'),
                        0, ',', '.'
                    ) }}
                </strong>
            </div>

            <div class="total-row">
                <span>Ongkos Kirim</span>
                <strong>
                    Rp {{ number_format($pemesanan->ongkir ?? 0, 0, ',', '.') }}
                </strong>
            </div>

            <div class="grand-total">
                <div class="total-row">
                    <span>Total Bayar</span>
                    <strong>
                        Rp {{ number_format($pemesanan->total_harga ?? 0, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            <div style="margin-top: 15px;">
                <div class="total-row">
                    <span>Metode Pembayaran</span>
                    <strong>
                        {{ strtoupper($pembayaran->metode_pembayaran ?? '-') }}
                    </strong>
                </div>

                <div class="total-row">
                    <span>Jumlah Dibayar</span>
                    <strong>
                        Rp {{ number_format($pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="total-row">
                    <span>Kembalian</span>
                    <strong>
                        Rp {{ number_format(
                            max(
                                0,
                                ($pembayaran->jumlah_bayar ?? 0)
                                - ($pemesanan->total_harga ?? 0)
                            ),
                            0, ',', '.'
                        ) }}
                    </strong>
                </div>
            </div>
        </div>

        <div class="footer">
            <strong>Terima kasih telah menggunakan jasa kami!</strong>
            <br>
            Simpan struk ini sebagai bukti pembayaran.
            <br>
            Semoga hari Anda menyenangkan.
        </div>

    </div>

    <div class="actions">
        <button type="button" class="btn btn-print" onclick="window.print()">
            Cetak Struk
        </button>

        <a href="{{ route('pembayaran.index') }}" class="btn btn-back">
            Kembali
        </a>
    </div>

</body>
</html>
