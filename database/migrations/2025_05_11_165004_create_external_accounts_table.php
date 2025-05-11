<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_accounts', function (Blueprint $table) {
            $table->string('website');
            $table->string('username');
            $table->string('password');
            $table->string('owner_id');
            $table->timestamps();

            $table->primary(['website', 'owner_id']);
            $table->foreign('owner_id')->references('user_handle')->on('users')->onDelete('cascade');
            $table->index(['website', 'owner_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_accounts');
    }
};
