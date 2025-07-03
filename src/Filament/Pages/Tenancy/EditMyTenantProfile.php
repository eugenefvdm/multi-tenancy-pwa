<?php

namespace Eugenefvdm\MultiTenancyPWA\Filament\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditMyTenantProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Project profile';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name'),
            ]);
    }
}
