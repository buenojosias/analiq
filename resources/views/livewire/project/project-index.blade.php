<x-slot:title>Projetos</x-slot:title>
<x-slot:actions>
    <x-ts-button text="Novo projeto" x-on:click="Livewire.dispatch('open-form-modal')" />
</x-slot:actions>

<div>
    <x-ts-table :$headers :rows="$projects">
        @interact('column_theme', $row)
            <a href="{{ route('project.show', $row) }}">{{ $row->theme }}</a>
        @endinteract
        @interact('column_requested_at', $row)
            {{ $row->requested_at->format('d/m/Y') }}
        @endinteract
        @interact('column_user', $row)
            {{ $row->user?->name }}
        @endinteract
        @interact('column_action', $row)
            @if ($row->bibliometric_count > 0)
                <x-ts-link :href="route('project.bibliometrics.show', $row)" icon="book-open" />
            @else
                <x-ts-icon name="book-open" class="h-4" color="gray" />
            @endif
        @endinteract
    </x-ts-table>
    @livewire('project.project-form')
</div>
