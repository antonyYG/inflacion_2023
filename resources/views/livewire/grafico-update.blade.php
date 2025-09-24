<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center mt-6">
    <h3 class="text-lg font-semibold">Actualizar Inflación (Múltiples Países y Meses)</h3>

    @foreach($this->actualizaciones as $i => $paisData)
        <div class="border rounded p-4 mt-4">
            <div class="flex justify-between items-center mb-2">
                <h4 class="font-medium">País {{ $i + 1 }}</h4>
                @if(count($this->actualizaciones) > 1)
                    <button wire:click="eliminarPais({{ $i }})" class="text-red-500 font-bold">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                @endif
            </div>

            <div class="mb-2">
                <label>País:</label>
                <select wire:model="actualizaciones.{{ $i }}.pais" class="border rounded px-2 py-1">
                    <option value="peru">Perú</option>
                    <option value="mexico">México</option>
                    <option value="chile">Chile</option>
                </select>
            </div>

            <div class="space-y-2">
                @foreach($paisData['meses'] as $j => $mesData)
                    <div class="flex gap-2 items-center">
                        <input type="number" wire:model="actualizaciones.{{ $i }}.meses.{{ $j }}.mes" 
                               placeholder="Mes (1-12)" class="border rounded px-2 py-1 w-24">
                        <input type="number" wire:model="actualizaciones.{{ $i }}.meses.{{ $j }}.inflacion" 
                               placeholder="Inflación (%)" step="0.01" class="border rounded px-2 py-1 w-32">
                        @if(count($paisData['meses']) > 1)
                            <button wire:click="eliminarMes({{ $i }}, {{ $j }})" 
                                    class="text-red-500 font-bold">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        @endif
                    </div>
                @endforeach
                <button wire:click="agregarMes({{ $i }})" class="text-blue-500 text-sm mt-1">
                    + Agregar Mes
                </button>
            </div>
        </div>
    @endforeach

    <div class="mt-4 flex gap-2 justify-center">
        <button wire:click="agregarPais" class="px-4 py-2 bg-blue-500 text-white rounded">
            + Agregar País
        </button>
        <button wire:click="actualizarGrafico" class="px-4 py-2 bg-green-500 text-white rounded">
            Actualizar Gráfico
        </button>
    </div>

    @if($mensaje)
        <p class="mt-2 text-sm text-gray-700">{{ $mensaje }}</p>
    @endif

    <div class="mt-4">
        <h3 class="font-medium">Gráfico Actualizado</h3>
        <img src="{{ $graficoUrl }}" alt="Gráfico actualizado" class="mx-auto mt-2">
    </div>
</div>
