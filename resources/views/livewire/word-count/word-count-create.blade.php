<x-slot:title>Contagem de palavras</x-slot:title>

<section>
    <x-ts-loading loading="generate">
        <div class="flex items-center text-primary-500 dark:text-white">
            <x-ts-icon name="arrow-path" class="mr-2 h-10 w-10 animate-spin" />
            Processando...
        </div>
    </x-ts-loading>
    <x-ts-errors />
    <div class="px-4 py-2 flex justify-between items-center bg-white rounded-t">
        <div class="flex gap-2 items-center">
            <x-ts-input wire:model="word" placeholder="Palavra ou expressão" invalidate />
            <x-ts-dropdown>
                <x-slot:action>
                    <x-ts-button x-on:click="show = !show" sm outline>Definições</x-ts-button>
                </x-slot:action>
                <p class="p-2 font-semibold border-b text-sm text-gray-800">Tipos de produção</p>
                @foreach ($bibliometric->types as $type)
                    <x-ts-dropdown.items>
                        <x-ts-checkbox name="production_types[]" wire:model="production_types" :value="$type"
                            :label="$type" invalidate />
                    </x-ts-dropdown.items>
                @endforeach
                <p class="p-2 font-semibold border-b text-sm text-gray-800">Seções</p>
                <x-ts-dropdown.items>
                    <x-ts-checkbox name="sections[]" id="resumo" wire:model="sections" value="Resumo" label="Resumo" invalidate />
                </x-ts-dropdown.items>
                <x-ts-dropdown.items>
                    <x-ts-checkbox name="sections[]" id="textual" wire:model="sections" value="Textual" label="Conteúdo textual"
                        invalidate />
                </x-ts-dropdown.items>
            </x-ts-dropdown>
            <x-ts-button wire:click="generate" text="Gerar" />
        </div>
        @if ($results && count($results) > 0)
            <div>
                <x-ts-button wire:click="save()" text="Salvar resultado" />
            </div>
        @endif
    </div>

    <x-ts-table :$headers :rows="$results">
        @interact('column_production', $row)
            {{ $row->production->title }}
        @endinteract
        @interact('column_type', $row)
            {{ $row->production->type->value }}
        @endinteract
        @interact('column_section', $row)
            {{ $row->section->label() }}
        @endinteract
        @interact('column_count', $row)
            {{ $row->count }} / {{ $row->total_words }}
        @endinteract
        @interact('column_percentage', $row)
            {{ $row->percentage }}%
        @endinteract
        @interact('column_action', $row)
            <x-ts-button outline text="Ver contexto"
                wire:click="loadContext({{ $row->id }}, '{{ $row->section->value }}')" />
        @endinteract
    </x-ts-table>

    @if ($content)
        <livewire:word-count.word-count-context :$content :$word />
    @endif

    @if ($savedWordCount)
        <x-ts-modal title="Contagem salva com sucesso" wire="savedModal" size="sm">
            O que você deseja fazer agora?
            <div class="mt-4 flex flex-col space-y-2">
                <x-ts-button wire:click="$toggle('savedModal')" text="Fazer nova contagem" color="white" />
                <x-ts-button :href="route('project.bibliometrics.wordcounts.show', [$project, $savedWordCount->id])" wire:navigate text="Ir para contagem" color="white" />
                <x-ts-button :href="route('project.bibliometrics.wordcounts.index', [$project])" wire:navigate text="Voltar para lista" color="white" />
            </div>
        </x-ts-modal>
    @endif

</section>
