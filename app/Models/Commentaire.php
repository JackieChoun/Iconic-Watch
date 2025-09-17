<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_commentaires';

    public function utilisateur() {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function article() {
        return $this->belongsTo(ArticleFilm::class, 'id_article');
    }
}
