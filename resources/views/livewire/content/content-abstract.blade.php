<div class="flex gap-6" x-data="{ showGoal: false, showKeywords: false }">
    <div class="w-60">
        <x-ts-card header="Opções" class="space-y-2">
            <x-ts-toggle x-model="showKeywords" label="Exibir palavras-chave" />
            <x-ts-toggle x-model="showGoal" label="Exibir objetivo" />
        </x-ts-card>
    </div>
    <div class="flex-1">
        <x-ts-card id="full-content">
            @foreach ($productions as $type => $types)
                <div class="mt-1" x-data="{ show_type: true }">
                    <div class="px-2 rounded-t-sm flex justify-between bg-primary-600">
                        <h1 class="text-2xl">
                            {{ $type }}
                            <small>({{ $types->count() }}
                                {{ $types->count() > 1 ? 'resultados' : 'resultado' }})</small>
                        </h1>
                        <div class="flex items-center">
                            <x-ts-button x-on:click="show_type = !show_type" color="white" sm flat>
                                <x-ts-icon name="chevron-down" class="w-5 h-5 transition" x-bind:class="show_type ? 'rotate-180'  :  ''" />
                            </x-ts-button>
                        </div>
                    </div>
                    <div x-show="show_type" x-collapse.duration.500ms>
                        @foreach ($types->groupBy('year') as $year => $years)
                            <div x-data="{ show_year: true }">
                                <div
                                    class="px-2 flex justify-between text-xl font-semibold bg-primary-400 border-b border-primary-500">
                                    <h2>
                                        {{ $year }}
                                        <small>({{ $years->count() }}
                                            {{ $years->count() > 1 ? 'resultados' : 'resultado' }})</small>
                                    </h2>
                                    <div class="flex items-center">
                                        <x-ts-button x-on:click="show_year = !show_year" color="white" sm flat>
                                            <x-ts-icon name="chevron-down" class="w-4 h-4 transition" x-bind:class="show_year ? 'rotate-180'  :  ''" />
                                        </x-ts-button>
                                    </div>
                                </div>
                                <div class="divide-y" x-show="show_year" x-collapse.duration.500ms>
                                    @foreach ($years as $production)
                                        <div class="py-4 px-3 space-y-2">
                                            <h3 class="text-lg font-semibold">{{ $production->title }}</h3>
                                            <p>
                                                {{ $production->abstract->content ?? '' }}
                                            </p>
                                            @if ($production->generalGoal)
                                                <div x-show="showGoal" x-trasition>
                                                    <p class="text-gray-700">
                                                        <span class="font-semibold">Objetivo geral:</span>
                                                        {{ $production->generalGoal->content }}
                                                    </p>
                                                </div>
                                            @endif
                                            <p x-show="showKeywords" x-trasition class="text-gray-700">
                                                <span>Palavras-chave:</span>
                                                @foreach ($production->keywords as $keyword)
                                                    {{ $keyword->value }}@if (!$loop->last); @endif
                                                @endforeach
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
            @endforeach
        </x-ts-card>
    </div>
</div>
