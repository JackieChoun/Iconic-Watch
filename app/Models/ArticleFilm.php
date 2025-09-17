<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleFilm extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_article';

    public function auteurs() {
        return $this->belongsToMany(User::class, 'ecrire', 'id_article', 'id_utilisateur');
    }

    public function commentaires() {
        return $this->hasMany(Commentaire::class, 'id_article');
    }

    public function notes() {
        return $this->belongsToMany(Montre::class, 'noter', 'id_article', 'id_montre')->withPivot('note_montre');
    }
}
