<x-slot:title>Palavras-chave</x-slot:title>

<section>
    <x-ts-toast />
    <div class="flex flex-col lg:flex-row-reverse gap-6">
        <div class="lg:w-2/3">
            @if ($selectedKeyword && $keywordProductions)
                <x-ts-table :headers="$productionHeaders" :rows="$keywordProductions">
                    <x-slot:header>
                        <div class="px-4 py-2 flex justify-between gap-4 items-center text-gray-800 font-semibold">
                            <h4>Publicações com a palavra-chave: {{ $selectedKeyword }}</h4>
                            @livewire('components.bulk-dropdown', ['project' => $project])
                            <x-ts-button wire:click="selectWord('')" icon="x-mark" flat />
                        </div>
                    </x-slot:header>
                    @interact('column_select', $row)
                        @livewire('components.bulk-checkbox', ['production_id' => $row->id], key($row->id))
                    @endinteract
                    @interact('column_type', $row)
                        {{ $row->type->value }}
                    @endinteract
                    @interact('column_keywords', $row)
                        {{ $row->keywords->pluck('value')->join('; ') }}
                    @endinteract
                    @interact('column_action', $row)
                        <x-ts-button icon="eye" x-on:click="$dispatch('preview-production', { id: {{ $row->id }} })" sm flat />
                        <x-ts-button wire:click="deleteProduction({{ $row->id }})" icon="archive-box-arrow-down" color="red" flat sm />
                    @endinteract
                    <x-slot:empty>Palavra-chave não encontrada nas produções.</x-slot:empty>
                </x-ts-table>
            @else
                <x-ts-card>
                    Selecione uma palavra na tabela <span class="lg:hidden">abaixo</span> <span
                        class="hidden lg:inline-flex">ao lado</span> para ver as produções que a possuem.
                </x-ts-card>
            @endif
        </div>
        <div class="lg:w-1/3">
            <x-ts-table :headers="$keywordHeaders" :rows="$keywordRows">
                <x-slot:header>
                <div class="px-4 py-2 flex justify-between gap-4 items-center text-gray-800 font-semibold">
                    <x-ts-dropdown>
                        <x-slot:action>
                            <x-ts-button x-on:click="show = !show" sm outline>Tipos de produção</x-ts-button>
                        </x-slot:action>
                        @foreach ($bibliometric->types as $type)
                            <x-ts-dropdown.items>
                                <x-ts-checkbox name="production_types[]" wire:model.live="production_types"
                                    :value="$type" :label="$type" />
                            </x-ts-dropdown.items>
                        @endforeach
                    </x-ts-dropdown>
                </div>
                </x-slot:header>
                @interact('column_value', $row)
                    <button type="button" wire:click="selectWord(@js($row['value']))">{{ $row['value'] }}</button>
                @endinteract
            </x-ts-table>
        </div>
    </div>
    @livewire('production.production-slide', ['project' => $project])
</section>
