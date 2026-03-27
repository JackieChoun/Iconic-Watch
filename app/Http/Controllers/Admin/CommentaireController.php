<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Commentaire;
use Inertia\Inertia;

class CommentaireController extends Controller
{
    public function index(User $user)
    {
        $commentaires = Commentaire::with('article')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return Inertia::render('admin/UserComments', [
            'user' => $user,
            'commentaires' => $commentaires->map(function ($c) {
                return [
                    'id' => $c->id_commentaires,
                    'content' => $c->contenu_commentaire,
                    'created_at' => $c->created_at,
                    'article_id' => $c->id_article,
                ];
            }),
        ]);
    }

    public function destroy(Commentaire $commentaire)
    {
        $commentaire->delete();

        return back()->with('success', 'Commentaire supprimé');
    }
}