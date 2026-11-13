<x-slot:title>Arquivos da bibliometria</x-slot:title>

<section>
        <div class="lg:grid grid-cols-4 gap-6">
        <div class="col-span-3 flex flex-wrap -mx-4">
            @forelse ($productions as $production)
                <div class="w-full sm:w-1/2 lg:w-1/2 p-2">
                    <x-ts-card class="h-full flex gap-4 items-center">
                        <div>
                            <a
                                href="{{ route('project.bibliometrics.productions.files.show', [$production->project_id, $production->id]) }}">
                                <x-ts-icon name="document-text" class="h-8" outline />
                            </a>
                        </div>
                        <div class="flex-1 space-y-2 overflow-hidden">
                            <div class="truncate max-w-full">
                                <a
                                    href="{{ route('project.bibliometrics.productions.files.show', [$production->project_id, $production->id]) }}">
                                    {{ $production->year }} - {{ $production->title }}
                                </a>
                            </div>
                            <div class="text-sm truncate max-w-full">
                                <a
                                    href="{{ route('project.bibliometrics.productions.files.show', [$production->project_id, $production->id]) }}">
                                    {{ $production->file->path }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <x-ts-dropdown icon="chevron-down" position="bottom-end">
                                <a href="{{ route('files', $production->file->path) }}" target="_blank">
                                    <x-ts-dropdown.items text="Abrir arquivo" />
                                </a>
                                {{-- <a href="{{ route('researches.publications.abstract', [$research, $production]) }}">
                                <x-ts-dropdown.items text="Extrair resumo" />
                            </a> --}}
                                {{-- <a href="{{ route('researches.publications.body', [$research, $production]) }}">
                                <x-ts-dropdown.items text="Extrair conteúdo" />
                            </a> --}}
                                <x-ts-dropdown.items class="items" text="Excluir" separator />
                            </x-ts-dropdown>
                        </div>
                        <x-slot:footer>
                            <div class="flex space-x-4 justify-between">
                                <div class="text-xs">
                                    {{ $production->file->pages }} páginas
                                </div>
                                <div class="text-xs">
                                    {{ number_format($production->file->size, 2, ',', '.') }} MB
                                </div>
                            </div>
                        </x-slot:footer>
                    </x-ts-card>
                </div>
            @empty
                Nenhum arquivo adicionado
            @endforelse
        </div>
        <x-ts-card header="Produções sem arquivo">
            @foreach ($noFileProductions as $production)
                <div class="py-1.5 divide-y">
                    <a href="{{ route('project.bibliometrics.productions.show', [$project, $production]) }}" class="flex items-center gap-2 text-sm">
                        <x-ts-icon name="document-text" class="h-6 w-6" outline />
                        {{ $production->year }} - {{ $production->title }}
                    </a>
                </div>
            @endforeach
        </x-ts-card>
    </div>
</section>
