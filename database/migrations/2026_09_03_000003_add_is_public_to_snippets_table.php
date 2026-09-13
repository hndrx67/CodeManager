<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('snippets', 'is_public')) {
            Schema::table('snippets', function (Blueprint $table) {
                $table->boolean('is_public')->default(false)->after('code')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('snippets', 'is_public')) {
            Schema::table('snippets', fn (Blueprint $table) => $table->dropColumn('is_public'));
        }
    }
};
