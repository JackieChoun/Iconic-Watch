<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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
        return Inertia::render('admin/Marques', [
            'marques' => $marques
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/MarqueCreate');
    }

    public function store(Request $request)
{
    
    $validated = $request->validate([
        'nom_marque' => 'required|string|max:255',
        'photo' => 'nullable|mimes:jpg,jpeg,png,svg,webp|max:3048',
        'logo' => 'nullable|mimes:jpg,jpeg,png,svg,webp|max:2048',
    ]);

    // Sauvegarde des fichiers si présents
    if ($request->hasFile('photo')) {
        $validated['photo_marque'] = $request->file('photo')->store('marques/photos', 'public');
    }

    if ($request->hasFile('logo')) {
        $validated['logo_marque'] = $request->file('logo')->store('marques/logos', 'public');
    }

    // Création
    Marque::create($validated);

    return redirect()->route('marques.index')->with('success', 'Marque créée avec succès !');
}




    public function edit(Marque $marque)
    {
        return Inertia::render('admin/MarqueEdit', [
            'marque' => $marque
        ]);
    }

   public function update(Request $request, Marque $marque)
    {
        $validated = $request->validate([
            'nom_marque' => 'required|string|max:50',
            'photo' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($marque->photo_marque) {
                Storage::disk('public')->delete($marque->photo_marque);
            }
            $marque->photo_marque = $request->file('photo')->store('marques', 'public');
        }

        if ($request->hasFile('logo')) {
            if ($marque->logo_marque) {
                Storage::disk('public')->delete($marque->logo_marque);
            }
            $marque->logo_marque = $request->file('logo')->store('marques', 'public');
        }

        $marque->nom_marque = $validated['nom_marque'];
        $marque->save();

        return redirect()->route('marques.index')->with('success', 'Marque mise à jour.');
}


    public function destroy(Marque $marque)
    {
        if ($marque->photo_marque) {
            Storage::disk('public')->delete($marque->photo_marque);
        }

        if ($marque->logo_marque) {
            Storage::disk('public')->delete($marque->logo_marque);
        }

        $marque->delete();

        return redirect()->route('marques.index')->with('success', 'Marque supprimée.');
    }

}
