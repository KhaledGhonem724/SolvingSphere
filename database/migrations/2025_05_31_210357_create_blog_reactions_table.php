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
        Schema::create('blog_reactions', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->tinyInteger('value'); // 1 for upvote, -1 for downvote
            $table->timestamps();

            $table->foreign('user_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->unique(['blog_id', 'user_id']); // Each user can vote once per blog

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_reactions');
    }
};
