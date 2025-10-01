<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = ['tmdb_id'];

    // 🔗 Un film peut avoir plusieurs articles
    public function articles()
    {
        return $this->hasMany(Article::class, 'id_film');
    }
}
