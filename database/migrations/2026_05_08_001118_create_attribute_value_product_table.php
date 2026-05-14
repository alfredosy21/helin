<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('attribute_value_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('attribute_value_id')->constrained('attribute_values')->onDelete('cascade');
            $table->unique(['product_id', 'attribute_value_id'], 'prod_attr_unique');
            $table->text('notes')->nullable()->comment('Additional notes for this product attribute');
            $table->decimal('numeric_value', 10, 4)->nullable()->comment('Numeric value for calculations and ranges');
            $table->string('text_value')->nullable()->comment('Text value for search');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // Indexes for better performance
            $table->index(['product_id', 'attribute_value_id']);
            $table->index('attribute_value_id');
            $table->index(['numeric_value', 'attribute_value_id']);
            $table->index('text_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('attribute_value_product');
    }
};
