<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('materials_url')->nullable()->after('max_members');
        });
    }

    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('materials_url');
        });
    }
}; 