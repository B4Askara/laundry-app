<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id('id_pemesanan');

            $table->foreignId('id_user')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('id_pelanggan')
                ->constrained('pelanggans', 'id_pelanggan')
                ->cascadeOnDelete();

            $table->foreignId('id_layanan')
                ->constrained('layanans', 'id_layanan')
                ->cascadeOnDelete();

            $table->foreignId('id_pengambilan')
                ->constrained('pengambilans', 'id_pengambilan')
                ->cascadeOnDelete();

            $table->foreignId('id_reward')
                ->nullable()
                ->constrained('rewards', 'id_reward')
                ->nullOnDelete();

            $table->decimal('berat_jumlah', 8, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('ongkir', 10, 2)->default(0);
            $table->decimal('total_harga', 10, 2);

            $table->date('tanggal');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
