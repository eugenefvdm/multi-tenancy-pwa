<?php

namespace Eugenefvdm\MultiTenancyPWA\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Routing\Controller;

class SocialiteController extends Controller
{
    use ValidatesRequests;

    public function redirect(string $provider)
    {        
        $this->validateProvider($provider);

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback(string $provider)
    {        
        $this->validateProvider($provider);
        
        $response = Socialite::driver($provider)->stateless()->user();

        $user = User::firstWhere(['email' => $response->getEmail()]);

        if ($user) {
            $user->update([$provider.'_id' => $response->getId()]);
        } else {
            $user = User::create([
                $provider.'_id' => $response->getId(),
                'name' => $response->getName(),
                'email' => $response->getEmail(),
                'password' => '',
            ]);
        }

        auth()->login($user);

        // If the authenticated user is a tenant, redirect to the first tenant's dashboard
        if ($user->tenants()->count() > 0) {            
            $tenant = $user->tenants()->first();

            return redirect()->intended(route('filament.admin.tenant', $tenant));
        }

        return redirect()->intended(route('filament.admin.tenant.registration'));
    }

    protected function validateProvider(string $provider): array
    {
        return $this->getValidationFactory()->make(
            ['provider' => $provider],
            ['provider' => 'in:google']
        )->validate();
    }
}
