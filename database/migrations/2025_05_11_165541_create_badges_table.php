<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Name of the badge');
            $table->text('goal')->nullable()->comment('What to do to get the badge');
            $table->text('link');
            $table->string('owner_id')->comment('Admin who created the badge');
            $table->timestamps();

            $table->foreign('owner_id')
            ->references('user_handle')
            ->on('users')
            ->onDelete('cascade');
            
            $table->index('owner_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};