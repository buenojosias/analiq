<section>
    <x-ts-toast />
    <x-page-header :title="$bibliometric ? 'Editar bibliometria' : 'Adicionar bibliometria'" />
    <x-ts-errors />
    <div class="sm:grid grid-cols-3 gap-6">
        <div class="col-span-2">
            <x-ts-card class="space-y-4">
                <form wire:submit="save" id="bibliometric-form">
                    <div class="w-full lg:grid grid-cols-6 gap-4 space-y-4 lg:space-y-0">
                        <div class="col-span-3">
                            <x-ts-tag wire:model="repositories" label="Repositórios *"
                                hint="Separe os itens com vírgula" />
                        </div>
                        <div class="col-span-3">
                            <x-ts-select.styled wire:model="types" label="Tipos de publicação"
                                placeholder="Selecione as opções" :options="$avaliable_types" multiple />
                        </div>
                        <div class="col-span-6">
                            <x-ts-tag wire:model="terms" label="Descritores *" hint="Separe os itens com vírgula" />
                        </div>
                        <div class="col-span-3">
                            <x-ts-tag wire:model="combinations" label="Combinações *" hint="Ex: A+B, A+C, B+C" />
                        </div>
                        <div class="col-span-3">
                            <x-ts-tag wire:model="languages" label="Idiomas *" hint="Separe os itens com vírgula" />
                        </div>
                        <div class="col-span-2">
                            <x-ts-number wire:model.live.blur="start_year" label="Ano inicial *" min="1900" />
                        </div>
                        <div class="col-span-2">
                            <x-ts-number wire:model="end_year" label="Ano final *" />
                        </div>
                    </div>
                    <x-slot:footer>
                        @if ($bibliometric)
                            <x-ts-button type="button" :href="route('project.bibliometrics.show', $bibliometric)" wire:navigate text="Ir para bibliometria"
                                outline />
                        @endif
                        <x-ts-button type="submit" text="Salvar" form="bibliometric-form" />
                    </x-slot:footer>
                </form>
            </x-ts-card>
        </div>
        @if ($bibliometric)
            @livewire('bibliometric.custom-fields', ['bibliometric' => $bibliometric])
        @endif
    </div>
</section>
