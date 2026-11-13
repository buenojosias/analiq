@auth
    <x-app-layout>
        <x-slot:title>Dashboard</x-slot:title>
        @livewire('dashboard.dashboard-index')
    </x-app-layout>
@else
    @include('landing')
@endauth
