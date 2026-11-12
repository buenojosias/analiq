<div>
    <x-ts-modal title="Adicionar anotação" wire size="sm">
        <form wire:submit.prevent="save" id="note-form">
            <x-ts-textarea label="Conteúdo" wire:model="content" placeholder="Conteúdo da anotação" required />
        </form>
        <x-slot:footer>
            <x-ts-button type="submit" form="note-form" text="Salvar" loading="save" />
        </x-slot>
    </x-ts-modal>
</div>
