<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->string('sender_id');
            $table->string('receiver_id');
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps(); // includes created_at and updated_at

            $table->foreign('sender_id')
                ->references('user_handle')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('receiver_id')
                ->references('user_handle')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
