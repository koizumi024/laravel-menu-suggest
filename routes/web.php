<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScrapingController;
use Goutte\Client;

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

Route::get('/material', [HomeController::class, 'loadMaterial'])->name('material');
Route::post('/store', [HomeController::class, 'store'])->name('store');
Route::get('/setting', [HomeController::class, 'loadSetting'])->name('setting');
Route::get('/suggest', [HomeController::class, 'loadSuggest'])->name('suggest');
Route::get('/dislike', [HomeController::class, 'dislike'])->name('dislike');
Route::post('/dstore', [HomeController::class, 'dstore'])->name('dstore');
Route::get('/search', [ScrapingController::class, 'index']);
Route::post('/clear', [HomeController::class, 'clear'])->name('clear');
Route::post('/menu_suggest', [HomeController::class, 'menuSuggest'])->name('menuSuggest');
