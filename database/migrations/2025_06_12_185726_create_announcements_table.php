<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            // Announcer
            $table->string('user_id');
            $table->foreign('user_id')->references('user_handle')->on('users')->onDelete('cascade');

            $table->string('title')->nullable();
            $table->text('content')->nullable();

            // the Authority to which the announcement is sent 
            // NULL is general (public announcement for all admins)
            $table->foreignId('authority_id')->constrained()->onDelete('cascade')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
