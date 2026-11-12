<div>
    <x-page-header title="Dashboard" subtitle="Research" />

    <div class="sm:grid grid-cols-3 gap-4 space-y-2 sm:space-y-0 mb-6">
        <x-ts-stats title="Projetos" :number="$projects->count()" />
        <x-ts-stats title="Produções" :number="$projects->sum('productions_count')" />
    </div>

    <div class="sm:grid grid-cols-2 gap-4">
        <div class="space-y-4">
            <h2 class="text-lg font-semibold">Meus projetos</h2>
            @forelse ($projects as $project)
                <x-ts-card>
                    <x-ts-link :text="$project->theme" :href="route('project.show', $project)" color="black" />
                    <p class="mt-2 text-sm">{{ $project->productions_count }} produções</p>
                    <x-slot:footer>
                        <div class="space-x-4">
                            <x-ts-link text="Bibliometria" :href="route('project.bibliometrics.show', [$project])" wire:navigate />
                            <x-ts-link text="Produções" :href="route('project.bibliometrics.productions.index', [$project])" wire:navigate />
                        </div>
                    </x-slot>
                </x-ts-card>
            @empty
                Você ainda não possui projetos
            @endforelse
        </div>
    </div>


    <div class="hidden flex gap-6">
        <div class="w-ful sm:w-1/2 space-y-4">
            <x-ts-table :headers="$projectHeaders" :rows="$projects">
                <x-slot:header>Meus projetos</x-slot:header>
                @interact('column_theme', $row)
                    <a href="{{ route('project.show', $row) }}" wire:navigate>{{ $row->theme }}</a>
                @endinteract
            </x-ts-table>
        </div>
        <div class="w-ful sm:w-1/2 space-y-4">
            <x-ts-table :headers="$studentHeaders" :rows="$students">
                <x-slot:header>Estudantes</x-slot:header>
            </x-ts-table>


        </div>
    </div>

</div>
