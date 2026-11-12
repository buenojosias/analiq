<section x-data="{ showtable: false, showdetails: false }">
    <div class="header">
        <div>
            <h1>Contagem de palavras</h1>
            <h2>{{ $word }}</h2>
        </div>
    </div>
    <div class="lg:grid grid-cols-2 gap-6">
        <div class="mb-6">
            <x-ts-card>
                <div class="detail">
                    <x-detail label="Palavra ou expressão pesquisada" :value="$wordcount->word" />
                    <x-detail label="Tipos de publicação" :value="$wordcount->production_types" />
                    <x-detail label="Seções pesquisadas" :value="$wordcount->sections" />
                    <x-detail label="Produções encontrados" :value="count($wordcount->records)" />
                    <x-detail label="Data do relatório" :value="$wordcount->created_at->format('d/m/Y')" />
                </div>
                <div class="card-footer">
                    <div class="space-x-4 flex-1 flex">
                        <x-ts-toggle x-model="showtable" label="Mostrar tabela" />
                        <x-ts-toggle x-model="showdetails" label="Mostrar detalhes" />
                    </div>
                    <div>
                        <x-ts-button text="Excluir relatório" color="red" />
                    </div>
                </div>
            </x-ts-card>
        </div>

        <div x-show="showdetails" x-transition class="mb-6 space-y-2">
            <h2 class="mb-4 font-semibold">Produções encontradas</h2>
            @foreach ($wordcount->records as $record)
                <x-ts-card x-data="{ details: false }">
                    <div x-on:click="details = !details" class="card-header justify-between"
                        :class="details ? 'pb-4 mb-2 border-b' : ''">
                        <div class="cursor-pointer">
                            {{ $record['production']['year'] }}.
                            {{ $record['production']['title'] }}
                        </div>
                        <div>
                            <x-ts-button color="gray" sm flat>
                                <x-ts-icon name="chevron-down" class="w-5 transition"
                                    x-bind:class="details ? 'rotate-180' : ''" />
                            </x-ts-button>
                        </div>
                    </div>
                    <div class="detail" x-show="details" x-collapse>
                        <x-detail label="Título da produção" :value="$record['production']['title']" />
                        <div>
                            <dl class="w-full">
                                <dt>Autor(es)</dt>
                                <dd>
                                    <ul>
                                        @foreach ($record['production']['authors'] as $author)
                                            <li>
                                                {{ $author['lastname'] }},
                                                {{ $author['forename'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </dd>
                            </dl>
                        </div>
                        <x-detail label="Tipo de produção" :value="$record['production']['type']" />
                        <x-detail label="Seção encontrada" :value="$record['section']" />
                        <x-detail label="Contagem de palavas" :value="$record['count'] .
                            ' / ' .
                            $record['total_words'] .
                            ' (' .
                            number_format($record['percentage'], 2, ',', '.') .
                            '%)'" />
                    </div>
                    <div class="card-footer" x-show="details" x-transition>
                        <div>
                            <x-ts-button
                                wire:click="loadContext({{ $record['internal_id'] }}, '{{ $record['section'] }}')"
                                text="Ver contexto" outline />
                            {{-- <x-ts-button :href="route('researches.productions.content', [
                                $research,
                                $record['production']['id'],
                            ])" wire:navigate text="Ler conteúdo" outline /> --}}
                            <x-ts-button :href="route('project.bibliometrics.productions.show', [
                                $project,
                                $record['production']['id'],
                            ])" wire:navigate text="Abrir produção" outline />
                        </div>
                    </div>
                </x-ts-card>
            @endforeach
        </div>
    </div>

    <div x-show="showtable" x-transition>
        <x-ts-table :$headers :rows="$records">
            <x-slot:header>Produções encontradas</x-slot:header>
            @interact('column_production', $row)
                <a href="{{ route('project.bibliometrics.productions.show', [$project, $row['production']['id']]) }}">
                    {{ $row['production']['title'] }}
                </a>
            @endinteract
            @interact('column_type', $row)
                {{ $row['production']['type'] }}
            @endinteract
            @interact('column_percentage', $row)
                {{ $row['percentage'] }}%
            @endinteract
            @interact('column_action', $row)
                <x-ts-button icon="eye"
                    x-on:click="$dispatch('preview-production', { id: {{ $row['production']['id'] }} })" sm flat />
            @endinteract
        </x-ts-table>
    </div>
    @if ($content)
        <livewire:word-count.word-count-context :$content :$word />
    @endif
    @livewire('production.production-slide', ['project' => $project])
</section>
