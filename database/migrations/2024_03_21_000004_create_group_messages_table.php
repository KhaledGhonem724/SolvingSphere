<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->string('user_id');
            $table->foreign('user_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_pinned')->default(false);
            $table->json('read_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Create table for message reactions
        Schema::create('group_message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('group_messages')->onDelete('cascade');
            $table->string('user_id');
            $table->foreign('user_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->string('reaction', 20);
            $table->timestamps();

            $table->unique(['message_id', 'user_id', 'reaction']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_message_reactions');
        Schema::dropIfExists('group_messages');
    }
}; 