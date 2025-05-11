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
            $table->string('language'); // Expected values: cpp, java, python
            $table->string('result');
            $table->boolean('ai_help')->default(false);
            $table->text('ai_response')->nullable();
            $table->string('owner_id');
            $table->string('problem_id');
            $table->timestamps();

            $table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->foreign('problem_id')->references('problem_handle')->on('problems')->onDelete('cascade');
            $table->index('owner_id');
            $table->index('problem_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
