<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sheets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->string('owner_id');
            $table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->timestamps(); // ✅ Adds both created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sheets');
    }
};
