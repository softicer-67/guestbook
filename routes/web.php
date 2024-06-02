<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

//Route::get('/', function () {
//    return view('index');
//});

Route::get('/', [HomeController::class, 'index']);
Route::post('/add-message', [HomeController::class, 'store'])->name('add-message');
Route::get('/captcha-image', [HomeController::class, 'generate'])->name('captcha-image');
Route::get('/sort', [HomeController::class, 'sort'])->name('sort');


