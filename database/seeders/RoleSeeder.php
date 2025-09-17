<?php
// database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->upsert(
            [
                ['id' => 1, 'nom_role' => 'utilisateur'],
                ['id' => 2, 'nom_role' => 'admin'],
            ],
            ['id'],           // clé unique
            ['nom_role']      // colonnes à mettre à jour si l’id existe déjà
        );
    }
}

