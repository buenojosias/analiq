<section>
    <x-page-header title="Bibliometria" :subtitle="$project->theme" />
    @if (session('status'))
        <x-ts-alert :text="session('status')" color="teal" close />
    @endif
    <div class="lg:grid grid-cols-5 gap-6">
        <div class="col-span-2 mb-6">
            <x-ts-card>
                <div class="detail">
                    <x-detail label="Data da solicitação" :value="$project->requested_at->format('d/m/Y')" />
                    <x-detail label="Repositórios" :value="$bibliometric->repositories" />
                    <x-detail label="Tipos de publicação" :value="$bibliometric->types" />
                    <x-detail label="Palavras" :value="$bibliometric->terms" />
                    <x-detail label="Combinações" :value="$bibliometric->combinations" />
                    <x-detail label="Intervalo de anos" :value="$bibliometric->period" />
                    <x-detail label="Idioma(s)" :value="$bibliometric->languages" />
                </div>
                <div class="card-footer">
                    <x-ts-link :href="route('project.bibliometrics.edit', $project)" wire:navigate>Editar</x-ts-link>
                </div>
            </x-ts-card>
        </div>

        <div class="col-span-3 space-y-6">
            <x-ts-card header="Produções encontradas" paddingless>
                <x-ts-table :$headers :rows="$types">
                </x-ts-table>
                <x-slot:footer between>
                    <x-ts-link :href="route('project.bibliometrics.productions.index', $project)" wire:navigate>Ver todas</x-ts-link>
                    <x-ts-link :href="route('project.bibliometrics.productions.create', $project)" wire:navigate text="Adicionar produção" />
                </x-slot:footer>
            </x-ts-card>

            <div class="grid sm:grid-cols-2 gap-4">
                @livewire('bibliometric.custom-fields', ['bibliometric' => $bibliometric])
                <div>
                    <x-ts-card>
                        <div class="pb-3 border-b">
                            <x-ts-link href="#" wire:navigate text="Ranking de palavras" />
                        </div>
                        <div class="pt-3">
                            <x-ts-link href="#" wire:navigate text="Frequência de palavras" />
                        </div>
                    </x-ts-card>
                </div>
            </div>
        </div>
    </div>
</section>
