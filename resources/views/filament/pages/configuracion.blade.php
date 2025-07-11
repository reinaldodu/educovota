<x-filament::page>
    {{-- formulario --}}
    <form wire:submit.prevent="save" class="space-y-6 max-w-xl pl-6">
        {{ $this->form }}

        {{-- Botón envío formulario --}}
        <div class="pt-4">
            <x-filament::button 
                type="submit" 
                wire:loading.attr="disabled" 
                wire:target="save"
            >
                Guardar configuración
            </x-filament::button>
        </div>
    </form>
</x-filament::page>
