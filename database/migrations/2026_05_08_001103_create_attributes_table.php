<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->comment('Attribute name: Diameter, Length, Material');
            $table->string('slug')->unique()->comment('Slug for URLs and references');
            $table->string('type')->default('text')->comment('Type: text, number, select, boolean');
            $table->text('description')->nullable()->comment('Attribute description');
            $table->string('unit')->nullable()->comment('Unit of measurement: mm, cm, gr, ml');
            $table->json('options')->nullable()->comment('Options for select type');
            $table->boolean('is_required')->default(false)->comment('If required');
            $table->boolean('is_filterable')->default(true)->comment('If filterable');
            $table->integer('position')->default(0)->comment('Display order');
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            // Indexes
            $table->index(['is_active', 'is_filterable']);
            $table->index('type');
            $table->index('position');
            $table->fullText(['name', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('attributes');
    }
};
