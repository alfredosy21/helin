<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Añade opinion_url y offices (JSON) a settings y consolida los campos
     * individuales *_location / *_whatsapp en el JSON offices con la estructura
     * [{name, url, whatsapp, active}]. Las columnas individuales se conservan
     * (se eliminan en una migración posterior tras verificar).
     */
    public function up(): void
    {
        if (!Schema::hasColumn('settings', 'opinion_url')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('opinion_url')->nullable()->after('analytics_code');
            });
        }
        if (!Schema::hasColumn('settings', 'offices')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->json('offices')->nullable()->after('opinion_url');
            });
        }

        $row = DB::table('settings')->first();
        if ($row && empty($row->offices)) {
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
                if (empty($whatsapp) && empty($url)) {
                    continue;
                }
                $offices[] = [
                    'name' => $name,
                    'url' => $url,
                    'whatsapp' => $whatsapp,
                    'active' => true,
                ];
            }

            if (count($offices) > 0) {
                DB::table('settings')->where('id', $row->id)->update([
                    'offices' => json_encode($offices, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $row = DB::table('settings')->first();
        if ($row && isset($row->offices)) {
            $offices = json_decode($row->offices, true);
            $offices = is_array($offices) ? $offices : [];
            $updates = [];
            $cityKeys = [
                'caracas' => 'caracas',
                'valencia' => 'valencia',
                'barquisimeto' => 'barquisimeto',
                'maracay' => 'maracay',
                'maracaibo' => 'maracaibo',
            ];
            foreach ($offices as $office) {
                $name = strtolower(trim((string) ($office['name'] ?? '')));
                if (isset($cityKeys[$name])) {
                    $updates["{$cityKeys[$name]}_location"] = $office['url'] ?? null;
                    $updates["{$cityKeys[$name]}_whatsapp"] = $office['whatsapp'] ?? null;
                }
            }
            if (count($updates) > 0) {
                DB::table('settings')->where('id', $row->id)->update($updates);
            }
        }

        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'opinion_url')) {
                $table->dropColumn('opinion_url');
            }
            if (Schema::hasColumn('settings', 'offices')) {
                $table->dropColumn('offices');
            }
        });
    }
};
