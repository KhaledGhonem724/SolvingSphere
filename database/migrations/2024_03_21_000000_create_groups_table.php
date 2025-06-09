<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name')->unique();
            $table->text('description')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->enum('join_policy', ['free', 'apply_approve', 'invite_only'])->default('free');
            $table->integer('max_members')->default(50);
            $table->string('owner_id');
            $table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
}; 