<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Film;
use App\Models\Marque;
use App\Models\Montre;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $qLike = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $q) . '%';

        $results = [
            'q' => $q,
            'marques' => [],
            'montres' => [],
            'films' => [],
        ];

        if ($q !== '' && mb_strlen($q) >= 2) {
            $results['marques'] = Marque::query()
                ->select('id_marque', 'nom_marque', 'logo_marque')
                ->where('nom_marque', 'like', $qLike)
                ->orderBy('nom_marque')
                ->limit(10)
                ->get()
                ->map(function ($m) {
                    $m->logo_marque = $m->logo_marque ? asset('storage/' . $m->logo_marque) : null;
                    return $m;
                })
                ->values();

            $results['montres'] = Montre::query()
                ->with('marque:id_marque,nom_marque')
                ->select('id_montre', 'info_montre', 'id_marque')
                ->where('info_montre', 'like', $qLike)
                ->orderBy('info_montre')
                ->limit(10)
                ->get();

            // Films: on cherche d'abord dans notre table (titres stockés via seed/ajout admin)
            $results['films'] = Film::query()
                ->select('id', 'tmdb_id', 'title')
                ->where('title', 'like', $qLike)
                ->orderBy('title')
                ->limit(10)
                ->get();
        }

        return Inertia::render('Search', [
            'title' => 'Recherche',
            'results' => $results,
        ]);
    }
}
