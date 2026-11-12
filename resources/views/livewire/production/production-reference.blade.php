<div class="flex-1 space-y-3">
    <x-table>
        <div class="table-header flex justify-end gap-2">
            <x-ts-button text="Adicionar referência" x-on:click="$tsui.open.modal('reference-modal')" />
            <x-ts-button text="Vincular existente" x-on:click="$tsui.open.modal('sync-modal')" flat />
        </div>
        <x-slot:header>
            <th>Autor(es)</th>
            <th>Ano</th>
            <th>Título</th>
            <th>Tipo</th>
            <th width="1">Citações</th>
            <th width="1"></th>
        </x-slot>
        <x-slot:body>
            @forelse ($references as $reference)
                <tr>
                    <td>{{ $reference->short_author }}</td>
                    <td>{{ $reference->year }}{{ $reference->pivot->suffix ?? '' }}</td>
                    <td>{{ $reference->title }}</td>
                    <td>{{ $reference->type }}</td>
                    <td>{{ $reference->citations_count }}</td>
                    <td>
                        <x-ts-dropdown icon="ellipsis-vertical" static>
                            <x-ts-dropdown.items text="Ver citações" />
                            <x-ts-dropdown.items wire:click="remove({{ $reference->id }})" text="Remover" separator />
                        </x-ts-dropdown>
                    </td>
                </tr>
            @empty
                <div class="p-4">
                    Nenhuma referência adicionada para esta produção.
                </div>
            @endforelse
        </x-slot>
        <x-slot:pagination>
            {{ $references->links() }}
        </x-slot>
    </x-table>
    <x-ts-modal id="reference-modal" title="Adicionar/Editar referência" size="sm" wire="createModal">
        <form class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <x-ts-input wire:model="short_author" label="Autor(es) abreviado(s) *" />
            </div>
            <div>
                <x-ts-input type="number" wire:model="year" label="Ano *" min="1900" :max="$production->year" />
            </div>
            <div class="col-span-3">
                <x-ts-input wire:model="long_author" label="Nome(s) completo(s)" />
            </div>
            <div class="col-span-2">
                <x-ts-select.styled wire:model="type" label="Tipo *" :options="$avaliable_types" />
            </div>
            <div>
                <x-ts-select.styled wire:model="suffix" :options="['A', 'B', 'C', 'D', 'E']" label="Sufixo" />
            </div>
            <div class="col-span-3">
                <x-ts-input wire:model="title" label="Título *" />
            </div>
            <div class="col-span-3">
                <x-ts-textarea wire:model.live.blur="full" label="Referência completa" />
            </div>
        </form>
        <x-slot:footer>
            <x-ts-button x-on:click="$tsui.close.modal('reference-modal'), $wire.resetFields()" text="Cancelar" flat />
            <x-ts-button type="submit" wire:click="submitReference" text="Salvar" />
        </x-slot:footer>
    </x-ts-modal>

    <x-ts-modal id="sync-modal" title="Vincular referência existente" size="sm" wire="syncModal">
        <x-ts-input wire:model.live.debounce="search_other" icon="magnifying-glass" position="right" />
        <div class="space-y-3 mt-3">
            @forelse ($other_references as $or)
                <x-ts-radio wire:model="or" :value="$or->id">
                    <x-slot:label>
                        {{ $or->short_author }},
                        {{ $or->year }}.
                        {{ $or->title }}
                        ({{ $or->type }})
                    </x-slot:label>
                </x-ts-radio>
            @empty
                <small>Nada encontrado</small>
            @endforelse
        </div>
        @if ($or)
            <div class="mt-3">
                <x-ts-select.styled wire:model="suffix" :options="['a', 'b', 'c', 'd', 'e']" label="Sufixo" />
            </div>
        @endif
        <x-slot:footer>
            <x-ts-button x-on:click="$tsui.close.modal('sync-modal'), $wire.resetFields()" text="Cancelar" flat />
            <x-ts-button type="button" wire:click="submitSync" text="Salvar" />
        </x-slot:footer>
    </x-ts-modal>
</div>
