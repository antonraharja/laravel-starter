<x-filament-panels::page>
    <form wire:submit="create">
        {{ $this->form }}
    </form>

    <div>
        {{ $this->table }}
    </div>
</x-filament-panels::page>
