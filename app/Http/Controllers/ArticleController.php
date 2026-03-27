<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Film;
use App\Models\Montre;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class ArticleController extends Controller
{
    /**
     * Affiche un article par ID (utile pour l'admin/liaisons).
     */
    public function show(Article $article)
    {
        $article->load(['film', 'montre.marque']);

        $movie = $this->fetchTmdbMovie($article->film?->tmdb_id);

        return Inertia::render('Article', [
            'title' => $article->montre?->info_montre ?? 'Article',
            'article' => $article,
            'movie' => $movie,
        ]);
    }

    /**
     * SEO: /films/{tmdbId}/{slug?}
     * On retrouve le film local via tmdb_id, puis l'article associé.
     */
    public function showByFilmTmdb(int $tmdbId)
    {
        $film = Film::where('tmdb_id', $tmdbId)->firstOrFail();
        $article = Article::with(['film', 'montre.marque'])->where('id_film', $film->id)->firstOrFail();

        $movie = $this->fetchTmdbMovie($tmdbId);

        return Inertia::render('Article', [
            'title' => $article->montre?->info_montre ?? ($film->title ?? 'Film'),
            'article' => $article,
            'movie' => $movie,
        ]);
    }

    /**
     * Accès direct depuis une montre: /montres/{montre}
     */
    public function showByMontre(Montre $montre)
    {
        $article = Article::with(['film', 'montre.marque'])->where('id_montre', $montre->id_montre)->firstOrFail();

        $movie = $this->fetchTmdbMovie($article->film?->tmdb_id);

        return Inertia::render('Article', [
            'title' => $article->montre?->info_montre ?? 'Article',
            'article' => $article,
            'movie' => $movie,
        ]);
    }

    private function fetchTmdbMovie(?int $tmdbId): ?array
    {
        if (! $tmdbId) return null;

        $apiKey = env('API_KEY');
        if (! $apiKey) return null;

        $response = Http::get("https://api.themoviedb.org/3/movie/{$tmdbId}", [
            'api_key' => $apiKey,
            'language' => 'fr-FR',
        ]);

        return $response->successful() ? $response->json() : null;
    }
}
