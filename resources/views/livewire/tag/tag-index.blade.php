<x-slot:title>Tags do projeto</x-slot:title>

<div>
    <x-ts-toast />
    <div class="flex flex-col lg:flex-row gap-6">
        <div>
            <x-ts-table :headers="$tagHeaders" :rows="$tags">
                @interact('column_name', $row)
                    <button type="button" wire:click="selectTag({{ $row->id }})" class="font-semibold text-gray-900">
                        {{ $row->name }}
                    </button>
                @endinteract
            </x-ts-table>
            <div class="mt-4">
                <x-ts-button text="Nova tag" wire:click="$toggle('modal')" class="w-full" />
            </div>
        </div>

        <div class="lg:w-2/3 space-y-4">
            @if ($selectedTag)
                <x-ts-table :headers="$productionHeaders" :rows="$selectedTag->productions">
                    <x-slot:header>
                        <div class="px-4 py-2 flex justify-between gap-4 items-center text-gray-800 font-semibold">
                            <h4>Publicações com a tag: {{ $selectedTag->name }}</h4>
                            <x-ts-button wire:click="unselectTag" icon="x-mark" flat />
                        </div>
                    </x-slot:header>
                    @interact('column_type', $row)
                        {{ $row->type->value }}
                    @endinteract
                    @interact('column_action', $row)
                        <x-ts-button icon="eye" x-on:click="$dispatch('preview-production', { id: {{ $row->id }} })" sm flat />
                        <x-ts-button wire:click="deleteProduction({{ $row->id }})" icon="archive-box-arrow-down" color="red" flat sm />
                    @endinteract
                    <x-slot:empty>Nenhuma produção vinculada com esta tag.</x-slot:empty>
                </x-ts-table>

            @else
                <x-ts-card>
                    Selecione uma tag na tabela <span class="lg:hidden">abaixo</span> <span
                        class="hidden lg:inline-flex">ao lado</span> para ver as produções relacionadas.
                </x-ts-card>
            @endif
        </div>
    </div>
    <x-ts-modal title="Criar tag" persistent wire size="sm">
        <x-ts-input type="text" wire:model="newTag" placeholder="Nome da tag" required />
        <x-slot:footer>
            <x-ts-button text="Salvar" wire:click="createTag" class="w-full" />
        </x-slot:footer>
    </x-ts-modal>
    @livewire('production.production-slide', ['project' => $project])
</div>
