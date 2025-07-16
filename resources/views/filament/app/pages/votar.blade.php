<div class="p-6 bg-white rounded-lg shadow-md my-6 relative">
    @vite('resources/css/app.css')

    {{-- Mensaje de bienvenida --}}
    @if (!empty($user))
        <div class="absolute top-4 right-4 text-xs text-gray-700 bg-green-100/60 rounded-md px-2 py-1 shadow-sm">
            Gracias por participar, 
            <span class="font-semibold text-gray-800">{{ $user->nombres }} {{ $user->apellidos }}</span> 
            <span class="text-gray-500">({{ $user->documento }})</span>
        </div>
    @endif

    {{-- Encabezado institucional --}}
    <div class="flex flex-col sm:flex-row items-center justify-center mb-10 gap-6">
        <div class="shrink-0">
            @php
                $rutaLogo = $config->logo ?? null;
                $logoExiste = $rutaLogo && file_exists(storage_path('app/public/' . $rutaLogo));
            @endphp

            @if (!empty($config) && $logoExiste)
                <img src="{{ asset('storage/' . $rutaLogo) }}" alt="Logo Institucional"
                    class="h-24 w-24 rounded-full object-cover shadow-md">
            @else
                @include('filament.logo-default')
            @endif
        </div>

        <div class="text-center sm:text-left flex flex-col items-center sm:items-start justify-center">
            <h1 class="text-xl font-bold text-gray-800">
                {{ $config->nombre_institucion ?? 'Nombre de la Institución' }}
            </h1>

            @if (!empty($config?->descripcion_votaciones))
                <p class="text-lg text-green-600 font-semibold leading-tight mt-1 text-center sm:text-left">
                    {{ $config->descripcion_votaciones }}
                </p>
            @endif
        </div>
    </div>

    {{-- Formulario --}}
    <form 
        wire:submit.prevent="votar"
        x-data="{
            enviando: false,
            selecciones: @entangle('selecciones'),
            totalCategorias: {{ count($candidatos) }},
            get completas() {
                return Object.values(this.selecciones).filter(val => val !== null && val !== '').length === this.totalCategorias;
            }
        }"
        @submit="enviando = true"
    >
        {{-- Categorías con descripción --}}
        @foreach ($candidatos as $bloque)
            <div class="mb-8 p-4 rounded-xl border border-gray-200 shadow-sm bg-gray-50">
                <h2 class="text-xl font-bold text-green-700 mb-4 text-center">
                    {{ $bloque['descripcion'] }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-center">
                    @foreach ($bloque['candidatos'] as $candidato)
                        @php 
                            $id = $candidato['id'] ?? 'blanco_' . $candidato['categoria_id']; 
                        @endphp

                        <label class="relative block cursor-pointer group">
                            <input 
                                type="radio" 
                                name="selecciones.{{ $candidato['categoria_id'] }}" 
                                value="{{ $id }}" 
                                wire:model="selecciones.{{ $candidato['categoria_id'] }}"
                                class="sr-only peer"
                            >

                            <div class="absolute inset-0 z-20 opacity-0 scale-90 peer-checked:opacity-100 peer-checked:scale-100 transition-all duration-300 ease-out pointer-events-none overflow-hidden rounded-xl">
                                <div class="absolute inset-0 bg-gray-700/40 rounded-xl"></div>
                                <div class="absolute top-1/2 left-[10%] w-[80%] h-1 bg-white transform -rotate-45 origin-center transition-transform duration-300 ease-out"></div>
                                <div class="absolute top-1/2 left-[10%] w-[80%] h-1 bg-white transform rotate-45 origin-center transition-transform duration-300 ease-out"></div>
                            </div>

                            <div class="relative z-10 w-full bg-white rounded-xl text-center flex flex-col items-center shadow transition group-hover:shadow-lg border-2 peer-checked:border-green-600 group-hover:border-green-600 overflow-hidden">
                                @if ($candidato['foto'])
                                    <div class="w-full p-1">
                                        <img src="{{ asset('storage/' . $candidato['foto']) }}" alt="Foto"
                                            class="w-full h-52 object-cover rounded-xl">
                                    </div>
                                @else
                                    <div class="w-full p-1">
                                        <div class="w-full h-52 flex items-center justify-center bg-gray-100 text-gray-600 text-lg font-semibold rounded-xl">
                                            Voto en blanco
                                        </div>
                                    </div>
                                @endif

                                <div class="w-full bg-green-100 px-4 py-2 mt-auto">
                                    <h2 class="text-base font-bold text-gray-800 leading-tight break-words">
                                        {{ $candidato['nombres'] ?? 'Voto' }} {{ $candidato['apellidos'] ?? 'en blanco' }}
                                    </h2>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Botón de votar --}}
        <div class="mt-8 flex flex-col items-center space-y-2">
            <button 
                type="submit"
                class="relative flex items-center justify-center text-white font-semibold px-8 py-3 rounded-lg transition shadow-md"
                :class="completas && !enviando 
                    ? 'bg-green-600 hover:bg-green-700' 
                    : 'bg-gray-400 cursor-not-allowed'"
                :disabled="!completas || enviando"
            >
                <svg 
                    x-show="enviando"
                    class="w-5 h-5 animate-spin mr-2" 
                    fill="none" 
                    viewBox="0 0 24 24"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="white" stroke-width="4"></circle>
                    <path class="opacity-75" fill="white"
                        d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 11-8 8h4z" />
                </svg>

                <span x-show="!enviando">Votar</span>
                <span x-show="enviando">Enviando...</span>
            </button>
        </div>
    </form>
</div>
