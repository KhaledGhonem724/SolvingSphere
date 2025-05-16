
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->string('problem_handle')->primary();
            $table->string('link');
            $table->string('website');
            $table->string('title');
            $table->string('timelimit');
            $table->string('memorylimit');
            $table->text('statement');
            $table->text('testcases');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('problems');
    }
};