<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Marque;

class MarqueController extends Controller
{
    public function index()
    {
        $marques = Marque::select('id_marque', 'nom_marque', 'photo_marque', 'logo_marque')
            ->orderBy('nom_marque')
            ->get()
            ->map(function ($marque) {
                if ($marque->photo_marque) {
                    $marque->photo_marque = asset('storage/' . $marque->photo_marque);
                }
                if ($marque->logo_marque) {
                    $marque->logo_marque = asset('storage/' . $marque->logo_marque);
                }
                return $marque;
            });

        return Inertia::render('Marques', [
            'marques' => $marques, 'title' => 'Marques'
        ]);
    }

    public function show(Marque $marque)
    {
        // On récupère les montres liées à la marque
        $montres = $marque->montres()
            ->select('id_montre', 'image_montre', 'info_montre')
            ->get()
            ->map(function ($montre) {
                if ($montre->image_montre) {
                    $montre->image_montre = asset('storage/' . $montre->image_montre);
                }
                return $montre;
            });

        return Inertia::render('Montres', [
            'marque'  => $marque,
            'montres' => $montres,
            'title'   => $marque->nom_marque,
        ]);
    }

}
