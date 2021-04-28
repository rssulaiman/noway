<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::get('/enseignants/register', [\App\Http\Controllers\Auth\RegisterController::class, 'enseignantForm'])->name('teachers.register');

Route::get('not_authorize', function () {
    return view('errors.401');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::redirect('/dashboard', '/home');

    Route::get('/profile/edit', [App\Http\Controllers\HomeController::class, 'showProfileForm'])->name('profile.edit');
    Route::put('/profile/edit', [App\Http\Controllers\HomeController::class, 'updateProfile']);
    Route::get('/profile/{user?}', [App\Http\Controllers\HomeController::class, 'showProfile'])->name('profile.show');

    Route::group(['middleware' => 'is_etudiant', 'prefix' => '/etudiants', 'as' => 'etudiants.'], function () {
        Route::get('/home', [App\Http\Controllers\EtudiantController::class, 'index'])->name('index');
        Route::get('/courses', [App\Http\Controllers\EtudiantController::class, 'show_my_courses'])->name('courses');
        Route::get('/courses/add', [App\Http\Controllers\EtudiantController::class, 'show_all_courses'])->name('courses.add');
        Route::get('/courses/{course}', [App\Http\Controllers\EtudiantController::class, 'show_course'])->name('courses.show');
        Route::put('/courses/{course}', [App\Http\Controllers\EtudiantController::class, 'update_course'])->name('courses.update');
        Route::get('/plannings', [App\Http\Controllers\EtudiantController::class, 'show_plannings'])->name('plannings');
    });

    Route::group(['middleware' => 'is_enseignant', 'prefix' => '/enseignants', 'as' => 'enseignants.'], function () {
        Route::get('/home', [\App\Http\Controllers\EnseignantController::class, 'index'])->name('index');
        Route::get('/courses', [App\Http\Controllers\EnseignantController::class, 'show_courses'])->name('courses');
        Route::get('/courses/{course}', [App\Http\Controllers\EnseignantController::class, 'show_course'])->name('courses.show');
        Route::resource('plannings', App\Http\Controllers\PlanningController::class);
    });


    Route::group(['middleware' => 'is_admin', 'prefix' => '/admin', 'as' => 'admin.'], function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'index'])->name('index');
        Route::get('users/unvalidate', [\App\Http\Controllers\Admin\UserController::class, 'unvalidate'])->name('users.unvalidate');
        Route::delete('users/{user}/forceDelete', [\App\Http\Controllers\Admin\UserController::class, 'forceDelete'])->name('users.forceDelete');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class);
        Route::resource('formations', \App\Http\Controllers\Admin\FormationController::class);
        Route::resource('plannings', \App\Http\Controllers\Admin\PlanningController::class);
    });
});
