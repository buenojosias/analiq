<x-slot:title>Grupo de produções</x-slot:title>
<x-slot:actions>
    <div class="flex flex-col gap-1">
        <x-ts-button text="Adicionar produção" x-on:click="Livewire.dispatch('open-slide')" />
        <x-ts-button text="Carregar resumos" x-on:click="Livewire.find('{{ $this->getId() }}').loadAbstract()" />
    </div>
</x-slot:actions>

<div>
    <x-ts-toast />
    <x-ts-dialog />

    <x-ts-table :$headers :rows="$this->groupProductions">
        @if ($group->description)
            <x-slot:header>{{ $group->description }}</x-slot:header>
        @endif
        @interact('column_type', $row)
            {{ $row->type->value }}
        @endinteract
        @interact('column_authors', $row)
            {{ $row->authors->map(fn ($author) => $author->forename.' '.$author->lastname)->join('; ') }}
        @endinteract
        @interact('column_note', $row)
            {{ $row->pivot->note }}
        @endinteract
        @interact('column_abstract', $row)
            {{ $row->abstract?->content }}
        @endinteract
        @interact('column_action', $row)
            <x-ts-button icon="eye" x-on:click="$dispatch('preview-production', { id: {{ $row->id }} })" sm flat />
            <x-ts-button icon="link-slash" wire:click="detach({{ $row->id }})" color="red" flat sm />
        @endinteract
        <x-slot:empty>Nenhuma produção adicionada ao grupo.</x-slot:empty>
    </x-ts-table>
    @livewire('group.attach-production', ['project' => $project, 'group' => $group, 'groupProductions' => $this->groupProductions])
    @livewire('production.production-slide', ['project' => $project])
</div>
