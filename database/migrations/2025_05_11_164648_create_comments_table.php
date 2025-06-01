<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// DONE
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {     
            $table->id();
            $table->text('content');
            $table->string('commenter_id');
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('commenter_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->index(['blog_id', 'commenter_id']);
            $table->index('parent_id');            
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
