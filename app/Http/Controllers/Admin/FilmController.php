<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class FilmController extends Controller
{
    public function index()
    {
        // On récupère directement les titres stockés
        $films = Film::orderBy('id')->get(['id', 'tmdb_id', 'title']);

        return Inertia::render('admin/Films', [
            'films' => $films,
            'title' => 'Films'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tmdb_id' => ['required', 'integer', 'unique:films,tmdb_id'],
        ]);

        // Récupérer le titre depuis TMDB au moment de l'ajout
        $apiKey = env('API_KEY');
        abort_unless($apiKey, 500, 'Missing API key');

        $response = Http::get("https://api.themoviedb.org/3/movie/{$request->tmdb_id}", [
            'api_key' => $apiKey,
            'language' => 'fr-FR',
        ]);

        $title = $response->successful() ? $response->json('title') : 'Titre indisponible';

        Film::create([
            'tmdb_id' => $request->tmdb_id,
            'title'   => $title,
        ]);

        return redirect()->route('admin.films.index')->with('success', 'Film ajouté.');
    }

    public function destroy(Film $film)
    {
        $film->delete();

        return redirect()->route('admin.films.index')->with('success', 'Film supprimé.');
    }
}
