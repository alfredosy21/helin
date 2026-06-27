<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfficesAndContactToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Dirección de contacto general
            $table->text('contact_address')->nullable()->after('address');
            
            // Oficinas - WhatsApp y ubicación
            $table->string('caracas_whatsapp')->nullable()->after('contact_address');
            $table->text('caracas_location')->nullable()->after('caracas_whatsapp');
            
            $table->string('valencia_whatsapp')->nullable()->after('caracas_location');
            $table->text('valencia_location')->nullable()->after('valencia_whatsapp');
            
            $table->string('barquisimeto_whatsapp')->nullable()->after('valencia_location');
            $table->text('barquisimeto_location')->nullable()->after('barquisimeto_whatsapp');
            
            $table->string('maracay_whatsapp')->nullable()->after('barquisimeto_location');
            $table->text('maracay_location')->nullable()->after('maracay_whatsapp');
            
            $table->string('maracaibo_whatsapp')->nullable()->after('maracay_location');
            $table->text('maracaibo_location')->nullable()->after('maracaibo_whatsapp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'contact_address',
                'caracas_whatsapp',
                'caracas_location',
                'valencia_whatsapp',
                'valencia_location',
                'barquisimeto_whatsapp',
                'barquisimeto_location',
                'maracay_whatsapp',
                'maracay_location',
                'maracaibo_whatsapp',
                'maracaibo_location',
            ]);
        });
    }
}
