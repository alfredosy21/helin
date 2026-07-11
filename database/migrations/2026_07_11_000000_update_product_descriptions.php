<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        $additionalText = ' Fabricado con materiales de alta calidad para garantizar durabilidad, precisión y seguridad en cada procedimiento.';

        DB::table('products')->orderBy('id')->chunkById(100, function ($products) use ($additionalText) {
            foreach ($products as $product) {
                if (str_contains($product->description, $additionalText)) {
                    continue;
                }

                DB::table('products')
                    ->where('id', $product->id)
                    ->update([
                        'description' => $product->description . $additionalText,
                        'updated_at' => now(),
                    ]);
            }
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        $additionalText = ' Fabricado con materiales de alta calidad para garantizar durabilidad, precisión y seguridad en cada procedimiento.';

        DB::table('products')->orderBy('id')->chunkById(100, function ($products) use ($additionalText) {
            foreach ($products as $product) {
                if (!str_ends_with($product->description, $additionalText)) {
                    continue;
                }

                DB::table('products')
                    ->where('id', $product->id)
                    ->update([
                        'description' => substr($product->description, 0, -strlen($additionalText)),
                        'updated_at' => now(),
                    ]);
            }
        });
    }
};
