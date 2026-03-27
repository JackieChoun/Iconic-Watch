<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commentaire extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_commentaires';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
    'contenu_commentaire',
    'id_article',
    'user_id'
    ];

    public function utilisateur(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function article() {
        return $this->belongsTo(Article::class, 'id_article', 'id_article');
    }
}
