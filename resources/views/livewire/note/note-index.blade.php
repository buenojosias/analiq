<div>
    <x-ts-toast />
    <x-ts-dialog />
    <x-page-header title="Anotações" :subtitle="$project->theme">
        <x-ts-button text="Adicionar anotação" wire:click="$dispatch('open-modal')" />
    </x-page-header>

    <div class="space-y-3">
        @forelse($notes as $note)
            <x-ts-card class="flex justify-between items-center gap-4">
                <div class="space-y-2">
                    <p>{{ $note->content }}</p>
                    @if ($note->production)
                        <x-ts-link :href="route('project.bibliometrics.productions.show', [$project, $note->production])" :text="$note->production->title" sm />
                    @endif
                </div>
                <div>
                    <x-ts-button icon="trash" color="red" wire:click="delete({{ $note->id }})" flat sm />
                </div>
            </x-ts-card>
        @empty
            <x-ts-card class="text-md text-center">
                Nenhuma anotação adicionada.
            </x-ts-card>
        @endforelse
    </div>
    @livewire('note.note-create', ['project' => $project])
</div>
