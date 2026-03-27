<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_article'; // Clé primaire personnalisée
    protected $keyType = 'int';
    public $incrementing = true;

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

    protected $appends = [
    'affiche_film_url',
    'images_montre_urls',
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

        public function getAfficheFilmUrlAttribute()
    {
        return $this->affiche_film
            ? asset('storage/' . $this->affiche_film)
            : null;
    }

    public function getImagesMontreUrlsAttribute()
    {
        return collect($this->images_montre ?? [])
            ->map(fn ($img) => asset('storage/' . $img))
            ->values();
    }

    public function auteurs() {
        return $this->belongsToMany(User::class, 'ecrire', 'id_article', 'user_id');
    }

    public function commentaires() {
        return $this->hasMany(Commentaire::class, 'id_article');
    }

    public function notes() {
        return $this->belongsToMany(Montre::class, 'noter', 'id_article', 'id_montre')->withPivot('note_montre');
    }

    public function getRouteKeyName()
    {
        return 'id_article';
    }
}
