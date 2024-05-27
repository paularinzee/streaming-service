<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

Route::group(['prefix' => 'shows'], function() {
    Route::get('/details/{id}', [App\Http\Controllers\Anime\AnimeController::class, 'details'])->name('details');

    Route::post('/insert-comment/{id}', [App\Http\Controllers\Anime\AnimeController::class, 'insertComment'])->name('insert.comment');
    Route::post('/follow/{id}', [App\Http\Controllers\Anime\AnimeController::class, 'follow'])->name('follow');

    Route::get('/watching/{show_id}/{episode_id}', [App\Http\Controllers\Anime\AnimeController::class, 'watching'])->name('watching');

    Route::get('/category/{category_name}', [App\Http\Controllers\Anime\AnimeController::class, 'category'])->name('category');

    Route::any('/search}', [App\Http\Controllers\Anime\AnimeController::class, 'searchShows'])->name('searchShows');

});


Route::get('users/followed-shows', [App\Http\Controllers\Users\UserController::class, 'followedShows'])->name('followedShows')->middleware('auth:web');

Route::get('admin/login', [App\Http\Controllers\Admins\AdminController::class, 'viewLogin'])->name('view.login')->middleware('check.for.auth');
Route::post('admin/login', [App\Http\Controllers\Admins\AdminController::class, 'checkLogin'])->name('check.login');

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {

    Route::get('/index', [App\Http\Controllers\Admins\AdminController::class, 'index'])->name('admin.dashboard');
    

    Route::get('/all-admins', [App\Http\Controllers\Admins\AdminController::class, 'allAdmins'])->name('admin.all');
    Route::get('/create-admins', [App\Http\Controllers\Admins\AdminController::class, 'createAdmins'])->name('admin.create');
    Route::post('/store-admins', [App\Http\Controllers\Admins\AdminController::class, 'storeAdmins'])->name('admin.store');

    Route::get('/all-shows', [App\Http\Controllers\Admins\AdminController::class, 'allshows'])->name('shows.all');
    Route::get('/create-shows', [App\Http\Controllers\Admins\AdminController::class, 'createShows'])->name('show.create');
    Route::post('/store-shows', [App\Http\Controllers\Admins\AdminController::class, 'storeShows'])->name('show.store');
    Route::get('/delete_shows/{id}', [App\Http\Controllers\Admins\AdminController::class, 'deleteShows'])->name('show.delete');
    
    Route::get('/all-genres', [App\Http\Controllers\Admins\AdminController::class, 'allGenres'])->name('genres.all');
    Route::get('/delete_genres/{id}', [App\Http\Controllers\Admins\AdminController::class, 'deleteGenres'])->name('genre.delete');
    Route::get('/create-genres', [App\Http\Controllers\Admins\AdminController::class, 'createGenres'])->name('genre.create');
    Route::post('/store-genres', [App\Http\Controllers\Admins\AdminController::class, 'storeGenres'])->name('genre.store');

    Route::get('/all-episodes', [App\Http\Controllers\Admins\AdminController::class, 'allEpisodes'])->name('episodes.all');
    Route::get('/delete_episodes/{id}', [App\Http\Controllers\Admins\AdminController::class, 'deleteEpisodes'])->name('episode.delete');
    Route::get('/create-episodes', [App\Http\Controllers\Admins\AdminController::class, 'createEpisodes'])->name('episode.create');
    Route::post('/store-episodes', [App\Http\Controllers\Admins\AdminController::class, 'storeEpisodes'])->name('episode.store');


});