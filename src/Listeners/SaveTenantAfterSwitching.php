<?php

namespace App\Listeners;

use Filament\Events\TenantSet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveTenantAfterSwitching
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenantSet $event): void
    {
        $user = $event->getUser();

        $user->latest_tenant_id = $event->getTenant()->id;

        $user->save();        
    }
}
