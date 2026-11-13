<x-slot:title>Citações</x-slot:title>

<div>
    <div class="flex gap-6">
        <div class="w-[320px] space-y-4">
            <x-ts-card header="Buscar expressão">
                <x-ts-input wire:model.live.debounce="q" icon="magnifying-glass" placeholder="Busca" />
            </x-ts-card>
            <x-ts-card header="Produções">
                @if ($productions)
                    <div class="flex flex-col space-y-2 text-sm">
                        @forelse ($productions as $production)
                            <a class="cursor-pointer"
                                wire:click="setProduction({{ $production->id }})">{{ $production->title }}</a>
                        @empty
                            <p>Nenhuma produção com citação encontrada.</p>
                        @endforelse
                    </div>
                @else
                    <x-ts-button text="Carregar" wire:click="loadProductions" class="w-full" flat />
                @endif
            </x-ts-card>
            <x-ts-card header="Ano das produções">
                @for ($i = $project->bibliometric->start_year; $i <= $project->bibliometric->end_year; $i++)
                    <x-ts-button :text="$i" wire:click="setYear({{ $i }})" color="black" sm flat />
                @endfor
            </x-ts-card>
            <x-ts-card header="Referências">
                @if ($references)
                    <div class="flex flex-col space-y-2 text-sm">
                        @forelse ($references as $reference)
                            <a class="cursor-pointer"
                                wire:click="setReference({{ $reference->id }})">{{ $reference->title }}</a>
                        @empty
                            <p>Nenhuma referência com citação encontrada.</p>
                        @endforelse
                    </div>
                @else
                    <x-ts-button text="Carregar" wire:click="loadReferences" class="w-full" flat />
                @endif
            </x-ts-card>
        </div>
        <div class="flex-1 space-y-6">
            @if ($q || $prod || $ref || $ano)
                <x-ts-card header="Filtros aplicados" class="divide-y">
                    @if ($q)
                        <div class="py-2 flex justify-between">
                            <div>Termo buscado: {{ $q }}</div>
                            <x-ts-button icon="x-mark" x-on:click="$wire.q = ''" sm flat />
                        </div>
                    @endif
                    @if ($prod)
                        <div class="py-2 flex justify-between">
                            <div>Produção: {{ $prod }}</div>
                            <x-ts-button icon="x-mark" wire:click="setProduction({{ null }})" sm flat />
                        </div>
                    @endif
                    @if ($ref)
                        <div class="py-2 flex justify-between">
                            <div>Referência: {{ $ref }}</div>
                            <x-ts-button icon="x-mark" wire:click="setReference({{ null }})" sm flat />
                        </div>
                    @endif
                    @if ($ano)
                        <div class="py-2 flex justify-between">
                            <div>Ano: {{ $ano }}</div>
                            <x-ts-button icon="x-mark" wire:click="setYear({{ null }})" sm flat />
                        </div>
                    @endif
                </x-ts-card>
            @endif
            <div class="space-y-2">
                @forelse ($citations as $citation)
                    <div class="bg-white border rounded-lg shadow">
                        <p class="p-2">{{ $citation->content }}</p>
                        <div class="px-2 py-1.5 space-y-1 bg-gray-50 divide-y rounded-b-lg text-sm">
                            @if (!$prod)
                                <p>Produção:
                                    @foreach ($citation->production->authors as $author)
                                        {{ $author['lastname'] }}@if (!$loop->last)
                                        ; @else,
                                        @endif
                                    @endforeach
                                    {{ $citation->production->year }}.
                                    {{ $citation->production->title }}.
                                </p>
                            @endif
                            @if (!$ref)
                                <p>Referência:
                                    {{ $citation->reference->short_author }},
                                    {{ $citation->reference->year }}.
                                    {{ $citation->reference->title }}.
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <x-ts-card class="text-center py-6">
                        Nenhuma citação encontrada.
                    </x-ts-card>
                @endforelse
                {{ $citations->links() }}
            </div>
        </div>
    </div>
</div>
