<section>
    <x-ts-loading>
        <div class="flex items-center text-primary-500 dark:text-white">
            <x-ts-icon name="arrow-path" class="mr-2 h-10 w-10 animate-spin" />
            Processando...
        </div>
    </x-ts-loading>
    <x-page-header title="Gerar ranking de palavras" />
    <div class="">
        <div class="">
            <x-ts-errors />
            <div class="px-4 py-2 flex flex-wrap justify-between items-center bg-white rounded-t">
                <div class="flex gap-2 items-center">
                    <x-ts-dropdown>
                        <x-slot:action>
                            <x-ts-button x-on:click="show = !show" sm outline>Tipos de produção</x-ts-button>
                        </x-slot:action>
                        @foreach ($bibliometric->types as $type)
                            <x-ts-dropdown.items>
                                <x-ts-checkbox name="production_types[]" wire:model="production_types" :value="$type"
                                    :label="$type" invalidate />
                            </x-ts-dropdown.items>
                        @endforeach
                    </x-ts-dropdown>
                    <x-ts-dropdown>
                        <x-slot:action>
                            <x-ts-button x-on:click="show = !show" sm outline>Seções</x-ts-button>
                        </x-slot:action>
                        <x-ts-dropdown.items>
                            <x-ts-checkbox name="sections[]" id="resumo" wire:model="sections" value="Resumo" label="Resumo"
                                invalidate />
                        </x-ts-dropdown.items>
                        <x-ts-dropdown.items>
                            <x-ts-checkbox name="sections[]" id="textual" wire:model="sections" value="Textual"
                                label="Conteúdo textual" invalidate />
                        </x-ts-dropdown.items>
                    </x-ts-dropdown>
                    <x-ts-dropdown>
                        <x-slot:action>
                            <x-ts-button x-on:click="show = !show" sm outline>Limite</x-ts-button>
                        </x-slot:action>
                        <x-ts-dropdown.items class="items">
                            <x-ts-input wire:model="wordsLimit" />
                        </x-ts-dropdown.items>
                    </x-ts-dropdown>
                    <x-ts-button wire:click="generate" text="Gerar" />
                </div>
                @if ($results && count($results) > 0)
                    <div>
                        <x-ts-button wire:click="$toggle('savingModal')" text="Salvar resultado" />
                    </div>
                @endif
            </div>
            <x-ts-table :$headers :rows="$resultRows">
                @interact('column_action', $row)
                    <x-ts-dropdown text="Ações" position="bottom-end">
                        <x-ts-dropdown.items text="Ver contexto" />
                        <x-ts-dropdown.items wire:click="selectResult({{ $row['id'] }})" text="Listar produções" />
                        <x-ts-dropdown.items text="Exluir" separator />
                    </x-ts-dropdown>
                @endinteract
            </x-ts-table>
        </div>

        <div class="col-span-3">
            @if ($selectedResult)
                {{-- {{ $selectedResult }} --}}
                {{-- <livewire:word-ranking.word-ranking-productions :$selectedResult /> --}}
                {{ $foo }}
            @endif
        </div>
    </div>

    <x-ts-modal title="Salvar relatório" wire="savingModal" size="sm">
        <div class="space-y-4">
            <x-ts-input wire:model="title" label="Título do relatório" hint="Opcional" />
            <x-ts-textarea wire:model="description" label="Breve descrição" hint="Opcional" />
        </div>
        <x-slot:footer>
            <x-ts-button text="Confirmar" wire:click="save" />
        </x-slot:footer>
    </x-ts-modal>

    @if ($savedWordRanking)
        <x-ts-modal title="Relatório salvo com sucesso" wire="savedModal" size="sm">
            O que você deseja fazer agora?
            <div class="mt-4 flex flex-col space-y-2">
                <x-ts-button wire:click="clear" text="Gerar novo relatório" color="white" />
                <x-ts-button :href="route('project.bibliometrics.wordrankings.show', [$project, $savedWordRanking->id])" wire:navigate text="Ir para o relatório" color="white" />
                <x-ts-button :href="route('project.bibliometrics.wordrankings.index', [$project])" wire:navigate text="Voltar para lista" color="white" />
            </div>
        </x-ts-modal>
    @endif
</section>
