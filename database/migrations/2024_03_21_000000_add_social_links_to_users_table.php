<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->integer('current_streak')->default(0);
            $table->integer('max_streak')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'linkedin_url',
                'github_url',
                'portfolio_url',
                'last_active_at',
                'current_streak',
                'max_streak'
            ]);
        });
    }
};
