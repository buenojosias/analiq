<section>
    <x-ts-toast />
    <x-page-header title="Editar produção" :subtitle="$production->title" />
    <form wire:submit="save">
        <x-ts-card>
            <div class="lg:grid grid-cols-6 gap-4 space-y-4 lg:space-y-0">
                <div class="col-span-4">
                    <x-ts-input label="URL do resultado" wire:model="url" />
                </div>
                <div class="col-span-2">
                    <x-ts-select.styled label="Repositório *" wire:model="repository" :options="$bibliometric->repositories" />
                </div>
                <div class="col-span-3">
                    <x-ts-input label="Título *" wire:model="title">
                        <x-slot:suffix>
                            <x-ts-button wire:click.prevent="titleToLower" icon="language" color="slate" outline />
                        </x-slot:suffix>
                    </x-ts-input>
                </div>
                <div class="col-span-3">
                    <x-ts-input label="Subtítulo" wire:model="subtitle">
                        <x-slot:suffix>
                            <x-ts-button wire:click.prevent="subtitleToLower" icon="language" color="slate" outline />
                        </x-slot:suffix>
                    </x-ts-input>
                </div>
                <div class="">
                    <x-ts-select.styled label="Ano *" wire:model="year" :options="$years" />
                </div>
                <div class="col-span-3">
                    <x-ts-input label="Autor(es) *" wire:model="authors_display"
                        x-on:click="$tsui.open.modal('authors-modal')" readonly />
                </div>
                <div class="col-span-2">
                    <x-ts-select.styled label="Tipo *" wire:model="type" :options="$bibliometric->types" />
                </div>
                <div>
                    <x-ts-select.styled label="Idioma *" wire:model="language" :options="$bibliometric->languages" />
                </div>
                <div class="col-span-3">
                    <x-ts-select.styled label="Descritores *" placeholder="Selecione uma ou mais opções"
                        wire:model="searched_terms" :options="$terms" multiple />
                </div>

                @if (in_array('Tese', $bibliometric->types) ||
                        in_array('Dissertação', $bibliometric->types) ||
                        in_array('Artigo científico', $bibliometric->types))
                    <div class="col-span-3">
                        <x-ts-input label="Instituição (universidade)" wire:model="institution" />
                    </div>
                    <div class="col-span-3">
                        <x-ts-input label="Programa" wire:model="program" />
                    </div>
                @endif

                @if (in_array('Periódico', $bibliometric->types) || in_array('Artigo científico', $bibliometric->types))
                    <div class="col-span-3">
                        <x-ts-input label="Revista" wire:model="periodical" />
                    </div>
                    <div class="col-span-3">
                        <x-ts-input label="DOI" wire:model="doi" />
                    </div>
                @endif

                <div class="col-span-2">
                    <x-ts-input label="Cidade" wire:model="city" />
                </div>
                <div class="">
                    <x-ts-select.styled label="UF" wire:model="state_id" :options="$states"
                        select="label:abbreviation|value:id" searchable />
                </div>
                <div class="">
                    <x-ts-input label="País" wire:model="country" />
                </div>
            </div>
            {{-- <div class="mt-4 lg:grid grid-cols-3 gap-4 space-y-4 lg:space-y-0">
                @foreach ($customFields as $field)
                    <div>
                        @dump($field)
                        <x-ts-input label="{{ $field->name }}" wire:model="customValues.{{ $field->id }}" />
                        {{ $field->productions->first()->value }}
                    </div>
                @endforeach
            </div> --}}

            <x-slot:footer>
                <x-ts-button type="button" :href="route('project.bibliometrics.productions.show', [$project, $production])" wire:navigate text="Ir para produção" outline />
                <x-ts-button type="submit" text="Salvar" />
            </x-slot:footer>
        </x-ts-card>
    </form>
    <x-ts-modal id="authors-modal" size="xl" title="Autores">
        @foreach ($authors as $key => $author)
            <div class="flex border-b justify-between items-center py-1.5 px-1">
                <div>
                    {{ $author['lastname'] }},
                    {{ $author['forename'] }}
                </div>
                <div class="cursor-pointer">
                    <x-ts-icon name="trash" class="w-4" wire:click="removeAuthor({{ $key }})" />
                </div>
            </div>
        @endforeach
        <div class="mt-6 grid grid-cols-6 gap-4">
            <div class="col-span-4">
                <x-ts-input label="Adicionar autor(a)" wire:model="author.forename" placeholder="Exceto último nome" />
            </div>
            <div class="col-span-2 flex items-center">
                <x-ts-input label="Último nome" wire:model="author.lastname" />
                <div class="ml-2 mt-6 cursor-pointer">
                    <x-ts-icon name="check" class="w-4" wire:click="addAuthor" />
                </div>
            </div>
        </div>
    </x-ts-modal>
</section>
