<x-filament-panels::page>
    <form wire:submit="create">
        {{ $this->form }}

        <x-filament::button class="mt-4" type="submit">
            Save
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
