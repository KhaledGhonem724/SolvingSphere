<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_join_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->string('user_id');
            $table->foreign('user_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('response')->nullable();
            $table->timestamps();

            $table->unique(['group_id', 'user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_join_requests');
    }
}; 