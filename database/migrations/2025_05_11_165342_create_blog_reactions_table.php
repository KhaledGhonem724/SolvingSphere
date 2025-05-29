<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// DONE
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_reactions', function (Blueprint $table) {
            $table->id();
            $table->string('owner_id');
            $table->enum('reaction', ['upvoting', 'downvoting']); // Values: upvoting, downvoting
            $table->timestamps();
        
            //$table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->index(['blog_id', 'owner_id']);
        });
        
    }


    public function down(): void
    {
        Schema::dropIfExists('blog_reactions');
    }
};
