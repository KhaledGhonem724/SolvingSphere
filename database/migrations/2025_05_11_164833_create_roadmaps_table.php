<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roadmaps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('visibility', ['public', 'private']);
            $table->timestamps();
            $table->string('owner_id');

            $table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roadmaps');
    }
};
