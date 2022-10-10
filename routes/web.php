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
    return view('landing-page');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
	Route::get('home', [HomeController::class, 'index'])->name('home');

	Route::group(['prefix' => 'admin'], function() {

	});

	Route::group(['prefix' => 'user'], function() {

	});
});
