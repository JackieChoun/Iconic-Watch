<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Montre extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_montre';

    public function marque() {
        return $this->belongsTo(Marque::class, 'id_marque');
    }

    public function notes() {
        return $this->belongsToMany(ArticleFilm::class, 'noter', 'id_montre', 'id_article')->withPivot('note_montre');
    }
}
