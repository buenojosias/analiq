@php
    if(@request()->route()->research)
        $researchId = request()->route()->research->pid ?? request()->route()->research;
@endphp

<div class="overflow-y-auto pt-5 px-3 pb-16 h-full bg-primary-600">
    @if (request()->routeIs('researches'))
    <ul class="space-y-2">
        <x-side-link label="Nova pesquisa" :href="route('researche.create')" wire:navigate />
        <x-side-link label="Compartilhadas" href="#" wire:navigate />
    </ul>
    @endif


    @if (request()->routeIs('researches.*') && $researchId)
    <ul class="space-y-2">
        <x-side-link label="Pesquisa" icon="document-magnifying-glass" :href="route('researches.show', $researchId)" :active="request()->routeIs('researches.show')" wire:navigate />
        <x-side-link label="Publicações" icon="book-open" :href="route('researches.publications', $researchId)" :active="request()->routeIs('researches.publications*')" wire:navigate />
        <x-side-link label="Arquivos" icon="document" :href="route('researches.files.index', $researchId)" :active="request()->routeIs('researches.files*')" wire:navigate />
        <x-side-link label="Palavras-chave" icon="hashtag" :href="route('researches.keywords.index', $researchId)" :active="request()->routeIs('researches.keywords*')" wire:navigate />
        <x-side-link label="Ranking de palavras" icon="chart-bar" :href="route('researches.wordrankings.index', $researchId)" :active="request()->routeIs('researches.wordrankings*')" wire:navigate />
        <x-side-link label="Nuvem de palavras" icon="cloud" href="#" wire:navigate />
        <x-side-link label="Contagem de palavras" icon="arrow-trending-up" :href="route('researches.wordcounts.index', $researchId)" :active="request()->routeIs('researches.wordcounts*')" wire:navigate />
    </ul>
    @endif
    {{-- <ul class="space-y-2">
        <x-side-link label="Dashboard" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate />
        <x-side-link label="Pesquisas" :href="route('researches')" :active="request()->routeIs('researches*')" wire:navigate />

        <x-side-link label="Teste">
            <x-side-sublink label="Sublink 1" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate />
            <x-side-sublink label="Sublink 2" />
        </x-side-link>
    </ul>
    <ul class="pt-2 mt-4 space-y-2 border-t border-gray-200 ">
        <x-side-link label="Pesquisas" :href="route('researches')" :active="request()->routeIs('researches.*')" wire:navigate />
    </ul> --}}
</div>
<div
    class="hidden absolute bottom-0 left-0 justify-center p-4 space-x-4 w-full lg:flex z-20">
    <a href="#"
        class="inline-flex justify-center p-2 text-white rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100">
        <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z">
            </path>
        </svg>
    </a>
    <a href="#" data-tooltip-target="tooltip-settings"
        class="inline-flex justify-center p-2 text-white rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100">
        <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                clip-rule="evenodd"></path>
        </svg>
    </a>
    <div id="tooltip-settings" role="tooltip"
        class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip">
        Settings page
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
</div>
