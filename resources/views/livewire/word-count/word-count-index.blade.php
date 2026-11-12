<section>
    <div class="header">
        <div>
            <h1>Contagem de palavras</h1>
        </div>
        <div>
            <x-ts-button :href="route('project.bibliometrics.wordcounts.create', $project)" wire:navigate text="Nova contagem" />
        </div>
    </div>
    <x-ts-table :$headers :rows="$wordcounts">
        @interact('column_word', $row)
            <a href="{{ route('project.bibliometrics.wordcounts.show', [$project, $row]) }}" wire:navigate>{{ $row->word }}</a>
        @endinteract
        @interact('column_sections', $row)
            {{ collect($row->sections)->map(fn ($section) => $section === 'body' ? 'Conteúdo' : 'Resumo')->join(' / ') }}
        @endinteract
    </x-ts-table>
</section>
