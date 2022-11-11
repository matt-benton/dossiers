<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\SearchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    Route::post('/interests/{interest}/add_person', [InterestController::class, 'addPerson'])
      ->name('interests.add.person')
      ->middleware('can:update,interest');
    Route::post('/interests/{interest}/remove_person', [InterestController::class, 'removePerson'])
      ->name('interests.remove.person')
      ->middleware('can:update,interest');
    Route::resource('/interests', InterestController::class);
    Route::resource('/developments', DevelopmentController::class)->only(['store', 'destroy']);
    Route::post('/groups/{group}/add_member', [GroupController::class, 'addMember'])
      ->name('groups.add.member')
      ->middleware('can:update,group');
    Route::post('/groups/{group}/remove_member', [GroupController::class, 'removeMember'])
      ->name('groups.remove.member')
      ->middleware('can:update,group');
    Route::resource('/groups', GroupController::class);

    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/people', [SearchController::class, 'people']);
        Route::get('/interests', [SearchController::class, 'interests']);
        Route::get('/groups', [SearchController::class, 'groups']);
        Route::get('/uninterested/{interest}', [SearchController::class, 'uninterested']);
        Route::get('/ungrouped/{group}', [SearchController::class, 'ungrouped']);
    });
});

Route::get('/design_system', function () {
    return Inertia::render('DesignSystem');
});

require __DIR__.'/auth.php';
