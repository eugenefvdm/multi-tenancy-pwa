# Multi-tenancy PWA

A set up opinionated helpers for Laravel to instantly boot a multi-tenant aware back office for Filament 4. It has PWA features for app installation and push notifications and includes Google Socialite login (compliments of Povilas: https://laraveldaily.com/post/filament-sign-in-with-google-using-laravel-socialite)

## Installation

```bash
composer config minimum-stability beta
```

```bash
composer require eugenefvdm/multi-tenancy-pwa
```

## Setup

### Database migrations

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

Add the following trait to your tenant aware models (but not `Tenant.php`):

```php
use Eugenefvdm\MultiTenancyPWA\Traits\HasTenantRelationship;
```

### Filament Panel Service Provider

When you run `composer require`, it will install Filament.

Next, continue the Filament installation:

```bash
php artisan filament:install --panels
```

Open `AdminPanelProvider.php` and add this to the end, below the `authMiddleware` section:

```php
use App\Models\Tenant;
use 

// 
->tenant(Tenant::class)
->registration()
->tenantRegistration(RegisterNewTenant::class)
->renderHook( 
    'panels::auth.login.form.after',
    fn () => view('multi-tenancy-pwa::auth.socialite.google')
);
```

Next, update your user model:

```php
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements FilamentUser, HasTenants
{
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
}
```

### Now you can migrate the database

```bash
php artisan vendor:publish --tag="multi-tenancy-pwa-migrations"
```

### Add Google oAuth credentials

First see: https://herd.laravel.com/docs/macos/advanced-usage/social-auth

Then go to : https://console.cloud.google.com/apis/credentials?pli=1

```bash
GOOGLE_CLIENT_ID=******
GOOGLE_CLIENT_SECRET=******
# For testing use the line below, for production, leave it out.
# GOOGLE_REDIRECT=https://fwd.host/http://your-herd-site.test/auth/google/callback
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


