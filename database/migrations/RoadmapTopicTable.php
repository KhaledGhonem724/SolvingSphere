
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roadmap_topic', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('roadmap_id');
            $table->unsignedBigInteger('topic_id');
            $table->timestamps();

            $table->foreign('roadmap_id')->references('id')->on('roadmaps')->onDelete('cascade');
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
            $table->index('roadmap_id');
            $table->index('topic_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roadmap_topic');
    }
};