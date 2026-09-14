<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['id_layanan']);
        });

        Schema::table('pemesanans', function (Blueprint $table) {
            // Layanan utama sekarang disimpan di detail_pemesanans
            $table->foreignId('id_layanan')
                ->nullable()
                ->change();

            // Berat/jumlah juga disimpan di detail_pemesanans
            $table->decimal('berat_jumlah', 8, 2)
                ->nullable()
                ->change();
        });

        Schema::table('pemesanans', function (Blueprint $table) {
            // Pasang kembali foreign key
            $table->foreign('id_layanan')
                ->references('id_layanan')
                ->on('layanans')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropForeign(['id_layanan']);
        });

        Schema::table('pemesanans', function (Blueprint $table) {
            $table->foreignId('id_layanan')
                ->nullable(false)
                ->change();

            $table->decimal('berat_jumlah', 8, 2)
                ->nullable(false)
                ->change();
        });

        Schema::table('pemesanans', function (Blueprint $table) {
            $table->foreign('id_layanan')
                ->references('id_layanan')
                ->on('layanans')
                ->cascadeOnDelete();
        });
    }
};