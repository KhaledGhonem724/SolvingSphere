
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_badge', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->unsignedBigInteger('badge_id');
            $table->timestamps();

            $table->foreign('user_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->foreign('badge_id')->references('id')->on('badges')->onDelete('cascade');
            $table->index('user_id');
            $table->index('badge_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_badge');
    }
};