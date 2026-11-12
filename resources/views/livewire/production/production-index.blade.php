<section x-data="{
    author: false,
    year: true,
    type: true,
    repository: false,
    descriptors: false,
    periodical: false,
    institution: false,
    program: false,
    city: false,
    state: false,
    url: false,
}">
    <x-ts-toast />
    <x-page-header title="Produções encontradas" :subtitle="$project->theme">
        <div class="flex flex-col gap-1">
            <x-ts-button text="Adicionar produção" :href="route('project.bibliometrics.productions.create', $project)" wire:navigate />
            <x-ts-button text="Grupos" icon="rectangle-group" :href="route('project.bibliometrics.productions.groups.index', $project)" flat />
        </div>
    </x-page-header>
    <x-table screen>
        <div class="table-header flex justify-between items-center">
            <div class="w-1/2">
                <x-ts-input wire:model.live.debounce="q" placehoder="Buscar título ou subtítulo"
                    icon="magnifying-glass" />
            </div>
            <div>
                <span class="mr-2 text-sm font-normal">{{ $productions->count() }} resultados</span>
                <x-ts-button icon="funnel" flat x-on:click="$tsui.open.slide('filters')" />
                <x-ts-button icon="eye" flat x-on:click="$tsui.open.slide('columns')" />
            </div>
        </div>
        <x-slot name="header">
            <th width="10">
                @livewire('components.bulk-dropdown', ['project' => $project])
            </th>
            <th @click="sortByColumn">Título</th>
            <th x-show="author">Autor(es)</th>
            <th x-show="year">Ano</th>
            <th x-show="type">Tipo</th>
            <th x-show="repository">Repositório</th>
            <th x-show="descriptors">Descritores</th>
            <th x-show="periodical">Periódico</th>
            <th x-show="institution">Instituição</th>
            <th x-show="program">Programa</th>
            <th x-show="city">Cidade</th>
            <th x-show="state">UF</th>
            <th x-show="url">URL</th>
            <th width="10"></th>
        </x-slot>
        <x-slot name="body">
            @foreach ($productions as $production)
                <tr @class(['line-through text-gray-600' => $production->trashed()])>
                    <td>
                        @livewire('components.bulk-checkbox', ['production_id' => $production->id], key($production->id))
                    </td>
                    <td class="!text-wrap flex gap-1">
                        @if ($production->highlighted)
                            <x-ts-icon name="star" class="w-4 h-4 text-amber-600" />
                        @endif
                        <a href="{{ route('project.bibliometrics.productions.show', [$project, $production]) }}"
                            wire:navigate>
                            {{ $production->subtitle ? $production->title . ': ' . $production->subtitle : $production->title }}
                        </a>
                    </td>
                    <td x-show="author">
                        <ul>
                            @foreach ($production->authors as $author)
                                <li>
                                    {{ $author->lastname }}, {{ $author->forename }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td x-show="year">{{ $production->year }}</td>
                    <td x-show="type">{{ $production->type }}</td>
                    <td x-show="repository">{{ $production->repository }}</td>
                    <td x-show="descriptors">
                        @foreach ($production->searched_terms as $term)
                            {{ $term }}
                            @if (!$loop->last)
                                AND
                            @endif
                        @endforeach
                    </td>
                    <td x-show="periodical">{{ $production->periodical }}</td>
                    <td x-show="institution">{{ $production->institution }}</td>
                    <td x-show="program">{{ $production->program }}</td>
                    <td x-show="city">{{ $production->city }}</td>
                    <td x-show="state">{{ $production->state->abbreviation ?? '' }}</td>
                    <td x-show="url">
                        @if ($production->url)
                            <a href="{{ $production->url }}" target="_black">{{ $production->url }}</a>
                        @endif
                    </td>
                    <td>
                        <x-ts-button icon="eye"
                            x-on:click="$dispatch('preview-production', { id: {{ $production->id }} })" sm flat />
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-table>
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
            <x-ts-toggle x-model="author" label="Autor" />
            <x-ts-toggle x-model="year" label="Ano" />
            <x-ts-toggle x-model="type" label="Tipo" />
            <x-ts-toggle x-model="repository" label="Repositório" />
            <x-ts-toggle x-model="descriptors" label="Descritores" />
            @if ($has_periodicals)
                <x-ts-toggle x-model="periodical" label="Periódico" />
            @endif
            @if ($has_monographies)
                <x-ts-toggle x-model="institution" label="Instituição" />
                <x-ts-toggle x-model="program" label="Programa" />
                <x-ts-toggle x-model="city" label="Cidade" />
            @endif
            <x-ts-toggle x-model="state" label="Estado" />
            <x-ts-toggle x-model="url" label="URL" />
        </div>
    </x-ts-slide>
    @livewire('production.production-slide', ['project' => $project])
</section>
@push('scripts')
    <script>
        function data() {
            return {
                sortBy: "",
                sortAsc: false,
                sortByColumn($event) {
                    if (this.sortBy === $event.target.innerText) {
                        if (this.sortAsc) {
                            this.sortBy = "";
                            this.sortAsc = false;
                        } else {
                            this.sortAsc = !this.sortAsc;
                        }
                    } else {
                        this.sortBy = $event.target.innerText;
                    }

                    let rows = this.getTableRows()
                        .sort(
                            this.sortCallback(
                                Array.from($event.target.parentNode.children).indexOf(
                                    $event.target
                                )
                            )
                        )
                        .forEach((tr) => {
                            this.$refs.tbody.appendChild(tr);
                        });
                },
                getTableRows() {
                    return Array.from(this.$refs.tbody.querySelectorAll("tr"));
                },
                getCellValue(row, index) {
                    return row.children[index].innerText;
                },
                sortCallback(index) {
                    return (a, b) =>
                        ((row1, row2) => {
                            return row1 !== "" &&
                                row2 !== "" &&
                                !isNaN(row1) &&
                                !isNaN(row2) ?
                                row1 - row2 :
                                row1.toString().localeCompare(row2);
                        })(
                            this.getCellValue(this.sortAsc ? a : b, index),
                            this.getCellValue(this.sortAsc ? b : a, index)
                        );
                }
            };
        }
    </script>
@endpush
