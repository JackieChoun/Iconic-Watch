<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Film;

class FilmSeeder extends Seeder
{
    public function run(): void
    {
        $apiKey = config('services.tmdb.key');

        collect(config('movies.ids'))->each(function (int $tmdbId) use ($apiKey) {

            $response = Http::get(
                "https://api.themoviedb.org/3/movie/{$tmdbId}",
                [
                    'api_key' => $apiKey,
                    'language' => 'fr-FR',
                ]
            );

            if (! $response->successful()) {
                return;
            }

            Film::updateOrCreate(
                ['tmdb_id' => $tmdbId],
                ['title' => $response->json('title')]
            );
        });
    }
}
