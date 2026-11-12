<div>
    <x-page-header title="Estatísticas por autor" />
    <div class="sm:flex gap-x-6">
        @include('includes.records-nav')
        <div class="flex-auto grid grid-cols-6 gap-x-6">
            <div class="col-span-2">
                <x-ts-table :headers="$authorHeaders" :rows="$authorRows">
                    @interact('column_name', $row)
                        <button type="button" wire:click="selectAuthor(@js($row['name']), @js($row['ids']))">{{ $row['name'] }}</button>
                    @endinteract
                </x-ts-table>
            </div>

            <div class="col-span-3">
                @if ($selectedAuthor && $authorProductions)
                    <x-ts-table :headers="$productionHeaders" :rows="$authorProductions">
                        <x-slot:header>
                            <div class="px-4 py-2 flex justify-between gap-4 items-center text-gray-800 font-semibold">
                                <h4>Publicações do autor: {{ $selectedAuthorName }}</h4>
                                <x-ts-button wire:click="selectAuthor('', '')" icon="x-mark" outline />
                            </div>
                        </x-slot:header>
                        @interact('column_full_title', $row)
                            <a href="{{ route('project.bibliometrics.productions.show', [$project, $row]) }}">{{ $row->full_title }}</a>
                            @if ($row->institution) ({{ $row->institution }}) @endif
                        @endinteract
                        @interact('column_type', $row)
                            {{ $row->type->value }}
                        @endinteract
                        @interact('column_action', $row)
                            <x-ts-button icon="eye" x-on:click="$dispatch('preview-production', { id: {{ $row->id }} })" sm flat />
                        @endinteract
                    </x-ts-table>
                @else
                    <x-ts-card>
                        Selecione um autor na tabela <span class="lg:hidden">abaixo</span> <span
                            class="hidden lg:inline-flex">ao lado</span> para listar as respectivas produções.
                    </x-ts-card>
                @endif
            </div>
        </div>
    </div>
    @livewire('production.production-slide', ['project' => $project])
</div>
