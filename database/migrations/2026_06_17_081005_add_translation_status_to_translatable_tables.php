<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected array $tables = ['users', 'postingan', 'projects', 'komentar', 'learning_corner'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                if (!Schema::hasColumn($table, 'translation_status')) {
                    $blueprint->string('translation_status')->default('pending')->after('translations');
                    // pending | processing | done | failed | skipped
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) use ($table) {
                if (Schema::hasColumn($table, 'translation_status')) {
                    $blueprint->dropColumn('translation_status');
                }
            });
        }
    }
};