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
        Schema::table('products', function (Blueprint $table) {
            $table->string('spanish_name')->nullable()->after('name');
            $table->string('supplier_reference')->nullable()->after('sku');
            $table->string('dimensions')->nullable()->after('material');
            $table->foreignId('line_id')->nullable()->after('category_id')->constrained('lines')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['line_id']);
            $table->dropColumn(['spanish_name', 'supplier_reference', 'dimensions', 'line_id']);
        });
    }
};
