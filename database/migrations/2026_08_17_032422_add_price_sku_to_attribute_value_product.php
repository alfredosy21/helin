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
        Schema::table('attribute_value_product', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('numeric_value');
            $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            $table->string('sku')->nullable()->after('text_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_value_product', function (Blueprint $table) {
            $table->dropColumn(['price', 'sale_price', 'sku']);
        });
    }
};
