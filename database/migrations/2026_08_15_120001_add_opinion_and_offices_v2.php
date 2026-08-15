<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Añade opinion_url y offices JSON a settings, y elimina las columnas
     * individuales *_location y *_whatsapp que ya son obsoletas.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('opinion_url')->nullable()->after('analytics_code');
            $table->json('offices')->nullable()->after('opinion_url');

            // Consolidar los datos de las columnas individuales al JSON offices
            $row = DB::table('settings')->first();
            if ($row) {
                $offices = [];
                foreach ([
                    'caracas' => 'Caracas',
                    'valencia' => 'Valencia',
                    'barquisimeto' => 'Barquisimeto',
                    'maracay' => 'Maracay',
                    'maracaibo' => 'Maracaibo',
                ] as $key => $name) {
                    $whatsapp = $row->{"{$key}_whatsapp"} ?? null;
                    $url = $row->{"{$key}_location"} ?? null;
                    if (!empty($whatsapp) || !empty($url)) {
                        $offices[] = [
                            'name' => $name,
                            'url' => $url,
                            'whatsapp' => $whatsapp,
                            'active' => !empty($whatsapp),
                        ];
                    }
                }
                if (count($offices) > 0) {
                    DB::table('settings')->where('id', $row->id)->update([
                        'offices' => json_encode($offices, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ]);
                }
            }
        });

        // Eliminar columnas individuales obsoletas
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['caracas_location', 'valencia_location', 'barquisimeto_location', 'maracaibo_location', 'maracay_location']);
            $table->dropColumn(['caracas_whatsapp', 'valencia_whatsapp', 'barquisimeto_whatsapp', 'maracaibo_whatsapp', 'maracay_whatsapp']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * Restauraría las columnas individuales y quitaría opinion_url/offices.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Restauraría las columnas individuales (valores por defecto)
            $table->string('caracas_location')->nullable()->after('valencia_location');
            $table->string('caracas_whatsapp')->nullable()->after('caracas_location');
            $table->string('valencia_location')->nullable()->after('caracas_whatsapp');
            $table->string('barquisimeto_location')->nullable()->after('valencia_location');
            $table->string('barquisimeto_whatsapp')->nullable()->after('barquisimeto_location');
            $table->string('maracaibo_location')->nullable()->after('barquisimeto_whatsapp');
            $table->string('maracaibo_whatsapp')->nullable()->after('maracaibo_whatsapp');
            $table->string('maracay_location')->nullable()->after('maracaibo_whatsapp');
            $table->string('maracay_whatsapp')->nullable()->after('maracaibo_whatsapp');

            // Quitar opinion_url y offices
            $table->dropColumn(['opinion_url', 'offices']);
        });
    }
};