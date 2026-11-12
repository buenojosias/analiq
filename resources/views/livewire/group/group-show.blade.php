<div>
    <x-ts-toast />
    <x-ts-dialog />
    <x-page-header title="Grupo de produções" :subtitle="$group->name">
        <div class="flex flex-col gap-1">
            <x-ts-button text="Adicionar produção" wire:click="$dispatch('open-slide')" />
            <x-ts-button text="Carregar resumos" wire:click="loadAbstract" />
        </div>
    </x-page-header>

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
