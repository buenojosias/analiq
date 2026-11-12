<div class="flex-auto">
    <x-ts-toast />
    <div class="flex-auto grid grid-cols-5 gap-x-6">
        <div class="col-span-2 space-y-4">
            <x-ts-card>
                <ul class="space-y-2 divide-y">
                    @foreach ($tags as $tag)
                        <li class="flex justify-between pt-2">
                            <span class="cursor-pointer" wire:click="selectTag({{ $tag }})">
                                {{ $tag->name }}
                            </span>
                            <x-ts-button wire:click="detachTag({{ $tag }})" icon="trash" color="red" sm
                                flat />
                        </li>
                    @endforeach
                </ul>
            </x-ts-card>
            <x-ts-button text="Vincular tag"
                x-on:click="$dispatch('open-attach-modal', { production: {{ $production }} })" class="w-full" />
        </div>
    </div>
    @livewire('tag.tag-attach', ['production' => $production])
</div>
