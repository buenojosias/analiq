<div>
    <x-ts-modal title="Vincular tag" persistent wire size="sm" center>
        <div class="border-b mb-3 pb-3">
            <x-ts-input type="text" wire:model.live="newTag" placeholder="Criar tag" @keyup.enter="$wire.createTag" />
        </div>
        <div class="space-y-2">
            @foreach ($tags as $tag)
                <x-ts-checkbox name="selectedTags[]" wire:model="selectedTags" :value="$tag->id" :id="$tag->id"
                    :label="$tag->name" />
            @endforeach
        </div>

        <x-slot:footer>
            @if (!$newTag)
                <x-ts-button text="Vincular" wire:click="submit" class="w-full" />
            @else
                <x-ts-button text="Criar e vincular" wire:click="createTag" class="w-full" />
            @endif
        </x-slot:footer>
    </x-ts-modal>
</div>
