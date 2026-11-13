<?php

namespace App\Livewire\Bibliometric;

use App\Models\CustomField;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class CustomFields extends Component
{
    use Interactions;

    public $bibliometric;

    public $fields = [];

    public $custom_field_id;

    public $custom_field_name;

    public $custom_field_type;

    public $input_option;

    public $custom_fields_types = [
        ['label' => 'Selecione', 'value' => null],
        ['label' => 'Texto', 'value' => 'text'],
        ['label' => 'Número inteiro', 'value' => 'integer'],
        ['label' => 'Decimal', 'value' => 'decimal'],
        ['label' => 'Data', 'value' => 'date'],
        ['label' => 'Verdadeiro/falso', 'value' => 'boolean'],
        ['label' => 'Múltipla escolha', 'value' => 'select'],
    ];

    public function mount($bibliometric)
    {
        $this->bibliometric = $bibliometric;
    }

    public function render()
    {
        $this->fields = $this->bibliometric->customFields()->get();

        return view('livewire.bibliometric.custom-fields');
    }

    protected function attributes()
    {
        return [
            'custom_field_name' => 'Nome',
            'custom_field_type' => 'Tipo',
        ];
    }

    public function editCustomField(int $fieldId): void
    {
        $field = $this->bibliometric->customFields()->findOrFail($fieldId);

        $this->custom_field_id = $field->id;
        $this->custom_field_name = $field->name;
        $this->custom_field_type = $field->type;
    }

    public function resetCustomFieldForm(): void
    {
        $this->reset('custom_field_name', 'custom_field_type', 'custom_field_id');
    }

    public function submit()
    {
        $this->validate([
            'custom_field_name' => 'required|string|max:50|unique:custom_fields,name,'.($this->custom_field_id ?? 'NULL').',id,bibliometric_id,'.($this->bibliometric->id ?? 'NULL'),
            'custom_field_type' => 'required|string|in:text,integer,decimal,date,boolean,select',
        ]);

        if ($this->custom_field_id) {
            $field = $this->bibliometric->customFields()->findOrFail($this->custom_field_id);
            if ($field->type !== 'select' && $this->custom_field_type === 'select') {
                $options = [];
            } elseif ($field->type === 'select' && $this->custom_field_type === 'select') {
                $options = $field->options;
            } else {
                $options = null;
            }

            $field->update([
                'name' => $this->custom_field_name,
                'type' => $this->custom_field_type,
                'options' => $options,
            ]);
            $this->toast()->success('Campo personalizado atualizado com sucesso!')->send();
        } else {
            $this->bibliometric->customFields()->create([
                'name' => $this->custom_field_name,
                'type' => $this->custom_field_type,
                'options' => $this->custom_field_type === 'select' ? [] : null,
            ]);
            $this->toast()->success('Campo personalizado adicionado com sucesso!')->send();
        }

        $this->resetCustomFieldForm();
    }

    public function removeCustomField($id)
    {
        $this->dialog()
            ->question('Tem certeza que deseja remover este campo personalizado?')
            ->confirm(method: 'confirmRemoveCustomField', params: $id)
            ->cancel('Cancelar')
            ->send();
    }

    public function confirmRemoveCustomField($id)
    {
        $this->bibliometric->customFields()->find($id)->delete();
        $this->toast()->success('Campo personalizado removido com sucesso.')->send();
    }

    public function addOption(CustomField $field)
    {
        $this->render();
        $this->validate([
            'input_option' => 'required|string|max:50',
        ]);
        $options = $field['options'];
        $options[] = $this->input_option;
        $field->update(['options' => $options]);
        $this->input_option = null;
    }
}
