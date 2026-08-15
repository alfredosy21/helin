<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create pivot table
        Schema::create('state_whatsapp_number', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_number_id')->constrained('whatsapp_numbers')->onDelete('cascade');
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['whatsapp_number_id', 'state_id']);
        });

        // 2. Migrate existing data from whatsapp_numbers.state_id to pivot
        $existing = DB::table('whatsapp_numbers')->whereNotNull('state_id')->get(['id', 'state_id']);
        foreach ($existing as $row) {
            DB::table('state_whatsapp_number')->insertOrIgnore([
                'whatsapp_number_id' => $row->id,
                'state_id' => $row->state_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Drop foreign key, unique constraint and column from whatsapp_numbers
        Schema::table('whatsapp_numbers', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropUnique(['phone_number', 'state_id']);
            $table->dropColumn('state_id');
        });

        // 4. Add unique on phone_number only
        Schema::table('whatsapp_numbers', function (Blueprint $table) {
            $table->unique('phone_number');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_numbers', function (Blueprint $table) {
            $table->dropUnique(['phone_number']);
            $table->foreignId('state_id')->constrained('states')->onDelete('cascade')->after('id');
            $table->unique(['phone_number', 'state_id']);
        });

        // Restore data from pivot back to whatsapp_numbers
        $pivotRows = DB::table('state_whatsapp_number')->get();
        foreach ($pivotRows as $row) {
            DB::table('whatsapp_numbers')
                ->where('id', $row->whatsapp_number_id)
                ->update(['state_id' => $row->state_id]);
        }

        Schema::dropIfExists('state_whatsapp_number');
    }
};
