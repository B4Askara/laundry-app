<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id('id_pembayaran');

            $table->foreignId('id_pemesanan')
                ->unique()
                ->constrained('pemesanans', 'id_pemesanan')
                ->cascadeOnDelete();

            $table->decimal('jumlah_bayar', 10, 2);

            $table->enum('metode_pembayaran', [
                'cash',
                'transfer',
                'qris'
            ]);

            $table->dateTime('tanggal_pembayaran');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};