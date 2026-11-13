<div class="flex gap-6" x-data="{ showGoal: false, showKeywords: false }">
    <div class="w-60">
        <x-ts-card header="Opções" class="space-y-2">
            <x-ts-toggle x-model="showKeywords" label="Exibir palavras-chave" />
            <x-ts-toggle x-model="showGoal" label="Exibir objetivo" />
        </x-ts-card>
    </div>
    <div class="flex-1 space-y-2">

        @foreach ($productions as $type => $types)
            <x-ts-card minimize="mount">
                <x-slot:header>
                    <h2>{{ $type }}
                        <small>({{ $types->count() }} {{ $types->count() > 1 ? 'resultados' : 'resultado' }})</small>
                    </h2>
                </x-slot:header>
                <div class="space-y-3">
                    @foreach ($types->groupBy('year') as $year => $years)
                        <x-ts-card shadowless bordered>
                            <x-slot:header>
                                <h3>{{ $year }}
                                    <small>({{ $years->count() }} {{ $years->count() > 1 ? 'resultados' : 'resultado' }})</small>
                                </h3>
                            </x-slot:header>
                            <div class="space-y-3">
                                @foreach ($years as $production)
                                    <div class="border rounded p-4">
                                        <h4 class="text-md font-semibold">{{ $production->title }}</h4>
                                        <p>{{ $production->abstract->content ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </x-ts-card>
                    @endforeach
                </div>
            </x-ts-card>
        @endforeach
    </div>
</div>
