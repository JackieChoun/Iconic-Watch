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
        $films = Film::orderBy('id')->get(['id', 'tmdb_id']);

        $apiKey = env('API_KEY');
        abort_unless($apiKey, 500, 'Missing API key');

        $filmsWithTitles = $films->map(function ($film) use ($apiKey) {
            $response = Http::get("https://api.themoviedb.org/3/movie/{$film->tmdb_id}", [
                'api_key' => $apiKey,
                'language' => 'fr-FR',
            ]);
            $film->title = $response->successful() ? $response->json('title') : 'Titre indisponible';
            return $film;
        });

        return Inertia::render('admin/Films', [
            'films' => $filmsWithTitles
        ]);
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'tmdb_id' => ['required', 'integer', 'unique:films,tmdb_id'],
        ]);

        Film::create($request->only('tmdb_id'));

        return redirect()->route('films.index')->with('success', 'Film ajouté.');
    }

    public function edit(Film $film)
    {
        
    }

    public function update(Request $request, Film $film)
    {
        
    }

    public function destroy(Film $film)
    {
        $film->delete();

        return redirect()->route('films.index')->with('success', 'Film supprimé.');
    }
}
