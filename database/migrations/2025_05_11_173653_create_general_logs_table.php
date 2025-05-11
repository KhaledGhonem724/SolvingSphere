<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('general_logs', function (Blueprint $table) {
            $table->id();
            $table->text('action'); // e.g., Create, Delete, Edit
            $table->string('actor_id');
            $table->text('object'); // e.g., User, Group, Blog
            $table->string('object_id')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();

            $table->foreign('actor_id')
                ->references('user_handle')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_logs');
    }
};
