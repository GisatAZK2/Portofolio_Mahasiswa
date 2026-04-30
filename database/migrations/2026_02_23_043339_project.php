<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->json('isi_content');
            $table->json('viewer_ids');
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir')->nullable();
            //pemimpin project
            $table->unsignedBigInteger('leader_id')->nullable();

            $table->foreign('leader_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            // Pembuat project / mahasiswa
            $table->foreignId('id_mahasiswa')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};