<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topic_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id');
            $table->foreignId('problem_id')->constrained()->onDelete('cascade');
            $table->string('external_link')->nullable();
            $table->timestamps();

            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
            $table->index('topic_id');
            $table->index('problem_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topic_items');
    }
};
