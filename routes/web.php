<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SuscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersuscriptionController;
use App\Http\Middleware\LocalizationMiddleware;

Route::get('/', WelcomeController::class);

Route::middleware('auth')->group(function () {
    Route::resource('dashboard', DashboardController::class)->only(['index']);
    Route::get('/dashboard/show_site', [DashboardController::class, 'show_site'])->name('dashboard.show_site');
});

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
    Route::get('/users/{id}', [UserController::class, 'show'])->name('show.user');
});

Route::middleware(LocalizationMiddleware::class)->group(function () {
    Route::middleware('auth')->group(function () {
        Route::resource('sites', SiteController::class);
        Route::get('/sites/{site}/manage_config', [SiteController::class, 'maganage_sites_config_pay'])->name('sites.manage_config');
        Route::post('/add_field', [SiteController::class, 'add_field'])->name('sites.add_field');
        Route::delete('/field_destroy/{id}', [SiteController::class, 'field_destroy'])->name('sites.field_destroy');
        Route::get('/sites/{site}/form_site', [SiteController::class, 'form_site'])->name('sites.form_site');
        Route::get('/sites/{site}/form_site_invoices', [SiteController::class, 'form_site_invoices'])->name('sites.form_site_invoices');
        Route::get('/sites/{id}', [SiteController::class, 'show'])->name('show.site');

        Route::get('/finish_session/{value}', [SiteController::class, 'finish_session'])->name('sites.finish_session');
        Route::get('/lose_session/{value}', [SiteController::class, 'lose_session'])->name('sites.lose_session');
        Route::post('/sites/import_invoices/{value}', [SiteController::class, 'import_invoices'])->name('sites.import_invoices');
    });
});

Route::middleware('auth')->group(function () {
    Route::resource('payment', PaymentController::class);
    Route::get('/payment/user/show/{id}', [PaymentController::class, 'pays_especific_user'])->name('payment.pays_user');
    Route::get('/payment/site/show/{id}', [PaymentController::class, 'pays_especific_site'])->name('payment.pays_site');
    Route::get('/payment/suscription/show/{payment}', [PaymentController::class, 'show_suscription_pay'])->name('payment.suscription_show');
});

Route::middleware('auth')->group(function () {
    Route::resource('invoices', InvoiceController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('suscriptions', SuscriptionController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('user_suscriptions', UsersuscriptionController::class);
    Route::delete('/user_suscriptions/{reference}/{user_id}', [UsersuscriptionController::class, 'destroyy'])->name('user_suscriptions.destroyy');
    Route::get('/user_suscriptions/return/{suscription_reference}', [UsersuscriptionController::class, 'return'])->name('user_suscriptions.return');
});

Route::middleware('auth')->group(function () {});

Route::post('lang/', [LanguageController::class, 'setLocale'])->name('lang.switch');

require __DIR__.'/auth.php';
