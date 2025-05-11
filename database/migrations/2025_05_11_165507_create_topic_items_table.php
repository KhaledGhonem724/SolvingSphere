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
            $table->string('problem_id');
            $table->string('external_link')->nullable();
            $table->unsignedBigInteger('topic_id');
            $table->timestamps();

            $table->foreign('problem_id')->references('problem_handle')->on('problems')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
            $table->index('problem_id');
            $table->index('topic_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topic_items');
    }
};
