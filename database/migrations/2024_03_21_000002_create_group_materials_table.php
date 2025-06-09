<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('group_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->string('title');
            $table->string('url');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('group_materials');
    }
}; 