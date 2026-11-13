<x-slot:title>Referências</x-slot:title>
<x-slot:actions>
    <div class="flex flex-col gap-1">
        <x-ts-button text="Referências do projeto" :href="route('project.bibliometrics.references.project', $project)" flat />
    </div>
</x-slot:actions>

<div>
    <x-ts-table :$headers :rows="$references" paginate>
        <x-slot:header>
            <div class="table-header flex justify-between items-center">
                <div class="w-1/2">
                    <x-ts-input wire:model.live.debounce="q" placehoder="Buscar" icon="magnifying-glass" />
                </div>
                <div>
                    <x-ts-button icon="funnel" flat x-on:click="$tsui.open.slide('filters')" />
                </div>
            </div>
        </x-slot:header>
        @interact('column_title', $row)
            <a href="{{ route('project.bibliometrics.references.show', [$project, $row]) }}">{{ $row->title }}</a>
        @endinteract
        @interact('column_action', $row)
            <x-ts-link :href="route('project.bibliometrics.references.citations', [$project, $row])" text="Ver citações" />
        @endinteract
    </x-ts-table>
</div>
