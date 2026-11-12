<section>
    <x-ts-toast />
    <x-page-header title="Produções encontradas" :subtitle="$project->theme">
        <div class="flex flex-col gap-1">
            <x-ts-button text="Adicionar produção" :href="route('project.bibliometrics.productions.create', $project)" wire:navigate />
            <x-ts-button text="Grupos" icon="rectangle-group" :href="route('project.bibliometrics.productions.groups.index', $project)" flat />
        </div>
    </x-page-header>
    <div class="screen">
        <x-ts-table :$headers :rows="$productions">
            <x-slot:header>
                <div class="table-header flex justify-between items-center">
                    <div class="w-1/2">
                        <x-ts-input wire:model.live.debounce="q" placeholder="Buscar título" icon="magnifying-glass" />
                    </div>
                    <div>
                        @livewire('components.bulk-dropdown', ['project' => $project])
                        <span class="mr-2 text-sm font-normal">{{ $productions->count() }} resultados</span>
                        <x-ts-button icon="funnel" flat x-on:click="$tsui.open.slide('filters')" />
                        <x-ts-button icon="eye" flat x-on:click="$tsui.open.slide('columns')" />
                    </div>
                </div>
            </x-slot:header>
            @interact('column_select', $row)
                @livewire('components.bulk-checkbox', ['production_id' => $row->id], key($row->id))
            @endinteract
            @interact('column_title', $row)
                <div @class(['line-through text-gray-600' => $row->trashed()])>
                    @if ($row->highlighted)
                        <x-ts-icon name="star" class="w-4 h-4 text-amber-600" />
                    @endif
                    <a href="{{ route('project.bibliometrics.productions.show', [$project, $row]) }}" wire:navigate>
                        {{ $row->title }}
                    </a>
                </div>
            @endinteract
            @interact('column_author', $row)
                {{ $row->authors->map(fn ($author) => $author->lastname.', '.$author->forename)->join('; ') }}
            @endinteract
            @interact('column_type', $row)
                {{ $row->type->value }}
            @endinteract
            @interact('column_descriptors', $row)
                {{ collect($row->searched_terms)->join(' AND ') }}
            @endinteract
            @interact('column_state', $row)
                {{ $row->state?->abbreviation }}
            @endinteract
            @interact('column_url', $row)
                @if ($row->url)
                    <a href="{{ $row->url }}" target="_blank" rel="noopener noreferrer">{{ $row->url }}</a>
                @endif
            @endinteract
            @interact('column_action', $row)
                <x-ts-button icon="eye" x-on:click="$dispatch('preview-production', { id: {{ $row->id }} })" sm flat />
            @endinteract
        </x-ts-table>
    </div>
    <x-ts-slide left title="Filtros" id="filters" size="sm">
        <div class="space-y-3">
            <div class="flex flex-wrap gap-4">
                @for ($i = $bibliometric->start_year; $i <= $bibliometric->end_year; $i++)
                    <x-ts-checkbox name="anos[]" wire:model.live="anos" :value="$i" :label="$i" />
                @endfor
            </div>
            <x-ts-select.styled label="Repositório" wire:model.live="repo" placeholder="Selecione um repositório"
                :options="$bibliometric->repositories" />
            <x-ts-select.styled label="Tipo" wire:model.live="tipo" placeholder="Selecione um tipo"
                :options="$bibliometric->types" />
            <x-ts-select.styled label="Idioma" wire:model.live="idioma" placeholder="Selecione um idioma"
                :options="$bibliometric->languages" />
            @if ($has_monographies)
                <x-ts-select.styled label="Estado" wire:model.live="uf" placeholder="Selecione um estado"
                    :options="$states" />
                <x-ts-select.styled label="Região" wire:model.live="regiao" placeholder="Selecione uma região do país"
                    :options="$regions" />
            @endif
            @if ($has_periodicals)
                <x-ts-select.styled label="Periódico" wire:model.live="periodico" placeholder="Selecione um periódico"
                    :options="$periodicals" />
            @endif
            <x-ts-toggle wire:model.live="highlighted" label="Apenas destacadas" />
            <x-ts-toggle wire:model.live="show_deleted" label="Exibir removidas" />
        </div>
    </x-ts-slide>

    <x-ts-slide left title="Colunas visíveis" id="columns" size="sm">
        <div class="space-y-3">
            <x-ts-toggle wire:model.live="visibleColumns.author" label="Autor" />
            <x-ts-toggle wire:model.live="visibleColumns.year" label="Ano" />
            <x-ts-toggle wire:model.live="visibleColumns.type" label="Tipo" />
            <x-ts-toggle wire:model.live="visibleColumns.repository" label="Repositório" />
            <x-ts-toggle wire:model.live="visibleColumns.descriptors" label="Descritores" />
            @if ($has_periodicals)
                <x-ts-toggle wire:model.live="visibleColumns.periodical" label="Periódico" />
            @endif
            @if ($has_monographies)
                <x-ts-toggle wire:model.live="visibleColumns.institution" label="Instituição" />
                <x-ts-toggle wire:model.live="visibleColumns.program" label="Programa" />
                <x-ts-toggle wire:model.live="visibleColumns.city" label="Cidade" />
            @endif
            <x-ts-toggle wire:model.live="visibleColumns.state" label="Estado" />
            <x-ts-toggle wire:model.live="visibleColumns.url" label="URL" />
        </div>
    </x-ts-slide>
    @livewire('production.production-slide', ['project' => $project])
</section>
