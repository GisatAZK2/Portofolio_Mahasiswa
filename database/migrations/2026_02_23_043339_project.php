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
            $table->string('nama_project');
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir')->nullable();
            $table->string('link_project')->nullable();

            $table->unsignedBigInteger('id_mahasiswa');
            $table->foreign('id_mahasiswa')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};