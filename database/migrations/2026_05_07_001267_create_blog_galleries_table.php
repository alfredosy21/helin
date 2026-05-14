<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('blog_galleries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->string('title')->nullable()->comment('Image title');
            $table->string('file_path')->comment('File path');
            $table->string('file_name')->nullable()->comment('Original file name');
            $table->string('mime_type')->nullable()->comment('MIME type');
            $table->integer('file_size')->nullable()->comment('File size in bytes');
            $table->string('alt_text')->nullable()->comment('Alt text for SEO');
            $table->text('description')->nullable()->comment('Image description');
            $table->string('thumbnail')->nullable()->comment('Thumbnail for optimization');
            $table->integer('position')->default(0)->comment('Order in gallery');
            $table->boolean('is_featured')->default(false)->comment('Featured image');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // Indexes for better performance
            $table->index(['blog_id', 'position']);
            $table->index(['blog_id', 'is_featured']);
            $table->index(['blog_id', 'is_active']);
            $table->index('mime_type');
            $table->index(['is_active', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('blog_galleries');
    }
};
