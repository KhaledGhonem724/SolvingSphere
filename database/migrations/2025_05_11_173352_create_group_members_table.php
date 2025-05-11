<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->string('user_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('user_handle')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('group_id')
                ->references('id')
                ->on('groups')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
