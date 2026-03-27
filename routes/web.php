<?php

use App\Http\Controllers\recommendationscontroller;
use App\Http\Controllers\TestController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\UserSettingsController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('recommendations', [recommendationscontroller::class, 'index'])->name('recommendations_index');
Route::get('recommendations/filterall', [recommendationscontroller::class, 'filterall'])->name('recommendations_filterall');
Route::get('recommendations/filterme', [recommendationscontroller::class, 'filterme'])->middleware(['auth', 'verified'])->name('recommendations_filterme');

Route::get('myrecommendations', [recommendationscontroller::class, 'myrecommendations'])->middleware(['auth', 'verified'])->name('my_recommendations');
Route::get('recommendations/create', [recommendationscontroller::class, 'create'])->middleware(['auth', 'verified'])->name('recommendations_create');
Route::post('recommendations', [recommendationscontroller::class, 'store'])->middleware(['auth', 'verified'])->name('recommendations_store');


Route::resource('test', TestController::class)->middleware(['auth', 'verified']);


Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
