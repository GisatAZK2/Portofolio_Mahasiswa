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
        /*
        |--------------------------------------------------------------------------
        | USERS TABLE
        |--------------------------------------------------------------------------
        */
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            

            $table->string('nama_mahasiswa', 100);
            $table->string('nim', 50)->unique()->nullable();
            $table->string('photo_profile', 100)->nullable();
            $table->string('email',100)->unique()->nullable();
            $table->string('username', 100)->unique()->nullable();
            $table->string('password', 255)->nullable();
            $table->string('background_url')->nullable();
            $table->enum('jenis_kelamin', [ 'Laki-laki','Perempuan','Tidak ingin memberi tahu'])->nullable();
            $table->enum('status_pengajuan', ['Sedang Di Ajukan', 'Di Terima', 'Di Tolak'])->default('Sedang Di Ajukan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('id_jurusan')->nullable();
            $table->unsignedBigInteger('id_keahlian')->nullable();
            $table->unsignedBigInteger('id_angkatan')->nullable();
            $table->date('tanggal_lahir');

            
            $table->boolean('is_active')->default(true);
            $table->rememberToken(); 
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            // Foreign Key
            $table->foreign('id_jurusan')
                ->references('id_jurusan')
                ->on('jurusan')
                ->onDelete('cascade');

            $table->foreign('id_angkatan')
                ->references('id')
                ->on('angkatan')
                ->onDelete('cascade');
        });


        /*
        |--------------------------------------------------------------------------
        | PASSWORD RESET TOKENS TABLE
        |--------------------------------------------------------------------------
        */
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('username')->primary(); 
            // karena kamu pakai username, bukan email

            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });


        /*
        |--------------------------------------------------------------------------
        | SESSIONS TABLE (Optional - for database session driver)
        |--------------------------------------------------------------------------
        */
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};