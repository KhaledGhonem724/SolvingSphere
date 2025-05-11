<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->string('owner_id');
            $table->timestamps();

            $table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
