<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

/**
 * ContactMessage Seeder
 *
 * This seeder creates the contact_messages table structure.
 * Messages are persisted via ContactController::send, not through this seeder.
 */
class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        // Tabla ya creada por migración; sin registros iniciales.
    }
}