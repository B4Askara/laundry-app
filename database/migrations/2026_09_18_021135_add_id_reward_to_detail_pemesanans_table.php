<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pemesanans', function (Blueprint $table) {
            $table->unsignedBigInteger('id_reward')
                ->nullable()
                ->after('id_layanan');

            $table->foreign('id_reward')
                ->references('id_reward')
                ->on('rewards')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detail_pemesanans', function (Blueprint $table) {
            $table->dropForeign(['id_reward']);
            $table->dropColumn('id_reward');
        });
    }
};