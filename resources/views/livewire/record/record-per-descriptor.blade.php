<x-slot:title>Estatísticas por descritores</x-slot:title>

<div>
    <x-ts-toast />
    <div class="sm:flex gap-x-6">
        @include('includes.records-nav')
        <div class="flex-auto grid grid-cols-5 gap-x-6">
            <div class="col-span-2">
                <x-ts-table :headers="$wordHeaders" :rows="$wordRows">
                    @interact('column_word', $row)
                        <button type="button" class="cursor-pointer text-wrap text-left" wire:click="selectWord(@js($row['word']))">
                            {{ $row['word'] }}
                        </button>
                    @endinteract
                </x-ts-table>
            </div>
            <div class="col-span-3 space-y-3">
                @if ($selectedWords && $records->count())
                    <x-ts-table :headers="$recordHeaders" :rows="$records">
                        <x-slot:header>
                            <div class="px-4 py-2 flex justify-between gap-4 items-center text-gray-800 font-semibold">
                                <h4>Produções com o descritor: {{ $descriptor }}</h4>
                                @livewire('components.bulk-dropdown', ['project' => $project])
                                <x-ts-button wire:click="selectWord('')" icon="x-mark" outline />
                            </div>
                        </x-slot:header>
                        @interact('column_selection', $row)
                            @livewire('components.bulk-checkbox', ['production_id' => $row->id], key($row->id))
                        @endinteract
                        @interact('column_type', $row)
                            {{ $row->type->value }}
                        @endinteract
                        @interact('column_authors', $row)
                            @foreach ($row->authors as $author)
                                {{ $author->forename . ' ' . $author->lastname }}{!! !$loop->last ? ';<br>' : '' !!}
                            @endforeach
                        @endinteract
                        @interact('column_action', $row)
                            <x-ts-button icon="eye" x-on:click="$dispatch('preview-production', { id: {{ $row->id }} })" sm flat />
                        @endinteract
                    </x-ts-table>
                @else
                    <x-ts-card>
                        Selecione um descritor na tabela <span class="lg:hidden">abaixo</span> <span
                            class="hidden lg:inline-flex">ao lado</span> para listar as respectivas produções.
                    </x-ts-card>
                @endif

                <div x-data="{ open: true }">
                    <button type="button" class="mb-2 font-semibold" x-on:click="open = !open">Lista por tipo de produção</button>
                    <div x-show="open" x-collapse>
                        <x-ts-table :headers="$typeMatrix['headers']" :rows="$typeMatrix['rows']" />
                    </div>
                </div>
                <div x-data="{ open: true }">
                    <button type="button" class="mb-2 font-semibold" x-on:click="open = !open">Lista por repositório</button>
                    <div x-show="open" x-collapse>
                        <x-ts-table :headers="$repositoryMatrix['headers']" :rows="$repositoryMatrix['rows']" />
                    </div>
                </div>
                <div x-data="{ open: true }">
                    <button type="button" class="mb-2 font-semibold" x-on:click="open = !open">Lista por ano</button>
                    <div x-show="open" x-collapse>
                        <x-ts-table :headers="$yearMatrix['headers']" :rows="$yearMatrix['rows']" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('production.production-slide', ['project' => $project])
</div>
