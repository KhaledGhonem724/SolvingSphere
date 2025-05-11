<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('content');
            $table->enum('blog_type', ['question', 'discussion', 'explain']);
            $table->integer('score')->default(0);
            $table->string('owner_id');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');

            // Indexing for the foreign key
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
