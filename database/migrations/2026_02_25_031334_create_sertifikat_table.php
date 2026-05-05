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
        Schema::create('sertifikat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sertifikat');
            $table->string('lembaga_penerbit');
            $table->date('tanggal_terbit');
            $table->date('expired_date')->nullable();
            $table->string('link_sertifikat')->nullable();
            $table->unsignedBigInteger('id_mahasiswa');
                $table->foreign('id_mahasiswa')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->enum('status_pengajuan', ['Sedang Di Ajukan', 'Di Terima', 'Di Tolak'])->default('Sedang Di Ajukan')->nullable();
            $table->string('keterangan')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat');
    }
};
