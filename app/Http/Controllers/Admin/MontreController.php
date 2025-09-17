<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Montre;
use App\Models\Marque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MontreController extends Controller
{
    // public function index()
    // {
    //     $montres = Montre::with('marque')->get();

    //     return Inertia::render('admin/Montres', [
    //         'montres' => $montres,
    //     ]);
    // }
    public function index()
    {
        $marques = Marque::with('montres')->get();

        return Inertia::render('admin/Montres', [
            'marques' => $marques,
        ]);
    }

    public function create()
    {
        $marques = Marque::all();
        return Inertia::render('admin/MontreCreate', [
            'marques' => $marques,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image_montre' => 'nullable|mimes:jpg,jpeg,png,webp,svg|max:4096',
            'info_montre' => 'nullable|string',
            'id_marque' => 'required|exists:marques,id_marque',
        ]);

        $path = null;
        if ($request->hasFile('image_montre')) {
            $path = $request->file('image_montre')->store('montres', 'public');
            $validated['image_montre'] = $path;
        }

        Montre::create([
            'image_montre' => $path,
            'info_montre' => $validated['info_montre'] ?? null,
            'id_marque' => $validated['id_marque'],
        ]);

        return redirect()->route('montres.index')->with('success', 'Montre ajoutée avec succès.');
    }

    public function edit(Montre $montre)
    {
        $marques = Marque::all();
        return Inertia::render('admin/MontreEdit', [
            'montre' => $montre,
            'marques' => $marques,
        ]);
    }

    public function update(Request $request, Montre $montre)
    {
        $validated = $request->validate([
            'image_montre' => 'nullable|mimes:jpg,jpeg,png,webp,svg|max:4096',
            'info_montre' => 'nullable|string',
            'id_marque' => 'required|exists:marques,id_marque',
        ]);

        if ($request->hasFile('image_montre')) {
            if ($montre->image_montre) {
                Storage::disk('public')->delete($montre->image_montre);
            }
            $montre->image_montre = $request->file('image_montre')->store('montres', 'public');
        }

        $montre->info_montre = $validated['info_montre'] ?? null;
        $montre->id_marque = $validated['id_marque'];
        $montre->save();

        return redirect()->route('montres.index')->with('success', 'Montre mise à jour.');
    }

    public function destroy(Montre $montre)
    {
        if ($montre->image_montre) {
            Storage::disk('public')->delete($montre->image_montre);
        }

        $montre->delete();
        return redirect()->route('montres.index')->with('success', 'Montre supprimée.');
    }
}
