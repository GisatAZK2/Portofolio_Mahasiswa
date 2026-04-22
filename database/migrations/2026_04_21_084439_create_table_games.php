<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id('id_games');
            $table->unsignedBigInteger('id_postingan');
            $table->unsignedBigInteger('id_user');
            $table->string('game_name', 100);
            $table->string('score', 10);
            $table->string('playing_time', 20);
            $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('games');
    }
};
