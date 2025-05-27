<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // First, ensure the name column exists
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('user_handle');
            }

            // Copy data from username to name if username exists
            if (Schema::hasColumn('users', 'username')) {
                DB::statement('UPDATE users SET name = username WHERE name IS NULL');
                $table->dropColumn('username');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->after('user_handle');
            }

            if (Schema::hasColumn('users', 'name')) {
                DB::statement('UPDATE users SET username = name WHERE username IS NULL');
                $table->dropColumn('name');
            }
        });
    }
};
