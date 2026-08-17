<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Check users
$users = App\Models\User::all();
echo "Total users: " . $users->count() . PHP_EOL;
foreach ($users as $u) {
    echo "  id={$u->id} name={$u->name} email={$u->email} rol_id={$u->rol_id}" . PHP_EOL;
}

// Check roles
$roles = App\Models\Role::all();
echo PHP_EOL . "Roles: " . $roles->count() . PHP_EOL;
foreach ($roles as $r) {
    echo "  id={$r->id} name={$r->name} slug={$r->slug}" . PHP_EOL;
}

// Check modules & submodules
echo PHP_EOL . "Modules: " . App\Models\Module::count() . PHP_EOL;
echo "Submodules: " . App\Models\Submodule::count() . PHP_EOL;
