# Multi-tenancy PWA

A set up opinionated helpers for Laravel to instantly boot a multi-tenant aware back office for Filament 4. It has PWA features for app installation and push notifications and includes Google Socialite login (compliments of Povilas: https://laraveldaily.com/post/filament-sign-in-with-google-using-laravel-socialite)

## Packages included

The following are presumed to be used and already included in composer:

- Filament 4.x
- Laravel Socialite
- Spatie Eloquent Sortable

## Installation

```bash
composer config minimum-stability beta
```

```bash
composer require filamentphp/filament:4.x
```

```bash
composer require eugenefvdm/multi-tenancy-pwa
```

```bash
php artisan vendor:publish --tag=eloquent-sortable-config
```

## Setup

### Spatie orderable column name

In `config/eloquent-sortable.php`, change `order_column_name` from `order_colulmn` to `order`.

If you're using this in your code you'll need both `Sortable` and `SortableTrait`.

### Database migrations for Tenancy

Every tenant aware table in your application should have this line:

```php
$table->foreignId('tenant_id')->constrained('tenants');
```
To keep things tidy I like putting them after the main ID column.

### Tenant Model

You'll need the base tenant model:

```bash
php artisan make:model Tenant -f
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tenant extends Model
{
    /** @use HasFactory<\Database\Factories\TenantFactory> */
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
```

### Model Trait

Add the following trait to your tenant aware models (but not to `Tenant.php`):

```php
use Eugenefvdm\MultiTenancyPWA\Traits\HasTenantRelationship;
```

### Filament Panel Service Provider

#### ApplyTenantScopes Middleware

Let's say you have `todo` and `category` tables and you want them to automatically get `tenant_id`. Use this middleware:

```php
<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Todo;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyTenantScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Category::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );
        
        Todo::addGlobalScope(
            fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()),
        );

        return $next($request);
    }
}
```

The security of your application is your responsbility. Be sure to read this part of the manual:
https://filamentphp.com/docs/4.x/users/tenancy#tenancy-security

### To automatically assign your tenant `id` column to every record creation and list view, add Tenant

Next, continue the Filament installation:

```bash
php artisan filament:install --panels
```

Open `AdminPanelProvider.php` and add this to the end, below the `authMiddleware` section:

```php
use App\Http\Middleware\ApplyTenantScopes;
use App\Models\Tenant;
use Eugenefvdm\MultiTenancyPWA\Filament\Pages\Tenancy\RegisterNewTenant;

// Filament AdminPanelProvider extra sections
->colors([ // Already exists
    'primary' => Color::Cyan
]);
->profile(\App\Filament\Pages\Auth\EditProfile::class);
->tenant(Tenant::class)
->registration()
->tenantRegistration(RegisterNewTenant::class)
->tenantProfile(EditMyTenantProfile::class)
->renderHook( 
    'panels::auth.login.form.after',
    fn () => view('multi-tenancy-pwa::auth.socialite.google')
->tenantMiddleware([
    ApplyTenantScopes::class,
], isPersistent: true)
->databaseNotifications();
```

## Web Push Notifications

Composer will already be updated with `laravel-notification-channels/webpush`.

Add the config file:

```bash
php artisan vendor:publish --provider="NotificationChannels\WebPush\WebPushServiceProvider" --tag="config"
```

```bash
php artisan vendor:publish --provider="NotificationChannels\WebPush\WebPushServiceProvider" --tag="migrations"
```

### Database notifications

Laravel 11 and higher:

```
php artisan make:notifications-table
```

## User Model

```php
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements FilamentUser, HasDefaultTenant, HasTenants
{
    use HasPushSubscriptions;

    // ...
    protected $fillable = [
        'google_id'        
    ];

    // ...
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->tenants;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tenants()->whereKey($tenant)->exists();
    }

    public function getDefaultTenant(): ?Model
    {
        return $this->latestTenant()->first();
    }

    public function latestTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'latest_tenant_id');
    }
}
```

### Now you can migrate the database

```bash
php artisan vendor:publish --tag="multi-tenancy-pwa-migrations"
```

### Add Google oAuth credentials

First see: https://herd.laravel.com/docs/macos/advanced-usage/social-auth

Then go to: https://console.cloud.google.com/apis/credentials?pli=1

At Google's URL above, carefully copy out your `CLIENT_ID` and `CLIENT_SECRET` top right.

```bash
GOOGLE_CLIENT_ID=******
GOOGLE_CLIENT_SECRET=******
# For testing use the line below, for production, leave it out.
# GOOGLE_REDIRECT=https://fwd.host/http://your-herd-site.test/auth/google/callback
```

## For web push

```bash
VAPID_PUBLIC_KEY=
VAPID_PRIVATE_KEY=
```

See: https://laravel-news.com/fwd-host

## Logging in

Sign up for a new account:

https://app.test/admin/register

## General Features

- Work with Filament 4
- Includes `HasTenantRelationship` that may be used to make any model tenant aware
- Social login using Google

## PWA Features

- App installation button
- Push notifications

## More information

### Excluding resources from tenancy

As per the manual, you can exclude Filament resources from Tenancy by doing this:

```php
use Filament\Resources\Resource;

protected static bool $isScopedToTenant = false;
```

## Web push errors

```bash
openssl_pkey_new(): Private key length must be at least 384 bits, configured to 0
```

You can simulate this error on the command line on a Mac

```bash
php -r 'print_r(openssl_pkey_new());'
Warning: openssl_pkey_new(): Private key length must be at least 384 bits, configured to 0 in Command line code on line 1
```

```bash
php -i | grep 'Openssl default config'
Openssl default config => /etc/ssl/openssl.cnf
sudo vi /etc/ssl/openssl.cnf
sudo vi /opt/homebrew/opt/openssl@3/etc/openssl.cnf
[ req ]
default_bits            = 2048
default_md              = sha256
default_keyfile         = privkey.pem
```

But this works:
```
OPENSSL_CONF=/opt/homebrew/opt/openssl@3/etc/openssl.cnf \
php -r 'var_dump(openssl_pkey_new(["private_key_type"=>OPENSSL_KEYTYPE_RSA]));'
```

## Socialite Errors

### Incorrect Client Secret

Client error: `POST https://www.googleapis.com/oauth2/v4/token` resulted in a `400 Bad Request` response: { "error": "invalid_request", "error_description": "Missing required parameter: code" }

The `Client secret` is incorrect. It's the bottom value on Google's site.

## PWA Images

If you have an SVG, you can resize it here:
See: https://pwagenerator.test/

## Application Icon Name on Android

`short_name` in manifest.json



