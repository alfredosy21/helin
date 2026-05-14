<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author')->nullable();
            $table->longText('content');
            $table->text('excerpt')->nullable()->comment('Excerpt for listings');
            $table->string('featured_image')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->foreignId('blog_category_id')->nullable()->constrained('blog_categories')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_pinned')->default(false)->comment('Pinned to homepage');
            $table->integer('view_count')->default(0)->comment('View counter');
            $table->integer('read_time')->nullable()->comment('Estimated reading time in minutes');
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('share_count')->default(0);
            $table->timestamp('published_at')->nullable()->comment('Publication date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // Indexes for better performance
            $table->index(['is_active', 'is_featured']);
            $table->index(['is_active', 'is_pinned']);
            $table->index(['blog_category_id', 'is_active']);
            $table->index('author');
            $table->index('published_at');
            $table->index(['view_count', 'like_count']);
            $table->index(['is_active', 'published_at']);

            // Full-text indexes for search
            $table->fullText(['title', 'content']);
            $table->fullText(['title', 'excerpt', 'content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('blogs');
    }
};
