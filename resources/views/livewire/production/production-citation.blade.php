<div class="flex-1">
    <x-ts-toast />
    <x-table>
        <div class="table-header flex justify-between items-center">
            <div class="w-1/2">
                <x-ts-input wire:model.live.debounce="q" placehoder="Buscar citação" icon="magnifying-glass" />
            </div>
            <div>
                <x-ts-button text="Adicionar citação" x-on:click="$tsui.open.modal('citation-modal')" />
            </div>
        </div>
        <x-slot:header>
            <th>Citação</th>
            <th>Tipo</th>
            <th>Referência</th>
            <th></th>
        </x-slot>
        <x-slot:body>
            @foreach ($citations as $citation)
                <tr>
                    <td class="!text-wrap">
                        {{ $citation->content }}
                    </td>
                    <td>{{ $citation->type }}</td>
                    <td>
                        <a href="{{ route('project.bibliometrics.citations.index', [$project, 'ref' => $citation->reference->id]) }}"
                            wire:navigate>
                            {{ $citation->reference->short_author }},
                            {{ $citation->reference->year }}
                        </a>
                    </td>
                    <td></td>
                </tr>
            @endforeach
        </x-slot>
        <x-slot:pagination>
            {{ $citations->links() }}
        </x-slot>
    </x-table>

    <x-ts-modal title="Adicionar citação" x-on:open="$wire.loadReferences" id="citation-modal" persistent>
        <form wire:submit="submit" id="citation-form" class="space-y-4">

            <x-ts-textarea label="Conteúdo *" wire:model.live.blur="content" />
            <x-ts-select.styled label="Tipo *" wire:model="type" :options="['Direta', 'Indireta']" />

            <div>
                <x-ts-label label="Referência *" />
                <div class="mb-4">
                    <x-ts-input wire:model.live.debounce="searchReference" placeholder="Buscar nome ou título" />
                </div>
                <div class="w-full" wire:loading wire:target="loadReferences">
                    <x-ts-alert text="Carregando..." light />
                </div>
                @forelse ($references as $reference)
                    <x-ts-radio wire:model="reference_id" :value="$reference->id" :label="$reference->short_author . ', ' . $reference->year . '. ' . $reference->title" class="my-2" invalidate />
                @empty
                    <p wire:loading.remove class="text-sm my-2">Nenhuma referência encontrada</p>
                @endforelse
            </div>
        </form>
        <x-slot:footer>
            <x-ts-button text="Fechar" x-on:click="$tsui.close.modal('citation-modal')" flat />
            <x-ts-button type="submit" text="Salvar" form="citation-form" />
        </x-slot>
    </x-ts-modal>

</div>
