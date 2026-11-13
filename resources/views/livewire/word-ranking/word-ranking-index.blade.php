<x-slot:title>Ranking de palavras</x-slot:title>
<x-slot:actions>
    <x-ts-button :href="route('project.bibliometrics.wordrankings.create', $project)" wire:navigate text="Nova análise" />
</x-slot:actions>

<section>
    <x-ts-table :$headers :rows="$wordrankings">
        @interact('column_title', $row)
            <a href="{{ route('project.bibliometrics.wordrankings.show', [$project, $row]) }}" wire:navigate>
                {{ $row->title ?? $row->created_at->format('d/m/Y') }}
            </a>
        @endinteract
        @interact('column_sections', $row)
            {{ collect($row->filters['internal_sections'])->join(' / ') }}
        @endinteract
        @interact('column_types', $row)
            {{ collect($row->filters['production_types'])->join(' / ') }}
        @endinteract
    </x-ts-table>
</section>
