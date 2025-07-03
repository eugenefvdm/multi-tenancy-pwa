<?php

use Eugenefvdm\MultiTenancyPWA\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.callback');

// Dynamic PWA Manifest
Route::get('/manifest.json', function () {
    $manifest = [
        'name' => 'Todo Magic',
        'short_name' => 'Todo Magic',
        'description' => 'An app to magically manage your todos.',
        'start_url' => config('app.url') . '/admin',
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
                'src' => '/img/pwa/screenshots/magic-wand-1280x720.jpeg',
                'sizes' => '1280x720',
                'type' => 'image/jpeg',
                'label' => 'Student Dashboard',
                'form_factor' => 'wide'
            ]
        ],
        'icons' => [
            [
                'src' => '/img/pwa/icons/magic-wand-192x192.jpeg',
                'type' => 'image/png',
                'sizes' => '192x192',
                'purpose' => 'any'
            ],
            [
                'src' => '/img/pwa/icons/magic-wand-512x512.jpeg',
                'type' => 'image/png',
                'sizes' => '512x512',
                'purpose' => 'any'
            ]
        ]
    ];

    return response()->json($manifest)
        ->header('Content-Type', 'application/manifest+json');
})->name('pwa.manifest');

// PWA routes
Route::get('/app', function () {

    return view('multi-tenancy-pwa::pwa.diagnostics', [
        'vapidPublicKey' => config('webpush.vapid.public_key')
    ]);
})->middleware('web')->name('pwa.diagnostics');

// PWA Offline page
Route::get('/offline', function () {
    return view('multi-tenancy-pwa::pwa.offline');
})->name('pwa.offline');
