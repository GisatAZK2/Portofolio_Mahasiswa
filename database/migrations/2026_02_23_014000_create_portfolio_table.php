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
    Schema::create('portfolio', function (Blueprint $table) {
        $table->id('id_portfolio');
        $table->json('isi_content');
        $table->unsignedBigInteger('id_mahasiswa');
        $table->dateTime('tanggal');

        $table->foreign('id_mahasiswa')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio');
    }
};
