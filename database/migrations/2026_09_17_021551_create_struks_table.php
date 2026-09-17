<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('struks', function (Blueprint $table) {
            $table->id('id_struk');

            $table->foreignId('id_pembayaran')
                ->unique()
                ->constrained('pembayarans', 'id_pembayaran')
                ->cascadeOnDelete();

            $table->string('nomor_struk')->unique();

            $table->dateTime('tanggal_cetak');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('struks');
    }
};