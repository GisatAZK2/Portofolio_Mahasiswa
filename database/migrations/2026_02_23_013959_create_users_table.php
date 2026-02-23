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

            // Custom Mahasiswa Fields
            $table->string('nama_mahasiswa', 100);
            $table->string('photo_profile', 100)->nullable();
            $table->string('email',100)->unique()->nullable();
            $table->string('username', 100)->unique()->nullable();
            $table->string('password', 255)->nullable();
            $table->unsignedBigInteger('id_jurusan');
            $table->unsignedBigInteger('id_keahlian');
            
            $table->boolean('is_active')->default(true);

            // Laravel Auth Features
            $table->rememberToken(); // untuk remember me
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            // Foreign Key
            $table->foreign('id_jurusan')
                ->references('id_jurusan')
                ->on('jurusan')
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