<section>
    <x-page-header title="Estudantes">
        <x-ts-button :href="route('students.create')" text="Novo" />
    </x-page-header>
    <x-ts-table :$headers :rows="$students">
        @interact('column_name', $row)
            <a href="{{ route('students.show', $row) }}" wire:navigate>{{ $row->name }}</a>
        @endinteract
        @interact('column_user', $row)
            {{ $row->user?->name }}
        @endinteract
        @interact('column_projects_count', $row)
            {{ $row->projects_count }}
        @endinteract
    </x-ts-table>
</section>
