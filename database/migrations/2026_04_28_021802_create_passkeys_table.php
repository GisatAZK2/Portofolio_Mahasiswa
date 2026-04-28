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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Nama device (contoh: "iPhone 15")
            $table->text('credential_id'); // Base64 encoded
            $table->text('public_key');
            $table->string('sign_count', 32)->default('0');
            $table->text('transports')->nullable();
            $table->string('aaguid', 36)->nullable();
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['user_id', 'credential_id(100)']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('passkeys');
    }
};