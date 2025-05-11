<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('origin_comment_id');
            $table->unsignedBigInteger('reply_comment_id');
            $table->timestamps();

            $table->foreign('origin_comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');

            $table->foreign('reply_comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('replies');
    }
};
