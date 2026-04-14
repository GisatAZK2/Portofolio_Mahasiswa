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
        Schema::create('komentar', function (Blueprint $table) {
            $table->id('id_komentar');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_postingan');
            $table->longText('komentar');
            $table->timestamp('tanggal');
            $table->timestamps();
            
            $table->foreign('id_user')
              ->references('id')
              ->on('users')
              ->onDelete('cascade');

            $table->foreign('id_postingan')
                ->references('id_postingan')
                ->on('postingan')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
