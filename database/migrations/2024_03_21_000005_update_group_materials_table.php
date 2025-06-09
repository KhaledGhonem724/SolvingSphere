<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('group_materials', function (Blueprint $table) {
            // Add new columns for material types and stats
            $table->enum('type', ['video', 'document', 'link'])->default('document')->after('url');
            $table->text('description')->nullable()->after('title');
            $table->integer('views_count')->default(0)->after('type');
            $table->json('metadata')->nullable()->after('views_count');
            $table->timestamp('last_viewed_at')->nullable()->after('metadata');
        });
    }

    public function down()
    {
        Schema::table('group_materials', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'description',
                'views_count',
                'metadata',
                'last_viewed_at'
            ]);
        });
    }
}; 