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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            // Reporter (custom PK)
            $table->string('user_id');
            $table->foreign('user_id')->references('user_handle')->on('users')->onDelete('cascade');

            // Type of report
            $table->enum('type', ['scientific', 'ethical', 'technical', 'other']);

            // message
            $table->text('message')->nullable();

            // Status
            $table->enum('status', ['new', 'reviewed', 'ignored'])->default('new');

            // Polymorphic relation (nullable)
            $table->unsignedBigInteger('reportable_id')->nullable();
            $table->string('reportable_type')->nullable();
            $table->index(['reportable_type', 'reportable_id']);

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
