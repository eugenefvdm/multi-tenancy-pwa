<?php

namespace Eugenefvdm\MultiTenancyPWA\Filament\Pages\Auth;

use App\Notifications\WebpushNotification;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class EditProfile extends BaseEditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

                Tabs::make('Label')->tabs([
                    'Profile' => $this->profileTab(),
                ]),

                Tabs::make('Label')->tabs([
                    'Settings' => $this->settingsTab(),
                ]),


            ]);
    }

    private function profileTab()
    {
        return Tabs\Tab::make('Profile')
            ->schema(
                [
                    $this->getNameFormComponent(),

                    $this->getEmailFormComponent(),

                    $this->getPasswordFormComponent(),

                    $this->getPasswordConfirmationFormComponent(),
                ]
            );
    }

    private function settingsTab()
    {
        return Tabs\Tab::make('Application diagnostics')
            ->schema(
                [
                    ViewField::make('installApp')
                        ->view('multi-tenancy-pwa::pwa.install-button'),

                    Actions::make([
                        Action::make('PWA Raw Diagnostics')
                            ->label('PWA Raw Diagnostics')
                            ->url('/pwa/diagnostics'),
                    ]),

                    Fieldset::make('Notifications')->schema([
                        Actions::make([
                            Action::make('WebSockets')
                                ->label('WebSockets')
                                ->action(function () {
                                    $recipient = auth()->user();

                                    \Filament\Notifications\Notification::make()
                                        ->title('If you see this message, WebSocket broadcasting to this user is working.')
                                        ->success()
                                        ->broadcast($recipient);
                                })->color('gray'),

                            Action::make('database')
                                ->action(function () {
                                    $recipient = auth()->user();

                                    \Filament\Notifications\Notification::make()
                                        ->title('Test database notification')
                                        ->body('Database test notification succeeded.')
                                        ->success()
                                        ->sendToDatabase($recipient)
                                        ->send();
                                })->color('gray'),

                            Action::make('webpush')
                                ->action(function () {
                                    $recipient = auth()->user();

                                    $recipient->notify(new WebpushNotification());
                                })->color('gray'),
                        ]),
                    ]),            
                ]
            );
    }
}
