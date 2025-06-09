<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->text('code');
            $table->enum('language',['cpp', 'java', 'python'])->default('cpp');
            $table->string('oj_response');
            $table->enum('result',['solved', 'attempted', 'todo'])->default('todo');
            $table->string('original_link')->nullable();
            $table->text('ai_response')->nullable();
            $table->string('owner_id');
            $table->foreignId('problem_id')->constrained('problems')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->index('owner_id');
            $table->index('problem_id');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
