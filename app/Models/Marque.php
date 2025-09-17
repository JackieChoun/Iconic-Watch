<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Marque extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_marque';
    public $timestamps = false;

    protected $fillable = [
        'nom_marque',
        'photo_marque',
        'logo_marque',
    ];

    public function montres() {
        return $this->hasMany(Montre::class, 'id_marque');
    }
}
