<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Article;
use App\Models\Film;
use App\Models\Montre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['film', 'montre.marque'])->get();
        return Inertia::render('admin/Articles', [
        'title' => 'Articles',
        'articles' => $articles,
        ]);
    }

    public function create()
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
        
        $montres = Montre::with('marque')->get();
        return Inertia::render('admin/ArticlesCreate', [
        'title'   => 'Création article',
        'films'   => $filmsWithTitles,
        'montres' => $montres,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'affiche_film' => 'nullable|image|max:4096',
            'images_montre.*' => 'nullable|image|max:4096',
            'mouvement_montre' => 'nullable|string|max:100',
            'en_vente' => 'boolean',
            'lien_vente' => 'nullable|url',
            'taille_montre' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'id_film' => 'required|exists:films,id',
            'id_montre' => 'required|exists:montres,id_montre',
        ]);

        // Upload images
        if ($request->hasFile('affiche_film')) {
            $validated['affiche_film'] = $request->file('affiche_film')->store('articles/affiches', 'public');
        }

        if ($request->hasFile('images_montre')) {
            $images = [];
            foreach ($request->file('images_montre') as $image) {
                $images[] = $image->store('articles/montres', 'public');
            }
            $validated['images_montre'] = $images;
        }

        Article::create($validated);
        return redirect()->route('admin.articles.index')->with('success', 'Article créé avec succès !');
    }

    public function edit(Article $article)
    {
        $films = Film::all();
        $montres = Montre::with('marque')->get();
        return Inertia::render('Admin/Articles/Edit', compact('article', 'films', 'montres'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'affiche_film' => 'nullable|image|max:4096',
            'images_montre.*' => 'nullable|image|max:4096',
            'mouvement_montre' => 'nullable|string|max:100',
            'en_vente' => 'boolean',
            'lien_vente' => 'nullable|url',
            'taille_montre' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'id_film' => 'required|exists:films,id',
            'id_montre' => 'required|exists:montres,id_montre',
        ]);

        // Upload images et suppression anciennes
        if ($request->hasFile('affiche_film')) {
            if ($article->affiche_film) {
                Storage::disk('public')->delete($article->affiche_film);
            }
            $validated['affiche_film'] = $request->file('affiche_film')->store('articles/affiches', 'public');
        }

        if ($request->hasFile('images_montre')) {
            if ($article->images_montre) {
                foreach ($article->images_montre as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $images = [];
            foreach ($request->file('images_montre') as $image) {
                $images[] = $image->store('articles/montres', 'public');
            }
            $validated['images_montre'] = $images;
        }

        $article->update($validated);
        return redirect()->route('admin.articles.index')->with('success', 'Article mis à jour !');
    }

    public function destroy(Article $article)
    {
        if ($article->affiche_film) Storage::disk('public')->delete($article->affiche_film);
        if ($article->images_montre) {
            foreach ($article->images_montre as $img) {
                Storage::disk('public')->delete($img);
            }
        }
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article supprimé !');
    }
}
