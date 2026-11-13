<x-slot:title>Objetivos</x-slot:title>
<div class="flex gap-6" x-data="{ showWithoutGoal: false }">
    <div class="w-60">
        <x-ts-card header="Opções" class="space-y-2">
            <x-ts-toggle x-model="showWithoutGoal" label="Exibir sem objetivo" />
        </x-ts-card>
    </div>

    <div class="flex-1 space-y-4 mb-6">
        <x-ts-card class="space-y-2">
            @foreach ($productionsWithGoal as $production)
                <div class="p-2 border rounded shadow-sm" x-data="{ showSpecifics: false }">
                    <p class="text-sm font-semibold text-gray-800">{{ $production->title }} ({{ $production->type }} -
                        {{ $production->year }})</p>
                    <p class="my-2 cursor-pointer" x-on:click="showSpecifics = !showSpecifics">
                        {{ $production->goals->where('level', 'Geral')->first()->content }}</p>

                    <div class="px-1" x-show="showSpecifics" x-collapse>
                        <h6 class="py-2 font-semibold">Objetivos específicos</h6>
                        <ul class="ml-1 pl-4 space-y-1 list-disc">
                            @forelse ($production->goals->where('level', 'Específico') as $goal)
                                <li class="">{{ $goal->content }}</li>
                            @empty
                                Nada adicionado
                            @endforelse
                        </ul>
                    </div>

                </div>
            @endforeach
        </x-ts-card>

        <x-ts-card header="Produções sem objetivo" class="space-y-2" x-show="showWithoutGoal">
            @foreach ($productionsWithoutGoal as $production)
                <div class="py-1 px-1.5 border rounded">
                    <a
                        href="{{ route('project.bibliometrics.productions.show', [$production->project_id, $production]) }}">
                        <p>{{ $production->title }}</p>
                        <p class="text-sm">{{ $production->type }} - {{ $production->year }}</p>
                    </a>
                </div>
            @endforeach
        </x-ts-card>

    </div>

</div>
