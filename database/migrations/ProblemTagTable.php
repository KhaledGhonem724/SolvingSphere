<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('problem_tag', function (Blueprint $table) {
            $table->id();
            $table->string('problem_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('problem_id')->references('problem_handle')->on('problems')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->index('problem_id');
            $table->index('tag_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('problem_tag');
    }
};