<?php

use Eugenefvdm\MultiTenancyPWA\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');