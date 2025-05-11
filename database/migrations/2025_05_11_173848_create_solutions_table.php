<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_id');
            $table->string('problem_id');
            $table->timestamps(); // includes created_at and updated_at

            $table->foreign('blog_id')
                ->references('id')
                ->on('blogs')
                ->onDelete('cascade');

            $table->foreign('problem_id')
                ->references('problem_handle')
                ->on('problems')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
