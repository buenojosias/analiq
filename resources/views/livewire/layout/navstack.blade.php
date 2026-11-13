<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="flex items-baseline space-x-4 w-full sm:w-auto">
    <x-nav-link label="Dashboard" :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate />
    <x-nav-link label="Pesquisas" :href="route('researches')" :active="request()->routeIs('researches*')" wire:navigate />

    <!-- Settings Dropdown -->
    <div class="absolute right-2 top-2 flex gap-x-4">
        @include('includes.header-projects')
        <x-dropdown width="48">
            <x-slot name="trigger">
                <button
                    class="p-2 rounded-full border border-transparent text-sm leading-4 font-medium text-white bg-white/25 hover:bg-primary-400/60 hover:text-copy-700 focus:outline-none transition ease-in-out duration-150">
                    <div class="hidden" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name">
                    </div>
                    JB
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-dropdown-link>
                <button wire:click="logout" class="w-full text-start">
                    <x-dropdown-link>
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </button>
            </x-slot>
        </x-dropdown>
    </div>

</nav>
