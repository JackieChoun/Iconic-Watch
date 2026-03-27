<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Film;
use App\Models\Montre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ArticleController extends Controller
{
    /* =========================
     * Liste des articles
     * ========================= */
    public function index()
    {
        $articles = Article::with(['film', 'montre.marque'])->get();

        return Inertia::render('admin/Articles', [
            'articles' => $articles,
            'title' => 'Articles' 
        ]);
    }

    /* =========================
     * Formulaire création
     * ========================= */
    public function create()
    {
        $films = Film::orderBy('id')->get(['id', 'tmdb_id']);

        $apiKey = env('API_KEY');
        abort_unless($apiKey, 500, 'Missing API key');

        $films = $films->map(function ($film) use ($apiKey) {
            $response = Http::get(
                "https://api.themoviedb.org/3/movie/{$film->tmdb_id}",
                ['api_key' => $apiKey, 'language' => 'fr-FR']
            );

            $film->title = $response->successful()
                ? $response->json('title')
                : 'Titre indisponible';

            return $film;
        });

        $montres = Montre::with('marque')->get();

        return Inertia::render('admin/ArticlesCreate', [
            'films'   => $films,
            'montres' => $montres,
        ]);
    }

    /* =========================
     * Enregistrement création
     * ========================= */
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_film'          => ['required', 'exists:films,id'],
            'id_montre'        => ['required', 'exists:montres,id_montre'],
            'description'      => ['nullable', 'string'],
            'mouvement_montre' => ['nullable', 'string'],
            'taille_montre'    => ['nullable', 'string'],
            'en_vente'         => ['boolean'],
            'lien_vente'       => ['nullable', 'required_if:en_vente,1', 'url'],
            'affiche_film'     => ['nullable', 'image', 'max:4096'],
            'images_montre'    => ['nullable', 'array', 'max:4'],
            'images_montre.*'  => ['image', 'max:4096'],
        ]);

        /* Affiche film */
        if ($request->hasFile('affiche_film')) {
            $data['affiche_film'] = $request
                ->file('affiche_film')
                ->store('articles/affiches', 'public');
        }

        /* Images montre */
        if ($request->hasFile('images_montre')) {
            $data['images_montre'] = [];

            foreach ($request->file('images_montre') as $file) {
                if (count($data['images_montre']) >= 4) break;

                $data['images_montre'][] = $file
                    ->store('articles/montres', 'public');
            }
        }

        Article::create($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article créé');
    }

    /* =========================
     * Formulaire édition
     * ========================= */
    public function edit(Article $article)
    {
        $films   = Film::all();
        $montres = Montre::with('marque')->get();

        return Inertia::render('admin/ArticlesEdit', [
            'article' => $article,
            'films'   => $films,
            'montres' => $montres,
        ]);
    }

    /* =========================
     * Mise à jour
     * ========================= */
    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'id_film'          => ['required', 'exists:films,id'],
            'id_montre'        => ['required', 'exists:montres,id_montre'],
            'description'      => ['nullable', 'string'],
            'mouvement_montre' => ['nullable', 'string'],
            'taille_montre'    => ['nullable', 'string'],
            'en_vente'         => ['nullable'],
            'lien_vente'       => ['nullable', 'required_if:en_vente,1', 'url'],
            'affiche_film'     => ['nullable', 'image', 'max:4096'],
            'images_montre'    => ['nullable', 'array', 'max:4'],
            'images_montre.*'  => ['image', 'max:4096'],
            'existing_images'  => ['nullable', 'array', 'max:4'],
            'existing_images.*'=> ['string'],
        ]);

        // Normalise boolean
        $data['en_vente'] = (bool) ($request->input('en_vente') == '1' || $request->boolean('en_vente'));

        /* Affiche film */
        if ($request->hasFile('affiche_film')) {
            if ($article->affiche_film) {
                Storage::disk('public')->delete($article->affiche_film);
            }
            $data['affiche_film'] = $request
                ->file('affiche_film')
                ->store('articles/affiches', 'public');
        } else {
            // ne pas écraser si pas de nouvelle affiche
            unset($data['affiche_film']);
        }

        /* Images montre */
        $keep = collect($request->input('existing_images', []))
            ->filter(fn ($img) => is_string($img) && $img !== '')
            ->values();

        // Ne garder que les images qui appartiennent déjà à l'article
        $current = collect($article->images_montre ?? []);
        $keep = $keep->intersect($current)->values();

        // Supprime les images retirées
        $toDelete = $current->diff($keep);
        foreach ($toDelete as $img) {
            Storage::disk('public')->delete($img);
        }

        $finalImages = $keep->all();

        // Ajoute les nouvelles images (sans dépasser 4)
        if ($request->hasFile('images_montre')) {
            foreach ($request->file('images_montre') as $file) {
                if (count($finalImages) >= 4) break;
                $finalImages[] = $file->store('articles/montres', 'public');
            }
        }

        $data['images_montre'] = $finalImages;

        $article->update($data);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article mis à jour');
    }



    /* =========================
     * Suppression
     * ========================= */
    public function destroy(Article $article)
    {
        if ($article->affiche_film) {
            Storage::disk('public')->delete($article->affiche_film);
        }

        foreach ($article->images_montre ?? [] as $img) {
            Storage::disk('public')->delete($img);
        }

        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article supprimé');
    }
}
