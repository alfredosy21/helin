<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('product_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->enum('type', ['image', 'document', 'video'])->default('image');
            $table->string('alt_text')->nullable()->comment('Alt text for SEO');
            $table->string('title')->nullable()->comment('Image title');
            $table->string('label')->nullable()->comment('Descriptive label');
            $table->boolean('is_main')->default(false)->comment('Main image');
            $table->boolean('is_featured')->default(false)->comment('Featured image in gallery');
            $table->integer('position')->default(0)->comment('Order in gallery');
            $table->string('thumbnail')->nullable()->comment('Thumbnail for optimization');
            $table->text('description')->nullable()->comment('Media description');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // Indexes for better performance
            $table->index(['product_id', 'type']);
            $table->index(['product_id', 'is_main']);
            $table->index(['product_id', 'position']);
            $table->index(['type', 'is_featured']);
            $table->index('mime_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('product_media');
    }
};
