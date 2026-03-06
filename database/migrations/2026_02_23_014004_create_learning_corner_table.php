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
        Schema::create('learning_corner', function (Blueprint $table) {
            $table->id('id_learning_corner');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->json('content');
            $table->timestamp('tanggal');
            $table->timestamps();
            $table->unsignedBigInteger('project_id');

            $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->onDelete('cascade');
            
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
        Schema::dropIfExists('learning_corner');
    }
};
