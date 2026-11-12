<div>
    <x-ts-modal title="Criar grupo de produções" wire size="md">
        <form wire:submit="createGroup" id="group-form" class="space-y-4">
            <x-ts-input label="Nome do grupo *" wire:model="name" required />
            <x-ts-input label="Descrição" wire:model="description" />
        </form>
        <x-slot:footer>
            <x-ts-button type="submit" text="Criar" form="group-form" loading="createGroup" />
        </x-slot>
    </x-ts-modal>
</div>
