<div>
    <x-ts-toast />
    <x-ts-dialog />
    <x-ts-card header="Campos adicionais" x-data="{ showform: false }" class="space-y-2">
        @forelse ($fields as $field)
            <div class="flex justify-between items-center bg-gray-100 p-1.5 rounded flex-wrap" x-data="{ show_options: false }">
                <div class="flex-1">
                    {{ $field->name }}<span class="text-gray-500 text-sm pl-2">({{ $field->type }})</span>
                </div>
                <div>
                    @if ($field->type == 'select')
                        <x-ts-button icon="equals" color="secondary" flat sm @click="show_options = !show_options" />
                    @endif
                    <x-ts-button icon="pencil" flat sm wire:click="editCustomField({{ $field }})"
                        @click="showform = true" />
                    <x-ts-button icon="trash" color="red" flat sm
                        wire:click="removeCustomField({{ $field->id }})" />
                </div>
                @if ($field->type == 'select')
                    <div class="w-full bg-gray-50 mt-2 p-1 text-sm" x-show="show_options" x-data="{ input_option: false }" x-collapse>
                        <ul class="">
                            @foreach ($field->options as $opt => $option)
                                <li class="border-b border-gray-300 py-1.5">{{ $option }}</li>
                            @endforeach
                        </ul>
                        <div class="text-gray-500 text-center py-1" x-show="!input_option" x-collapse>
                            <x-ts-button text="Adicionar opção" class="w-full" sm flat @click="input_option = !input_option" />
                        </div>
                        <div class="w-full" x-show="input_option" x-collapse>
                            <x-ts-input placeholder="Digite a opção e pressione enter" wire:model="input_option"
                                @keyup.enter="$wire.addOption({{ $field }})" class="w-full" />
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center text-gray-500">Nenhum campo adicional cadastrado</div>
        @endforelse
        <x-slot:footer>
            <div x-data="{ showform: false }" class="w-full">
                <div x-show="!showform" x-collapse>
                    <x-ts-button text="Adicionar" outline icon="plus" @click="showform = true" block sm />
                </div>
                <div class="grid sm:grid-cols-2 gap-2" x-show="showform" x-collapse>
                    <div>
                        <x-ts-input wire:model="custom_field_name" label="Nome" />
                    </div>
                    <x-ts-select.native wire:model="custom_field_type" label="Tipo" :options="$custom_fields_types"
                        select="label:label|value:value" />
                    <div class="col-span-2">
                        <x-ts-button text="Salvar" wire:click="submit" />
                        <x-ts-button text="Cancelar" flat @click="showform = false" />
                    </div>
                </div>
            </div>
        </x-slot>
    </x-ts-card>
</div>
