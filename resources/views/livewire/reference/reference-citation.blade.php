<x-slot:title>Citações da referência</x-slot:title>

<div>

    {{-- @foreach ($citations as $title => $cits)
        <p class="text-lg font-semibold">{{ $title }}</p>
        <ul class="space-y-2">
            @foreach ($cits as $citation)
            <li>{{ $citation->content }}</li>
            @endforeach
        </ul>
    @endforeach --}}

    <x-ts-table :$headers :rows="$citations" paginate>
        <x-slot:header>
            <div class="table-header flex justify-between items-center">
                <div class="w-1/2">
                    <x-ts-input wire:model.live.debounce="q" placehoder="Buscar citação" icon="magnifying-glass" />
                </div>
            </div>
        </x-slot>
        @interact('column_production', $row)
            {{ $row->production->title }} ({{ $row->production->year }})
        @endinteract
        @interact('column_action', $row)
            <x-ts-link :href="route('project.bibliometrics.productions.show', [$project, $row->production])" wire:navigate icon="arrow-top-right-on-square" />
        @endinteract
    </x-ts-table>
</div>
