<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FilmsController extends Controller
{
    /* ---------- Page Vue ---------- */
    public function index()
    {
        return Inertia::render('Films', ['title' => 'Films']);
    }

    /** Retourne les films depuis l’API TMDB en se basant sur les IDs en base */
    public function getMovies()
    {
        $apiKey = env('API_KEY');
        abort_unless($apiKey, 500, 'Missing API key');

        // On utilise tous les IDs TMDB présents dans la table
        $ids = Film::pluck('tmdb_id')->toArray();

        // Clé de cache basée sur la liste d'IDs
        $cacheKey = 'tmdb.movies.list.' . md5(json_encode($ids));

        $movies = Cache::remember($cacheKey, 21600, function () use ($apiKey, $ids) {
            $movies = [];

            foreach ($ids as $id) {
                $response = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
                    'api_key'  => $apiKey,
                    'language' => 'fr-FR',
                ]);

                if ($response->successful()) {
                    $movies[] = $response->json();
                }
            }

            // Trie les films par titre (alphabétique)
            usort($movies, fn ($a, $b) => strcasecmp($a['title'], $b['title']));
            return $movies;
        });

        return response()->json($movies);
    }

    /** Détail d’un film */
    public function getMovie(int $id)
    {
        $apiKey = env('API_KEY');
        abort_unless($apiKey, 500, 'Missing API key');

        $response = Http::get("https://api.themoviedb.org/3/movie/{$id}", [
            'api_key'  => $apiKey,
            'language' => 'fr-FR',
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'TMDb error'], $response->status());
        }

        return response()->json($response->json());
    }

    /** Dernier film (le plus récemment ajouté dans la table) */
    public function getLastMovie()
    {
        $lastId = Film::latest('id')->value('tmdb_id');
        return $this->getMovie($lastId);
    }
}


