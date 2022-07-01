<?php

use App\Http\Controllers\ApiPersonController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\OccurrenceController;

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

Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/', DashboardController::class)->name('dashboard');
  Route::resource('/people', PersonController::class);
  Route::resource('/occurrences', OccurrenceController::class)->only(['store', 'destroy']);

  Route::prefix('api')->name('api.')->group(function () {
    Route::resource('/people', ApiPersonController::class);
  });
});

Route::get('/design_system', function () {
  return Inertia::render('DesignSystem');
});


require __DIR__.'/auth.php';
