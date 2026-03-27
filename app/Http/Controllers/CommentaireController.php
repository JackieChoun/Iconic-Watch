<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Article;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    public function index(Article $article)
    {
        $commentaires = $article->commentaires()
            ->with('utilisateur:id,name')
            ->latest()
            ->get();

        return $commentaires->map(function ($c) {
            return [
                'id' => $c->id_commentaires,
                'content' => $c->contenu_commentaire,
                'user_name' => $c->utilisateur?->name ?? 'Anonyme',
                'created_at' => $c->created_at,
                'user_id' => $c->user_id,
            ];
        });
    }

    public function store(Request $request, Article $article)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $commentaire = Commentaire::create([
            'contenu_commentaire' => $request->input('content'),
            'id_article' => $article->id_article,
            'user_id' => auth()->id(),
        ]);

        // charge la relation utilisateur
        $commentaire->load('utilisateur');

        return response()->json([
            'id' => $commentaire->id_commentaires,
            'content' => $commentaire->contenu_commentaire,
            'user_name' => $commentaire->utilisateur?->name ?? 'Anonyme',
            'created_at' => $commentaire->created_at,
            'user_id' => $commentaire->user_id,
        ]);
    }

    public function destroy(Commentaire $commentaire)
    {
        // Vérifie que c’est bien le propriétaire
        if ($commentaire->user_id !== auth()->id()) {
            abort(403);
        }

        $commentaire->delete();

        return response()->json([
            'message' => 'Commentaire supprimé'
        ]);
    }
}