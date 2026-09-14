<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('layanans', function (Blueprint $table) {
        $table->id('id_layanan');
        $table->string('nama_layanan', 100);
        $table->enum('kategori', ['kiloan', 'satuan', 'express']);
        $table->decimal('harga', 10, 2);
        $table->enum('satuan', ['KG', 'item']);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
