<div>
    <x-page-header title="Estatísticas por cidade" />
    <div class="sm:flex gap-x-6">
        @include('includes.records-nav')
        <div class="flex-auto grid grid-cols-5 gap-x-6">
            <div class="col-span-2">
                <x-ts-table :headers="$cityHeaders" :rows="$cityRows">
                    @interact('column_city', $row)
                        @if ($row['key'] === '')
                            <button type="button" wire:click="selectWithoutCity">Não informada</button>
                        @else
                            <button type="button" wire:click="selectCity(@js($row['key']))">
                                {{ $row['city'] }} ({{ $row['state'] }})
                            </button>
                        @endif
                    @endinteract
                </x-ts-table>
            </div>

            <div class="col-span-3 space-y-4">
                @if ($selectedCity && $cityProductions)
                    <x-ts-table :headers="$productionHeaders" :rows="$cityProductions">
                        <x-slot:header>
                            <div class="px-4 py-2 flex justify-between gap-4 items-center text-gray-800 font-semibold">
                                <h4>Publicações da cidade: {{ $selectedCity }}</h4>
                                <x-ts-button wire:click="selectCity('')" icon="x-mark" outline />
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
                        Selecione uma cidade na tabela <span class="lg:hidden">abaixo</span> <span
                            class="hidden lg:inline-flex">ao lado</span> para listar as respectivas produções.
                    </x-ts-card>
                @endif

                <div x-data="{ expanded: false }">
                    <x-ts-button x-on:click="expanded = !expanded" text="Quantidade por ano" icon="chevron-down" flat />
                    <div x-show="expanded" x-collapse>
                        <x-ts-table :headers="$yearMatrix['headers']" :rows="$yearMatrix['rows']" />
                    </div>
                </div>

                <div x-data="{ expanded: false }">
                    <x-ts-button x-on:click="expanded = !expanded" text="Quantidade por tipo" icon="chevron-down" flat />
                    <div x-show="expanded" x-collapse>
                        <x-ts-table :headers="$typeMatrix['headers']" :rows="$typeMatrix['rows']" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('production.production-slide', ['project' => $project])
</div>
