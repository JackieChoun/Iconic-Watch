<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_article'; // Clé primaire personnalisée

    protected $fillable = [
        'affiche_film',
        'images_montre',
        'mouvement_montre',
        'en_vente',
        'lien_vente',
        'taille_montre',
        'description',
        'id_film',
        'id_montre',
    ];

    protected $casts = [
        'images_montre' => 'array', // JSON <-> array automatique
        'en_vente' => 'boolean',
    ];

    /* Relations */

    // Un article est lié à un film
    public function film()
    {
        return $this->belongsTo(Film::class, 'id_film');
    }

    // Un article est lié à une montre
    public function montre()
    {
        return $this->belongsTo(Montre::class, 'id_montre');
    }
}
