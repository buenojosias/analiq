<section>
    <x-page-header title="Projeto" :subtitle="$project->theme">
        <x-ts-button text="Editar" x-on:click="$wire.dispatch('open-form-modal')" />
    </x-page-header>
    @if (session('status'))
        <x-ts-alert :text="session('status')" color="teal" close />
    @endif
    <div class="lg:grid grid-cols-3 gap-6">
        <div class="mb-6">
            <x-ts-card header="Sobre o projeto">
                <div class="detail">
                    <x-detail label="Tema da pesquisa" :value="$project->theme" />
                    <x-detail label="Data da solicitação" :value="$project->requested_at->format('d/m/Y')" />
                    @if (auth()->user()->admin)
                        <x-detail label="Usuário" :value="$project->user->name" />
                    @endif
                </div>
                <div class="card-footer">
                    {{-- <x-ts-link :href="route('researches.edit', $research)" wire:navigate>Editar</x-ts-link> --}}
                </div>
            </x-ts-card>
        </div>
        <div class="mb-6">
            <x-ts-card header="Bibliometria">
                @if ($project->bibliometric)
                    <div class="detail">
                        <x-detail label="Repositórios" :value="$project->bibliometric->repositories" />
                        <x-detail label="Tipos de publicação" :value="$project->bibliometric->types" />
                        <x-detail label="Palavras pesquisadas" :value="$project->bibliometric->terms" />
                        <x-detail label="Combinações" :value="$project->bibliometric->combinations" />
                        <x-detail label="Intervalo de anos" :value="$project->bibliometric->period" />
                        <x-detail label="Idioma(s)" :value="$project->bibliometric->languages" />
                        <x-detail label="Resultados encontrados" :value="$project->productions_count" />
                    </div>
                    <div class="card-footer">
                        <x-ts-link :href="route('project.bibliometrics.show', $project)" wire:navigate>Acessar</x-ts-link>
                        <x-ts-link :href="route('project.bibliometrics.productions.index', $project)" wire:navigate>Produções</x-ts-link>
                        <x-ts-link :href="route('project.bibliometrics.edit', $project)" wire:navigate>Editar</x-ts-link>
                    </div>
                @else
                    Bibliometria não encontrada
                    <div class="card-footer">
                        <x-ts-link :href="route('project.bibliometrics.create', $project)" wire:navigate>Adicionar bibliometria</x-ts-link>
                    </div>
                @endif
            </x-ts-card>
        </div>

        <div class="mb-6">
            <x-ts-card header="Entrevistas">
                Recurso disponível em breve
            </x-ts-card>
        </div>
    </div>
    @livewire('project.project-form', ['project' => $project])
</section>
