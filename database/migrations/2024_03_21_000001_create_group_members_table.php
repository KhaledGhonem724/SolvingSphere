<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->string('user_id');
            $table->foreign('user_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->enum('role', ['owner', 'admin', 'moderator', 'member'])->default('member');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['group_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
}; 