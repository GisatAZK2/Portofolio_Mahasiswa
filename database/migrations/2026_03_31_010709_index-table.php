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
Schema::table('users', function (Blueprint $table) {
    $table->index('id_jurusan');
    $table->index('id_keahlian');
    $table->index('id_angkatan');
});

Schema::table('projects', function (Blueprint $table) {
    $table->index('leader_id');
    $table->index('id_mahasiswa');
    $table->index('tanggal_mulai');
    $table->index('tanggal_akhir');
});

Schema::table('learning_corner', function (Blueprint $table) {
    $table->index('id_mahasiswa');
});

Schema::table('sertifikat', function (Blueprint $table) {
    $table->index('id_mahasiswa');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
