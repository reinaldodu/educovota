<x-filament-panels::page>
    @vite('resources/css/app.css')

    <form wire:submit.prevent="votar">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($candidatos as $candidato)
                @php
                    $id = $candidato['id'] ?? 'blanco';
                @endphp

                <label class="relative block cursor-pointer group">
                    {{-- Input radio como opción --}}
                    <input 
                        type="radio" 
                        name="candidato_id" 
                        value="{{ $id }}" 
                        wire:model.defer="candidato_id"
                        class="sr-only peer"
                    >

                    {{-- Check al hacer hover --}}
                    <div class="absolute top-2 right-2 z-20 opacity-0 group-hover:opacity-100 peer-checked:opacity-100 transition-opacity">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    {{-- Fondo translúcido y check al estar seleccionado --}}
                    <div class="absolute inset-0 bg-green-900/30 z-10 flex items-center justify-center rounded-xl opacity-0 peer-checked:opacity-100 transition">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" />
                        </svg>
                    </div>

                    {{-- Tarjeta visual del candidato --}}
                    <div class="w-full bg-white rounded-xl p-4 text-center flex flex-col items-center justify-between shadow transition group-hover:shadow-lg min-h-[260px] border-2 peer-checked:border-green-600">
                        @if ($candidato['foto'])
                            <img src="{{ asset('storage/' . $candidato['foto']) }}" alt="Foto"
                                 class="w-32 h-32 rounded-full object-cover mb-4 shadow">
                        @else
                            {{-- Voto en blanco --}}
                            <div class="w-32 h-32 rounded-full bg-gray-100 flex items-center justify-center mb-4 text-gray-700 text-center px-2 border border-dashed border-gray-400">
                                <span class="text-sm leading-tight">Voto en blanco</span>
                            </div>
                        @endif

                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $candidato['nombres'] ?? 'Voto' }} {{ $candidato['apellidos'] ?? 'en blanco' }}
                        </h2>
                        <p class="text-sm text-gray-600">
                            {{ $candidato['categoria']['nombre'] ?? '' }}
                        </p>
                    </div>
                </label>
            @endforeach
        </div>

        {{-- Botón de votar --}}
        <div class="mt-6 flex justify-center">
            <button 
                type="submit"
                class="bg-green-600 text-white font-semibold px-6 py-3 rounded hover:bg-green-700 transition disabled:opacity-50"
                wire:loading.attr="disabled"
            >
                Votar
            </button>
        </div>
    </form>
</x-filament-panels::page>