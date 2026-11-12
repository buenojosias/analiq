<x-ts-slide wire>
    @if ($production)
        <x-slot:title>
            {{ $production->title }}
        </x-slot:title>
        <div class="detail">
            <x-detail label="Tipo" :value="$production->type" />
            <x-detail label="Ano" :value="$production->year" />
            <div>
                <dl class="w-full">
                    <dt>Autor(es)</dt>
                    <dd>
                        <ul>
                            @foreach ($production->authors as $author)
                                <li>
                                    {{ $author->forename }}
                                    {{ $author->lastname }}
                                </li>
                            @endforeach
                        </ul>
                    </dd>
                </dl>
            </div>
            <div>
                <dl class="w-full">
                    <dt>Palavras-chave</dt>
                    <dd>
                        @foreach ($production->keywords as $keyword)
                            {{ $keyword->value }}@if (!$loop->last); @endif
                        @endforeach
                    </dd>
                </dl>
            </div>
            <div>
                <dl class="w-full">
                    <dt>Resumo</dt>
                    <p>{{ $production->abstract->content }}</p>
                </dl>
            </div>
        </div>
        <x-slot:footer class="flex gap-4">
            <x-ts-link :href="route('project.bibliometrics.productions.show', [$project, $production])" sm>
                Ver produção
            </x-ts-link>
            <x-ts-link :href="route('project.bibliometrics.productions.edit', [$project, $production])" sm>
                Editar produção
            </x-ts-link>
        </x-slot:footer>
    @endif
</x-ts-slide>
