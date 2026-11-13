<x-slot:title>Ranking de palavras</x-slot:title>

<section>
    <div class="lg:grid grid-cols-5 gap-6">
        <div class="mb-6 col-span-2">
            <x-ts-card>
                <div class="detail">
                    @if ($wordranking->title)
                        <x-detail label="Título do relatório" :value="$wordranking->title" />
                    @endif
                    <x-detail label="Tipos de produção" :value="$wordranking->filters['production_types']" />
                    <x-detail label="Seções pesquisadas" :value="$wordranking->filters['internal_sections']" />
                    <x-detail label="Largura mínima das palavras" :value="$wordranking->filters['min_lenght']" />
                    <x-detail label="Limite de palavras" :value="$wordranking->words_limit" />
                    <x-detail label="Contagem total" :value="$totalCount" />
                    <x-detail label="Data do relatório" :value="$wordranking->created_at->format('d/m/Y')" />
                </div>
            </x-ts-card>
        </div>

        <div class="col-span-3">
            <x-ts-table :$headers :rows="$wordranking->records">
                <x-slot:header>Relatório</x-slot:header>
                @interact('column_action', $row)
                    <x-ts-dropdown text="Ações" position="bottom-end">
                        <x-ts-dropdown.items text="Ver contexto" />
                    </x-ts-dropdown>
                @endinteract
            </x-ts-table>
        </div>
    </div>
</section>
