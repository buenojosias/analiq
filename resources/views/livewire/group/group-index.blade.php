<div>
    <x-ts-toast />
    <x-ts-dialog />
    <x-page-header title="Grupos de produções" :subtitle="$project->theme">
        <div class="flex flex-col gap-4">
            <x-ts-button text="Criar grupo" wire:click="$dispatch('open-modal')" />
        </div>
    </x-page-header>

    <x-ts-table :$headers :rows="$this->groups">
        @interact('column_name', $row)
            <div class="flex flex-col">
                <a href="{{ route('project.bibliometrics.productions.groups.show', [$project, $row]) }}">{{ $row->name }}</a>
                <span class="text-sm text-gray-500">{{ $row->description }}</span>
            </div>
        @endinteract
        @interact('column_action', $row)
            <x-ts-button icon="pencil" color="secondary" flat sm />
            <x-ts-button icon="trash" color="red" flat sm />
        @endinteract
        <x-slot:empty>Nenhum grupo de produções criado neste projeto.</x-slot:empty>
    </x-ts-table>

    @livewire('group.group-create', ['project' => $project])
</div>
