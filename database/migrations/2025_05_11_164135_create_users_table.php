
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_handle')->primary();
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->string('role');
            $table->integer('solved_problems')->default(0);
            $table->date('last_active_day')->nullable();
            $table->integer('streak_days')->default(0);
            $table->integer('maximum_streak_days')->default(0);
            $table->integer('social_score')->default(0);
            $table->integer('technical_score')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};