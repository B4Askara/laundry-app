<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pemesanans', function (Blueprint $table) {

            $table->id('id_detail');

            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_layanan');

            $table->decimal('berat_jumlah', 8, 2);
            $table->decimal('harga', 10, 2);
            $table->decimal('subtotal', 10, 2);

            $table->boolean('pakai_reward')->default(false);

            $table->timestamps();

            // Relasi ke pemesanan
            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')
                ->on('pemesanans')
                ->onDelete('cascade');

            // Relasi ke layanan
            $table->foreign('id_layanan')
                ->references('id_layanan')
                ->on('layanans')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pemesanans');
    }
};