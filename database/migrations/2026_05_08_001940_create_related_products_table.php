<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('related_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            // The main product being queried
            $table->foreignId('product_id')
                    ->constrained('products')
                    ->onDelete('cascade');

            // The suggested related product
            $table->foreignId('related_id')
                    ->constrained('products')
                    ->onDelete('cascade');

            // Optional: To define if it's an accessory, replacement, or similar product
            $table->string('relation_type')->nullable()->comment('Example: accessory, similar, suggested');

            // Prevent a product from relating to itself or duplicating the relationship
            $table->unique(['product_id', 'related_id']);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('related_products');
    }
};
