<div>
    <x-ts-toast />
    @if (session('status'))
        <x-ts-alert color="emerald" light>
            {{ session('status') }}
            <x-slot:footer>
                <div class="flex justify-end">
                    <x-ts-button text="Adicionar mais uma" color="primary" :href="route('project.bibliometrics.productions.create', $project)" />
                </div>
            </x-slot:footer>
        </x-ts-alert>
    @endif
    @if ($production->trashed())
        <x-ts-alert color="amber" light>
            Esta produção foi descartada
            <x-slot:footer>
                <div class="flex justify-end gap-2">
                    <x-ts-button wire:click="delete" text="Excluir definitivamente" color="amber" flat sm />
                    <x-ts-button wire:click="restore" text="Restaurar" color="amber" outline sm />
                </div>
            </x-slot:footer>
        </x-ts-alert>
    @endif
    <div class="flex-1 flex gap-6">
        <div class="mb-6">
            @if ($production->highlighted)
                <x-ts-banner text="Produção destacada." color="amber" />
            @endif
            <x-ts-card>
                <div class="detail">
                    <x-detail label="Título" :value="$production->title" />
                    <x-detail label="Ano" :value="$production->year" />
                    <div>
                        <dl class="w-full">
                            <dt>Autor(es)</dt>
                            <dd>
                                <ul>
                                    @foreach ($production->authors as $author)
                                        <li>
                                            {{ $author->lastname }},
                                            {{ $author->forename }}
                                        </li>
                                    @endforeach
                                </ul>
                            </dd>
                        </dl>
                    </div>
                    <x-detail label="Tipo" :value="$production->type" />
                    <x-detail label="Repositório" :value="$production->repository" />
                    <div>
                        <dl class="w-full">
                            <dt>Link do resultado</dt>
                            <dd class="w-full">
                                <span class="break-words">{{ $production->url }}</span>
                                @if ($production->url)
                                    <x-ts-link :href="$production->url" icon="arrow-top-right-on-square" blank />
                                @else
                                    Nenhum link adicionado
                                @endif
                            </dd>
                        </dl>
                    </div>
                    <x-detail label="Idioma" :value="$production->language" />
                    <div>
                        <dl>
                            <dt>Descritores</dt>
                            <dd>
                                @foreach ($production->searched_terms as $descriptor)
                                    {{ $descriptor }}
                                    @if (!$loop->last)
                                        AND
                                    @endif
                                @endforeach
                            </dd>
                        </dl>
                    </div>
                    @if ($production->institution)
                        <x-detail label="Instituição" :value="$production->institution" />
                    @endif
                    @if ($production->program)
                        <x-detail label="Progama" :value="$production->program" />
                    @endif
                    @if ($production->city)
                        <x-detail label="Cidade" :value="$production->city . ' (' . @$production->state->abbreviation . ')'" />
                    @endif
                    @if ($production->type->value === 'Periódico')
                        <x-detail label="Periódico" :value="$production->periodical" />
                        <x-detail label="DOI" :value="$production->doi" />
                    @endif
                    @foreach ($production->customFields as $field)
                        <x-detail label="{{ $field->name }}" :value="$field->pivot->value" />
                    @endforeach
                </div>
                <x-slot:footer between>
                    <x-ts-link :href="route('project.bibliometrics.productions.edit', [$project, $production])" wire:navigate text="Editar" />
                    <x-ts-link :href="route('project.bibliometrics.productions.index', $project)" wire:navigate text="Voltar para resultados" />
                    @if (!$production->highlighted)
                        <x-ts-button text="Destacar" wire:click="highlight" outline />
                    @else
                        <x-ts-button text="Remover destaque" wire:click="unhighlight" outline />
                    @endif
                </x-slot:footer>
            </x-ts-card>
        </div>
        <div class="mb-6 space-y-6">
            <x-ts-card header="Anotações" class="space-y-4">
                @forelse ($production->notes as $note)
                    <div class="flex justify-between items-center gap-4 text-sm">
                        <p>{{ $note->content }}</p>
                        <div>
                            <x-ts-button icon="trash" color="red" wire:click="deleteNote({{ $note->id }})" flat
                                sm />
                        </div>
                    </div>
                @empty
                    <p>Nenhuma anotação adicionada</p>
                @endforelse
                <x-slot:footer>
                    <x-ts-button text="Adicionar anotação"
                        wire:click="$dispatch('open-modal', { production: {{ $production }} })" />
                </x-slot:footer>
            </x-ts-card>

            <x-ts-card header="Palavras-chave">
                <ul class="space-y-1">
                    @forelse ($production->keywords as $keyword)
                        <li>{{ $keyword->value }}</li>
                    @empty
                        <p>Nenhuma palavra-chave adicionada</p>
                    @endforelse
                </ul>
            </x-ts-card>

            <x-ts-card header="Tags">
                <div class="detail">
                    <ul class="space-y-1">
                        @forelse ($production->tags as $tag)
                            <li>{{ $tag->name }}</li>
                        @empty
                            <p>Nenhuma tag adicionada</p>
                        @endforelse
                    </ul>
                </div>
                <x-slot:footer>
                    <x-ts-link text="Gerenciar" :href="route('project.bibliometrics.productions.tags', [$project, $production])" wire:navigate />
                </x-slot:footer>
            </x-ts-card>

            <x-ts-card header="Arquivo PDF">
                <div class="flex items-center justify-between">
                    @if ($production->file)
                        <x-ts-link :href="route('project.bibliometrics.productions.files.show', [$project, $production])" :text="$production->file->filename" wire:navigate />
                        <x-ts-button icon="trash" color="red" />
                    @else
                        Nenhum arquivo adicionado
                        <x-ts-button text="Adicionar" />
                    @endif
                </div>
            </x-ts-card>

            <x-ts-card>
                <div class="pb-3 border-b">
                    <x-ts-link href="#" wire:navigate text="Ranking de palavras" />
                </div>
                <div class="pt-3">
                    <x-ts-link href="#" wire:navigate text="Frequência de palavras" />
                </div>
            </x-ts-card>
            @if (!$production->trashed())
                <div class="w-full">
                    <x-ts-button wire:click="remove" text="Descartar" color="white" class="w-full" />
                </div>
            @endif
        </div>
    </div>
    @livewire('note.note-create', ['project' => $production->project])
</div>
