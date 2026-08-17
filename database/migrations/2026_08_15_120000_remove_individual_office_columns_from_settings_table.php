<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Elimina las columnas individuales *_location y *_whatsapp de settings
     * ya sustituidas por el JSON offices. Ejecutar después de verificar
     * que offices JSON funciona correctamente.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['caracas_location', 'valencia_location', 'barquisimeto_location', 'maracaibo_location', 'maracay_location']);
            $table->dropColumn(['caracas_whatsapp', 'valencia_whatsapp', 'barquisimeto_whatsapp', 'maracaibo_whatsapp', 'maracay_whatsapp']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * Restauraría las columnas individuales si fuera necesario.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('caracas_location')->nullable()->after('valencia_location');
            $table->string('caracas_whatsapp')->nullable()->after('caracas_location');
            $table->string('valencia_location')->nullable()->after('caracas_whatsapp');
            $table->string('barquisimeto_location')->nullable()->after('valencia_location');
            $table->string('barquisimeto_whatsapp')->nullable()->after('barquisimeto_location');
            $table->string('maracaibo_location')->nullable()->after('barquisimeto_whatsapp');
            $table->string('maracaibo_whatsapp')->nullable()->after('barquisimeto_whatsapp');
            $table->string('maracay_location')->nullable()->after('maracaibo_whatsapp');
            $table->string('maracay_whatsapp')->nullable()->after('maracaibo_whatsapp');
        });
    }
};