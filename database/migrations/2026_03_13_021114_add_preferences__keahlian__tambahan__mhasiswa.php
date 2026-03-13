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
        Schema::create('keahlian_tambahan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_user')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('id_keahlian')
                ->nullable()
                ->constrained('keahlian', 'id_keahlian')
                ->cascadeOnDelete();

            $table->enum('status_pengajuan', [
                'Sedang Di Ajukan',
                'Di Terima',
                'Di Tolak'
            ])->default('Sedang Di Ajukan')->nullable();

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
        Schema::dropIfExists('keahlian_tambahan');
    }
};