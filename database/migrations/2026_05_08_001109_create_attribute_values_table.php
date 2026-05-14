<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
            $table->string('value')->comment('Attribute value: 3.5mm, Steel, Titanium');
            $table->string('label')->nullable()->comment('Display label: 3.5 mm, Stainless Steel');
            $table->text('description')->nullable()->comment('Value description');
            $table->string('color')->nullable()->comment('Color for visual identification');
            $table->integer('position')->default(0)->comment('Display order');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // Indexes
            $table->index(['attribute_id', 'is_active']);
            $table->index(['attribute_id', 'position']);
            $table->fullText(['value', 'label', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('attribute_values');
    }
};
