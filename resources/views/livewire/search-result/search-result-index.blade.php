<x-slot:title>Resultado preliminar</x-slot:title>
<x-slot:actions>
    <x-ts-button text="Adicionar resultado" x-on:click="Livewire.find('{{ $this->getId() }}').$set('modal', true)" />
</x-slot:actions>

<div>
    <x-ts-toast />
    <x-ts-dialog />

    <div x-data="{ expanded: false }">
        <x-ts-button x-on:click="expanded = !expanded" text="Tabela detalhada" icon="chevron-down" flat />
        <div x-show="expanded" x-collapse>
            <x-ts-table :headers="$detailHeaders" :rows="$results->sortBy('repository')">
                @interact('column_terms', $row)
                    {{ collect($row->terms)->join(' AND ') }}
                @endinteract
                @interact('column_sections', $row)
                    {{ collect($row->sections)->join(' / ') }}
                @endinteract
                @interact('column_types', $row)
                    {{ collect($row->types)->join(' / ') }}
                @endinteract
                @interact('column_action', $row)
                    <x-ts-button icon="trash" wire:click="delete({{ $row->id }})" color="red" sm flat />
                @endinteract
            </x-ts-table>
        </div>
    </div>

    <div class="mt-6 md:grid grid-cols-2 gap-6">
        <x-ts-table :headers="$repositoryMatrixHeaders" :rows="$repositoryMatrixRows">
            <x-slot:header>Lista por repositórios</x-slot:header>
            <x-slot:empty>Nada adicionado</x-slot:empty>
        </x-ts-table>

        <x-ts-table :headers="$yearMatrixHeaders" :rows="$yearMatrixRows">
            <x-slot:header>Lista por anos</x-slot:header>
            <x-slot:empty>Nada adicionado</x-slot:empty>
        </x-ts-table>

        <div class="col-span-2">
            <h2 class="text-lg font-semibold">Detalhamento dos repositórios</h2>
        </div>

        @forelse ($results->groupBy('repository') as $repository => $items)
            <x-ts-table :headers="$repositoryDetailHeaders" :rows="$items">
                <x-slot:header>{{ $repository }}</x-slot:header>
                @interact('column_terms', $row)
                    {{ collect($row->terms)->join(' AND ') }}
                @endinteract
                @interact('column_sections', $row)
                    {{ collect($row->sections)->join(' / ') }}
                @endinteract
                @interact('column_types', $row)
                    {{ collect($row->types)->join(' / ') }}
                @endinteract
            </x-ts-table>
        @empty
            <x-ts-card>
                <div class="text-center text-sm">Nada adicionado</div>
            </x-ts-card>
        @endforelse
    </div>

    <x-ts-modal title="Adicionar resultado" wire persistent>
        @if (session('success'))
            <x-ts-alert icon="check-circle" :text="session('success')" color="green" light close />
        @endif
        <form wire:submit="submit" id="result-form" class="sm:grid grid-cols-6 gap-4">
            <div class="col-span-2">
                <x-ts-select.styled label="Repositório *" wire:model="repository" :options="$bibliometricRepositories" />
            </div>
            <div class="col-span-4">
                <x-ts-select.styled label="Palavra(s) buscada(s) *" wire:model="terms"
                    placeholder="Selecione uma ou mais opções" :options="$bibliometricTerms" multiple />
            </div>
            <div class="col-span-3">
                <x-ts-select.styled label="Tipo(s) buscado(s) *" wire:model="types"
                    placeholder="Selecione uma ou mais opções" :options="$bibliometricTypes" multiple />
            </div>
            <div class="col-span-3">
                <x-ts-select.styled label="Seções(s) buscada(s) *" wire:model="sections"
                    placeholder="Selecione uma ou mais opções" :options="$avaliableSections" multiple />
            </div>
            <div class="col-span-2">
                <x-ts-select.styled label="Idioma *" wire:model="language" :options="$bibliometricLanguages" />
            </div>
            <div class="col-span-2">
                <x-ts-select.styled label="Ano ponderado" wire:model="year" :options="$bibliometricYears" />
            </div>
            <div class="col-span-2">
                <x-ts-input label="Resultados *" wire:model="quantity" type="number" min="0" />
            </div>
        </form>
        <x-slot:footer>
            <x-ts-button type="submit" form="result-form" text="Adicionar" />
        </x-slot>
    </x-ts-modal>
</div>
