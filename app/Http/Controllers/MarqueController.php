<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Marque;

class MarqueController extends Controller
{
    public function index()
    {
        //return Inertia::render('Marques', ['title' => 'Marques']);
        return Inertia::render('Marques', [
        'marques' => Marque::all(), 'title' => 'Marques'
    ]);
    }
}
