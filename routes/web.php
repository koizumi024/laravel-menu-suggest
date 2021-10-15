<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/material', [HomeController::class, 'index'])->name('material');
Route::post('/store', [HomeController::class, 'store'])->name('store');
Route::get('/user', [HomeController::class, 'user'])->name('user');
Route::get('/suggest', [HomeController::class, 'suggest'])->name('suggest');

