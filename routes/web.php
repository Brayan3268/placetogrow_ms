<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});

Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('sites', SiteController::class);
    Route::get('/sites/{site}/manage_config', [SiteController::class, 'maganage_sites_config_pay'])->name('sites.manage_config');
    Route::post('/add_field', [SiteController::class, 'add_field'])->name('sites.add_field');
    Route::delete('/field_destroy/{id}', [SiteController::class, 'field_destroy'])->name('sites.field_destroy');
});

Route::middleware('auth')->group(function () {

});

require __DIR__.'/auth.php';
