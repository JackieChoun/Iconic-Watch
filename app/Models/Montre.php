<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Montre extends Model
{
    use HasFactory;

    protected $table = 'montres';
    protected $primaryKey = 'id_montre';
    public $timestamps = false;

    protected $fillable = [
        'image_montre',
        'info_montre',
        'id_marque',
    ];

    public function marque()
    {
        return $this->belongsTo(Marque::class, 'id_marque');
    }

    // 🔗 Une montre peut être liée à plusieurs articles
    public function articles()
    {
        return $this->hasMany(Article::class, 'id_montre');
    }
}


