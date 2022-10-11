<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;

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

Route::group(['middleware' => ['auth'], 'prefix' => 'cpanel'], function() {
	Route::get('home', [HomeController::class, 'index'])->name('home');
	
	Route::get('profile', [UserController::class, 'profile'])->name('profile');

	Route::group(['prefix' => 'admin'], function() {
		// users
		Route::get('users', [UserController::class, 'index'])->name('users');

	});

	Route::group(['prefix' => 'user'], function() {

	});
});

