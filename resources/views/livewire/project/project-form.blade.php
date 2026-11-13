<div>
    <x-ts-modal title="{{ $selectedProject ? 'Editar projeto' : 'Novo projeto' }}" size="sm" wire>
        <form id="project-form" wire:submit="save" class="space-y-4">
            <div>
                <x-ts-input wire:model="theme" label="Tema do projeto *" />
            </div>
            <div>
                <x-ts-date wire:model="requested_at" label="Data da solicitação *" format="DD/MM/YYYY" :max-date="now()" />
            </div>
        </form>
        <x-slot:footer>
            <x-ts-button type="submit" form="project-form" text="Salvar" />
        </x-slot:footer>
    </x-ts-modal>
</div>
