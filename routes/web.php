<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScrapingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/my-materials', [HomeController::class, 'loadMyMaterials'])->name('my-materials');
Route::get('/my-materials/update-materials', [HomeController::class, 'loadUpdateMaterials'])->name('update-materials');
Route::get('/my-materials/dislike-materials', [HomeController::class, 'loadDislikeMaterials'])->name('dislike-materials');
Route::post('/my-materials/delete-material', [HomeController::class, 'deleteMaterial2'])->name('delete-material2');
Route::post('/my-materials/clear', [HomeController::class, 'clear'])->name('clear');

Route::post('/store', [HomeController::class, 'store'])->name('store');
Route::get('/favorite', [HomeController::class, 'loadFavorite'])->name('favorite');
Route::get('/wishlist', [HomeController::class, 'loadWishlist'])->name('wishlist');
Route::post('/wishlist/clear', [HomeController::class, 'clearWishlist'])->name('clearWishlist');

Route::post('/add-wishlist', [HomeController::class, 'addWishlist'])->name('add-wishlist');
Route::post('/delete-wishlist', [HomeController::class, 'deleteWishlist'])->name('delete-wishlist');
Route::post('/delete-wishlist2', [HomeController::class, 'deleteWishlist2'])->name('delete-wishlist2');
Route::get('/suggest-menu', [HomeController::class, 'loadSuggest'])->name('suggest');

Route::post('/dstore', [HomeController::class, 'dstore'])->name('dstore');
Route::get('/search', [ScrapingController::class, 'scrapingRecipe']);

Route::post('/menu-suggest', [HomeController::class, 'menuSuggest'])->name('menuSuggest');
Route::get('/menu/{id}', [HomeController::class, 'loadMenuDetail'])->name('menu.index');
Route::post('/menu/fav-recipe', [HomeController::class, 'favRecipe'])->name('favRecipe');
Route::post('/menu/fav-recipe2', [HomeController::class, 'favRecipe2'])->name('favRecipe2');
Route::post('/delete-material', [HomeController::class, 'deleteMaterial'])->name('delete-material');
