<?php

namespace App\Livewire\Bibliometric;

use App\Enums\ProductionTypeEnum;
use App\Models\Project;
use Livewire\Attributes\Validate;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class BibliometricForm extends Component
{
    use Interactions;

    public $project;

    public $bibliometric;

    public $id;

    #[Validate('required:array')]
    public $repositories = [];

    #[Validate('required:array')]
    public $types = [];

    #[Validate('required:array')]
    public $terms = [];

    #[Validate('required:array')]
    public $combinations = [];

    #[Validate]
    public $start_year;

    #[Validate]
    public $end_year;

    #[Validate('required|array')]
    public $languages = ['Portugês'];

    public $avaliable_types = [];

    public int $current_year;

    protected function rules(): array
    {
        $yearRule = 'required|integer|digits:4|min:1900|max:'.now()->year;

        return [
            'start_year' => $yearRule,
            'end_year' => $yearRule,
        ];
    }

    public function mount(Project $project)
    {
        $this->project = $project;

        if ($this->bibliometric = $project->bibliometric) {
            $this->id = $this->bibliometric->id;
            $this->repositories = $this->bibliometric->repositories;
            $this->types = $this->bibliometric->types;
            $this->terms = $this->bibliometric->terms;
            $this->combinations = $this->bibliometric->combinations;
            $this->start_year = $this->bibliometric->start_year;
            $this->end_year = $this->bibliometric->end_year;
            $this->languages = $this->bibliometric->languages;
        } else {
            $this->bibliometric = null;
            $this->requested_at = date('Y-m-d');
        }

        $this->avaliable_types = ProductionTypeEnum::cases();
        $this->current_year = intval(date('Y'));
    }

    public function render()
    {
        return view('livewire.bibliometric.bibliometric-form')
            ->title($this->bibliometric ? 'Editar bibliometria' : 'Adicionar bibliometria');
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->bibliometric) {
            $this->bibliometric->update($data);
            $this->toast()->success('Salvo', 'Bibliometria salva com sucesso.')->send();
        } else {
            $this->bibliometric = $this->project->bibliometric()->create($data);
            session()->flash('status', 'Bibliometria adicionada com sucesso.');
            $this->redirectRoute('project.bibliometrics.show', $this->project, navigate: true);
        }
    }
}
