<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {

    public function up(): void {
        Schema::table('cities', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->index('slug');
        });

        DB::table('cities')->orderBy('id')->chunkById(100, function ($cities) {
            foreach ($cities as $city) {
                DB::table('cities')
                    ->where('id', $city->id)
                    ->update(['slug' => Str::slug($city->name)]);
            }
        });
    }

    public function down(): void {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn('slug');
        });
    }
};
