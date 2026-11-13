<x-slot:title>Estatísticas por estado</x-slot:title>

<div>
    <div class="sm:flex gap-x-6">
        @include('includes.records-nav')
        <div class="flex-auto grid grid-cols-5 gap-x-6">
            <div class="col-span-2">
                <x-ts-table :headers="$stateHeaders" :rows="$stateRows">
                    @interact('column_name', $row)
                        <span class="text-sm text-gray-500">{{ $row['region'] }}</span>
                        <button type="button" wire:click="selectState({{ $row['id'] }})">
                            {{ $row['name'] }} ({{ $row['abbreviation'] }})
                        </button>
                    @endinteract
                </x-ts-table>
            </div>

            <div class="col-span-3">
                @if ($selectedState && $stateProductions)
                    <x-ts-table :headers="$productionHeaders" :rows="$stateProductionRows">
                        <x-slot:header>
                            <div class="px-4 py-2 flex justify-between gap-4 items-center text-gray-800 font-semibold">
                                <h4>Publicações do estado: {{ $states->find($selectedState)?->name }}</h4>
                                <x-ts-button wire:click="selectState('')" icon="x-mark" outline />
                            </div>
                        </x-slot:header>
                        @interact('column_type', $row)
                            {{ $row->type->value }}
                        @endinteract
                        @interact('column_action', $row)
                            <x-ts-button icon="eye" x-on:click="$dispatch('preview-production', { id: {{ $row->id }} })" sm flat />
                        @endinteract
                    </x-ts-table>
                @else
                    <x-ts-card>
                        Selecione um estado na tabela <span class="lg:hidden">abaixo</span> <span
                            class="hidden lg:inline-flex">ao lado</span> para listar as respectivas produções.
                    </x-ts-card>
                @endif
            </div>
        </div>
    </div>
    @livewire('production.production-slide', ['project' => $project])
</div>
