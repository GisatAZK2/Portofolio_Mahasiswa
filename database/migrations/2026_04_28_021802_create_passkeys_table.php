<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('passkeys', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('name');
            $table->string('credential_id', 255); // ubah dari text
            $table->text('public_key');
            $table->unsignedBigInteger('sign_count')->default(0);
            $table->text('transports')->nullable();
            $table->uuid('aaguid')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'credential_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passkeys');
    }
};