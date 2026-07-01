<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\{ IndexController, FilmsController, MarqueController, ArticleController, SearchController, CommentaireController };

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// Route pour les mentions légales
Route::get('/ML', function () {
    return Inertia::render('ML');
})->name('ML');

// Route pour la page d'accueil
Route::get('/', [IndexController::class, 'index'])->name('accueil');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard', [
        'user' => Auth::user(),
    ]);
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

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
Route::get('/marques/{marque}', [MarqueController::class, 'show'])->name('marques.show');

// Articles (front)
Route::get('articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('films/{tmdbId}/{slug?}', [ArticleController::class, 'showByFilmTmdb'])
    ->whereNumber('tmdbId')
    ->name('articles.film');
Route::get('montres/{montre}', [ArticleController::class, 'showByMontre'])->name('articles.montre');

// Recherche (front)
Route::get('recherche', [SearchController::class, 'index'])->name('search');

// Route pour admin 
Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('marques', App\Http\Controllers\Admin\MarqueController::class);
    Route::resource('montres', App\Http\Controllers\Admin\MontreController::class);
    Route::resource('films', App\Http\Controllers\Admin\FilmController::class);
    Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class);
    Route::post('articles/{article}/update', [App\Http\Controllers\Admin\ArticleController::class, 'update'])
        ->name('articles.update.post');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->only(['index', 'edit', 'update']);
    // commentaires d’un user
        Route::get('users/{user}/comments', [App\Http\Controllers\Admin\CommentaireController::class, 'index'])
            ->name('users.comments');

        // suppression
        Route::delete('comments/{commentaire}', [App\Http\Controllers\Admin\CommentaireController::class, 'destroy'])
            ->name('comments.destroy');
});

Route::get('/articles/{article}/comments', [CommentaireController::class, 'index']);
Route::post('/articles/{article}/comments', [CommentaireController::class, 'store'])->middleware('auth');
Route::delete('/comments/{commentaire}', [CommentaireController::class, 'destroy'])
    ->middleware('auth');

// Route profile user
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return Inertia::render('Profile');
    })->name('profile');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
