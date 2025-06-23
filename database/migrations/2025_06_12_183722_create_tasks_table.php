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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();

            // Reporter (or the admin who created the task - if the task is nor deriven from a report)
            $table->string('user_id')->nullable();
            $table->foreign('user_id')->references('user_handle')->on('users')->onDelete('cascade')->nullable();

            // the Authority to which the task is assigned 
            $table->foreignId('authority_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('report_id')->nullable()->constrained()->onDelete('cascade');

            // Type of report
            $table->enum('type', ['scientific', 'ethical', 'technical', 'other']);

            // message
            $table->text('user_note')->nullable();
            $table->text('manager_note')->nullable();
            $table->text('admin_note')->nullable();

            // the user handle of the assignee 
            $table->string('assignee_id')->nullable();
            $table->foreign('assignee_id')->references('user_handle')->on('users')->onDelete('cascade')->nullable();

            // Status
            $table->enum('status', ['free', 'assigned', 'refused', 'solved', 'dismissed'])->default('free');

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
        Schema::dropIfExists('tasks');
    }
};
