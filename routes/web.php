<?php

use Eugenefvdm\MultiTenancyPWA\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

// PWA routes
Route::get('/app', function () {

    return view('multi-tenancy-pwa::pwa.diagnostics', [
        'vapidPublicKey' => config('webpush.vapid.public_key')
    ]);
})->name('pwa.diagnostics');

// PWA Offline page
Route::get('/offline', function () {
    return view('multi-tenancy-pwa::pwa.offline');
})->name('pwa.offline');