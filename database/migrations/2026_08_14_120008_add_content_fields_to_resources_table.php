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
        Schema::table('resources', function (Blueprint $table) {
            $table->longText('content')->nullable()->after('description');
            $table->text('diagnosis')->nullable()->after('content');
            $table->json('gallery')->nullable()->after('diagnosis');
            $table->string('video_url')->nullable()->after('gallery');
            $table->json('materials')->nullable()->after('video_url');
            $table->longText('results')->nullable()->after('materials');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->dropColumn(['content', 'diagnosis', 'gallery', 'video_url', 'materials', 'results']);
        });
    }
};
