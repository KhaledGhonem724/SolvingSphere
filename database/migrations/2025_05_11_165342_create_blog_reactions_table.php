<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_reactions', function (Blueprint $table) {
            $table->id();
            $table->string('reaction'); // Values: upvoting, downvoting
            $table->string('owner_id');
            $table->unsignedBigInteger('blog_id');
            $table->timestamps();

            $table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->foreign('blog_id')->references('id')->on('blogs')->onDelete('cascade');
            $table->index('owner_id');
            $table->index('blog_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_reactions');
    }
};
