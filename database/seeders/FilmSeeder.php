<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Film;

class FilmSeeder extends Seeder
{
    public function run(): void
    {
        // On part du tableau du fichier config
        collect(config('movies.ids'))
            ->each(fn (int $id) => Film::firstOrCreate(['tmdb_id' => $id]));
    }
}
