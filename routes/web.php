<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{ IndexController, FilmsController, MarqueController };

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard', [
        'user' => Auth::user(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [IndexController::class, 'index'])->name('accueil');

// Route categorie films et dernier ajout dans l'accueil
Route::controller(FilmsController::class)
    ->prefix('films')
    ->group(function () {
        Route::get('/', 'index')->name('films');
        Route::get('movies', 'getMovies')->name('films.movies');
        Route::get('movie/{id}', 'getMovie')->whereNumber('id')->name('films.show');
        Route::get('last', 'getLastMovie')->name('films.last');
    });

// Route pour les marques
Route::get('marques', [MarqueController::class, 'index'])->name('marques');

// Route pour admin
Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::resource('films', App\Http\Controllers\Admin\FilmController::class);
    });
Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::resource('marques', App\Http\Controllers\Admin\MarqueController::class);
    });
Route::middleware(['auth'])->prefix('admin')->group(function () {
        Route::resource('montres', App\Http\Controllers\Admin\MontreController::class);
    }); 


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
