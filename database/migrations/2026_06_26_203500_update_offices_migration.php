<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('settings', function (Blueprint $table) {
            // Eliminar campos de Maracay (ya no se usa)
            $table->dropColumn(['maracay_whatsapp', 'maracay_location']);
            
            // Agregar campo de Maracaibo location (ya existe whatsapp)
            $table->text('maracaibo_location')->nullable()->after('maracaibo_whatsapp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('settings', function (Blueprint $table) {
            // Restaurar campos de Maracay
            $table->string('maracay_whatsapp')->nullable()->after('barquisimeto_location');
            $table->text('maracay_location')->nullable()->after('maracay_whatsapp');
            
            // Eliminar campo de Maracaibo location
            $table->dropColumn('maracaibo_location');
        });
    }
};
