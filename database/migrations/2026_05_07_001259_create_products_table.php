<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->longText('description');
            $table->longText('clinical_specs')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->integer('stock')->default(0);
            $table->string('unit')->default('unidad');
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false)->comment('New product');
            $table->boolean('is_on_sale')->default(false)->comment('On sale');
            $table->decimal('sale_price', 10, 2)->nullable()->comment('Sale price');
            $table->date('sale_start_date')->nullable();
            $table->date('sale_end_date')->nullable();
            $table->integer('view_count')->default(0)->comment('View counter');
            $table->integer('search_count')->default(0)->comment('Search counter');
            $table->decimal('rating', 3, 2)->default(0)->comment('Average rating');
            $table->integer('review_count')->default(0);
            $table->timestamp('published_at')->nullable()->comment('Publication date');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            // Indexes for better performance
            $table->index(['is_active', 'is_featured']);
            $table->index(['is_active', 'is_new']);
            $table->index(['is_active', 'is_on_sale']);
            $table->index(['category_id', 'is_active']);
            $table->index('sku');
            $table->index('brand_id');
            $table->index(['price', 'currency']);
            $table->index(['rating', 'review_count']);
            $table->index('published_at');
            $table->index(['is_on_sale', 'sale_start_date', 'sale_end_date']);

            // Full-text indexes for search
            $table->fullText(['name', 'sku', 'description']);
            $table->fullText(['name', 'description', 'clinical_specs']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
