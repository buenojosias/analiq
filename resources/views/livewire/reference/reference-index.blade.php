<div>
    <x-page-header title="Referências">
        <div class="flex flex-col gap-1">
            <x-ts-button text="Referências do projeto" :href="route('project.bibliometrics.references.project', $project)" flat />
        </div>
    </x-page-header>
    <x-table>
        <div class="table-header flex justify-between items-center">
            <div class="w-1/2">
                <x-ts-input wire:model.live.debounce="q" placehoder="Buscar" icon="magnifying-glass" />
            </div>
            <div>
                <x-ts-button icon="funnel" flat x-on:click="$tsui.open.slide('filters')" />
            </div>
        </div>
        <x-slot:header>
            <th>Autor(es)</th>
            <th>Ano</th>
            <th width="50%">Título</th>
            <th>Tipo</th>
            <th width="1">Produções</th>
            <th width="1">Citações</th>
            <th width="1"></th>
        </x-slot>
        <x-slot:body>
            @foreach ($references as $reference)
                <tr>
                    <td>{{ $reference->short_author }}</td>
                    <td>{{ $reference->year }}</td>
                    <td>
                        <a href="{{ route('project.bibliometrics.references.show', [$project, $reference]) }}">{{ $reference->title }}</a>
                    </td>
                    <td>{{ $reference->type }}</td>
                    <td class="text-center">{{ $reference->productions_count }}</td>
                    <td class="text-center">{{ $reference->citations_count }}</td>
                    <td>
                        <x-ts-link href="{{ route('project.bibliometrics.references.citations', [$project, $reference]) }}" text="Ver citações" />
                    </td>
                </tr>
            @endforeach
        </x-slot>
        <x-slot:pagination>
            {{ $references->links() }}
        </x-slot>
    </x-table>
</div>
