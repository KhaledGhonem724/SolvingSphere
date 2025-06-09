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

            // Notes
            $table->text('message')->nullable();
            $table->text('admin_notes')->nullable();

            // Status
            $table->enum('status', ['new', 'assigned', 'under_review', 'resolved', 'dropped'])->default('new');

            // Assigned moderator (custom PK)
            $table->string('assignee_id')->nullable();
            $table->foreign('assignee_id')->references('user_handle')->on('users')->nullOnDelete();

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
