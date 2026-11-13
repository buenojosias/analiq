<x-app-layout>
    <x-ts-dialog />
    <x-ts-toast />
    @slot('title')
        {{ $title }}
    @endslot
    <section>
        <div class="flex gap-x-6">
            @include('includes.production-nav')
            {{ $slot }}
        </div>
    </section>
</x-app-layout>
