<x-slot:title>Bibliometrias</x-slot:title>

<section>
    <div class="header">
        <h1>Pesquisas</h1>
        <x-ts-button text="Nova pesquisa" :href="route('researche.create')" wire:navigate />
    </div>

    <x-ts-table :$headers :rows="$researches">
        @interact('column_requested_at', $row)
            {{ $row->requested_at->format('d/m/Y') }}
        @endinteract
        @interact('column_theme', $row)
            <a href="{{ route('researches.show', $row) }}">{{ $row->theme }}</a>
        @endinteract
        @interact('column_action', $row)
            <x-ts-link :href="route('researches.publications', $row)" wire:navigate :text="$row->publications_count . ' publicações'" />
        @endinteract
    </x-ts-table>
</section>
