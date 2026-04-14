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
        Schema::create('postingan', function (Blueprint $table) {
            $table->id('id_postingan');
            $table->unsignedBigInteger('id_user');
            $table->json('content');
            $table->timestamp('tanggal');
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->timestamps();
      
            $table->foreign('id_user')
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
        //
    }
};
