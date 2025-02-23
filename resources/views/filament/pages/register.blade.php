<x-filament::page>
    <form wire:submit.prevent="register" class="space-y-4">
        {{ $this->form }}

        <x-filament::button type="submit" class="w-full">
            Register
        </x-filament::button>
    </form>
</x-filament::page>
