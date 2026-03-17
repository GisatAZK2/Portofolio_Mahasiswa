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
        DB::unprepared("
        CREATE TRIGGER before_users_insert
        BEFORE INSERT ON users
        FOR EACH ROW
        BEGIN
            IF NEW.role = 'admin' THEN
                SET NEW.id_angkatan = NULL;
                SET NEW.id_jurusan = NULL;
                SET NEW.id_keahlian = NULL;
            END IF;
        END
        ");

        DB::unprepared("
        CREATE TRIGGER before_users_update
        BEFORE UPDATE ON users
        FOR EACH ROW
        BEGIN
            IF NEW.role = 'admin' THEN
                SET NEW.id_angkatan = NULL;
                SET NEW.id_jurusan = NULL;
                SET NEW.id_keahlian = NULL;
            END IF;
        END
        ");
    }
    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS before_users_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS before_users_update");
    }
};
