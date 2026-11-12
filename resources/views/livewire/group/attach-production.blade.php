<x-ts-slide title="Adicionar produção" wire persistent>
    <div class="space-y-2">
        @foreach ($productions as $key => $production)
            <x-ts-checkbox wire:model="selectedProductions" :id="$key" :value="$production->id">
                <x-slot:label start>
                    {{ $production->title }}
                    ({{ $production->type }} - {{ $production->year }})
                </x-slot:label>
            </x-ts-checkbox>
        @endforeach
    </div>

    <x-slot:footer class="flex justify-between gap-4">
        <div class="flex-1">
            <x-ts-input placeholder="Adicionar anotação" wire:model="note" class="w-full" />
        </div>
        <x-ts-button text="Salvar" wire:click="attachProductions" />
    </x-slot>
</x-ts-slide>
