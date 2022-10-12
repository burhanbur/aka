<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;;

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

	Route::group(['middleware' => ['role:admin']], function() {
		// institutions
		Route::get('institutions', [InstitutionController::class, 'index'])->name('institutions');
		Route::get('institutions/{id?}', [InstitutionController::class, 'show'])->name('show.institution');
		Route::get('create-institution', [InstitutionController::class, 'create'])->name('create.institution');
		Route::post('institutions', [InstitutionController::class, 'store'])->name('store.institution');
		Route::get('edit-institution/{id?}', [InstitutionController::class, 'edit'])->name('edit.institution');
		Route::put('institutions/{id?}', [InstitutionController::class, 'update'])->name('update.institution');

		// users
		Route::get('users', [UserController::class, 'index'])->name('users');
		Route::get('users/{id?}', [UserController::class, 'show'])->name('show.user');
		Route::get('create-user', [UserController::class, 'create'])->name('create.user');
		Route::post('users', [UserController::class, 'store'])->name('store.user');
		Route::get('edit-user/{id?}', [UserController::class, 'edit'])->name('edit.user');
		Route::put('users/{id?}', [UserController::class, 'update'])->name('update.user');

		// activities
		Route::get('activities', [ActivityController::class, 'index'])->name('activities');
		Route::get('activities/{id?}', [ActivityController::class, 'show'])->name('show.activity');
		Route::get('create-activity', [ActivityController::class, 'create'])->name('create.activity');
		Route::post('activities', [ActivityController::class, 'store'])->name('store.activity');
		Route::get('edit-activity/{id?}', [ActivityController::class, 'edit'])->name('edit.activity');
		Route::put('activities/{id?}', [ActivityController::class, 'update'])->name('update.activity');

		// surveys
		Route::get('surveys', [SurveyController::class, 'index'])->name('surveys');
		Route::get('surveys/{id?}', [SurveyController::class, 'show'])->name('show.survey');
		Route::get('create-survey', [SurveyController::class, 'create'])->name('create.survey');
		Route::post('surveys', [SurveyController::class, 'store'])->name('store.survey');
		Route::get('edit-survey/{id?}', [SurveyController::class, 'edit'])->name('edit.survey');
		Route::put('surveys/{id?}', [SurveyController::class, 'update'])->name('update.survey');
		Route::delete('surveys/{id?}', [SurveyController::class, 'destroy'])->name('delete.survey');

	});

	Route::group(['middleware' => ['role:user']], function() {
		// survey
		Route::get('event-surveys', [SurveyController::class, 'listEventSurvey'])->name('event.surveys');
		Route::get('event-surveys/{id?}', [SurveyController::class, 'fillEventSurvey'])->name('fill.event.survey');
		Route::post('event-surveys', [SurveyController::class, 'storeEventSurvey'])->name('store.event.survey');
	});
});

