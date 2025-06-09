<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Main containers table
        Schema::create('group_containers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['sheet', 'contest', 'topic']);
            $table->json('settings')->nullable();
            $table->integer('items_count')->default(0);
            $table->timestamp('last_active_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Sheets table
        Schema::create('group_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained('group_containers')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('problems')->nullable(); // List of problem IDs
            $table->json('settings')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Contests table
        Schema::create('group_contests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained('group_containers')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('problems')->nullable(); // List of problem IDs
            $table->json('settings')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Topics table
        Schema::create('group_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_id')->constrained('group_containers')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->json('attachments')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Comments table for topics
        Schema::create('group_topic_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('group_topics')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_topic_comments');
        Schema::dropIfExists('group_topics');
        Schema::dropIfExists('group_contests');
        Schema::dropIfExists('group_sheets');
        Schema::dropIfExists('group_containers');
    }
}; 