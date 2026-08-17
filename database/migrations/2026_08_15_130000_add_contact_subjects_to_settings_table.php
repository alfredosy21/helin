<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Añade el campo contact_subjects (JSON) a settings para gestionar
     * los asuntos del formulario de contacto desde el CMS.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('settings', 'contact_subjects')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->json('contact_subjects')->nullable()->after('offices');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'contact_subjects')) {
                $table->dropColumn('contact_subjects');
            }
        });
    }
};
