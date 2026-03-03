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
        Schema::table('learning_corner', function (Blueprint $table) {
         
            $table->unsignedBigInteger('project_id')->after('id_learning_corner');

            $table->foreign('project_id')
                ->references('id') // sesuaikan dengan PK project kamu
                ->on('projects')            // sesuaikan nama tabel project
                ->onDelete('cascade');

             });
            }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_corner', function (Blueprint $table) {
            //
        });
    }
};
