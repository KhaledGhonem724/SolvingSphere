<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sheet_problem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sheet_id');
            $table->string('problem_id');
            $table->timestamps();

            $table->foreign('sheet_id')->references('id')->on('sheets')->onDelete('cascade');
            $table->foreign('problem_id')->references('problem_handle')->on('problems')->onDelete('cascade');
            $table->index('sheet_id');
            $table->index('problem_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sheet_problem');
    }
};