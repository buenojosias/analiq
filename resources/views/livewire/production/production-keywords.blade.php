<div class="sm:w-1/2 space-y-4">
    <x-ts-table :$headers :rows="$keywords" headerless>
        @interact('column_action', $row)
            <x-ts-dropdown icon="ellipsis-vertical" static>
                <x-ts-dropdown.items icon="pencil-square" text="Buscar palavra-chave" />
                <x-ts-dropdown.items icon="pencil-square" text="Editar" separator />
                <x-ts-dropdown.items wire:click="deleteKeyword({{ $row->id }})" icon="trash" text="Excluir" separator />
            </x-ts-dropdown>
        @endinteract
    </x-ts-table>
    <livewire:modals.keyword-modal :production="$production" :keywords="$keywords" />
</div>
