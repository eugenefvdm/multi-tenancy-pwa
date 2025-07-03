<?php

namespace Eugenefvdm\MultiTenancyPWA\Filament\Pages\Tenancy;

use App\Models\Tenant;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterNewTenant extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Add a new project';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->helperText('How would you like to name this project?')
                    ->columnSpanFull(),
            ]);
    }

    protected function handleRegistration(array $data): Tenant
    {
        $tenant = Tenant::create($data);

        $tenant->users()->attach(auth()->user());        

        return $tenant;
    }
}
