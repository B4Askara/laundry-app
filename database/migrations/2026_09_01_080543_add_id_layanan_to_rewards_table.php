<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->unsignedBigInteger('id_layanan')
                  ->after('id_reward');

            $table->foreign('id_layanan')
                  ->references('id_layanan')
                  ->on('layanans')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('rewards', function (Blueprint $table) {
            $table->dropForeign(['id_layanan']);
            $table->dropColumn('id_layanan');
        });
    }
};