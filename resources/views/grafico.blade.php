<x-app-layout
title="Inflacion">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gráfico de Inflación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Gráfico original -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center">
                <h3>Gráfico Original</h3>
                <img src="{{ asset('storage/grafico.png') }}" 
                     alt="Gráfico original" class="mx-auto">
            </div>

            <!-- Gráfico actualizado -->
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('grafico-update')
        </div>
    </div>

</x-app-layout>
