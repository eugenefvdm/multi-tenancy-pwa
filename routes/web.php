<?php

use Eugenefvdm\MultiTenancyPWA\Http\Controllers\SocialiteController;
use Eugenefvdm\MultiTenancyPWA\Http\Controllers\WebPushController;
use Eugenefvdm\MultiTenancyPWA\Http\Controllers\WebPushSubscriptionController;
use Illuminate\Support\Facades\Route;

// Goolge(+) Social Login
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');    
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback')
    ->middleware('web');

// Dynamic PWA Manifest - copy/paste to your own Web route
Route::get('/manifest.json', function () {
    $manifest = [
        'name' => 'Your App Name',
        'short_name' => 'Your App Short Name',
        'description' => 'A description of your app',
        'start_url' => config('app.url') . '/admin', // Fixed to Filament start page
        'display' => 'standalone',
        'theme_color' => '#000000',
        'background_color' => '#ffffff',
        'orientation' => 'any',
        'status_bar' => 'black',
        'splash' => [
            '1125x2436' => '/img/pwa/splash/splash-1125x2436.png'
        ],
        'screenshots' => [
            [
                'src' => '/img/pwa/screenshots/screenshot-720x720.png',
                'sizes' => '720x720',
                'type' => 'image/jpeg',
                'label' => 'Main Dashboard',
                'form_factor' => 'narrow'
            ],
            [
                'src' => '/img/pwa/screenshots/screenshot-1280x720.jpeg',
                'sizes' => '1280x720',
                'type' => 'image/jpeg',
                'label' => 'Student Dashboard',
                'form_factor' => 'wide'
            ]
        ],
        'icons' => [
            [
                'src' => '/img/pwa/icons/icon-192x192.jpeg',
                'type' => 'image/png',
                'sizes' => '192x192',
                'purpose' => 'any'
            ],
            [
                'src' => '/img/pwa/icons/icon-512x512.jpeg',
                'type' => 'image/png',
                'sizes' => '512x512',
                'purpose' => 'any'
            ]
        ]
    ];

    return response()->json($manifest)
        ->header('Content-Type', 'application/manifest+json');
})->name('pwa.manifest');

// PWA routes - requires web middleware
Route::get('/app', function () {
    return view('multi-tenancy-pwa::pwa.diagnostics', [
        'vapidPublicKey' => config('webpush.vapid.public_key')
    ]);
})->middleware('web')->name('pwa.diagnostics');

// PWA Offline page
Route::get('/offline', function () {
    return view('multi-tenancy-pwa::pwa.offline');
})->name('pwa.offline');

// Web Push Routes - requires web middleware
Route::middleware('web')->group(function () {
    Route::match(['post', 'put', 'delete'], '/push-subscription', [WebPushSubscriptionController::class, 'handle']);
    Route::post('/send-notification', [WebPushController::class, 'sendNotification']);
});