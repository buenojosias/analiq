<?php

namespace App\Livewire\Production;

use App\Enums\ReferenceTypeEnum;
use App\Models\Project;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

class ProductionReference extends Component
{
    use Interactions;
    use WithPagination;

    public $project;

    public $production;

    public $avaliable_types = [];

    #[Validate('required|string')]
    public $type;

    #[Validate('required|string')]
    public $short_author;

    #[Validate('nullable|string')]
    public $long_author;

    #[Validate('required|integer|digits:4')]
    public $year;

    #[Validate('required|string')]
    public $title;

    #[Validate('nullable|string')]
    public $full;

    public $suffix;

    public $other_references = [];

    public $search_other;

    public $or;

    public $createModal = false;

    public $syncModal = false;

    public function mount(Project $project, $production)
    {
        $this->project = $project;
        $this->production = $project->productions()->findOrFail($production);

        $this->avaliable_types = ReferenceTypeEnum::cases();
    }

    public function render()
    {
        $headers = [
            ['index' => 'short_author', 'label' => 'Autor(es)', 'sortable' => false],
            ['index' => 'year', 'label' => 'Ano', 'sortable' => false],
            ['index' => 'title', 'label' => 'Título', 'sortable' => false],
            ['index' => 'type', 'label' => 'Tipo', 'sortable' => false],
            ['index' => 'citations_count', 'label' => 'Citações', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        $references = $this->production->references()
            ->withCount(['citations' => function ($q) {
                $q->where('production_id', $this->production->id);
            }])
            ->orderBy('short_author')
            ->orderBy('year')
            ->paginate();

        if ($this->search_other) {
            $this->other_references = $this->project->references()
                ->whereNotIn('id', $references->pluck('id'))
                ->where('title', 'like', '%'.$this->search_other.'%')
                ->get();
        }

        return view('livewire.production.production-reference', compact('references', 'headers'))
            ->layout('layouts.production')
            ->layoutData([
                'title' => 'Referências da produção',
                'project' => $this->project,
                'production' => $this->production,
            ]);
    }

    public function updated($name)
    {
        if ($name === 'full') {
            $this->full = trim(preg_replace('/\n|\s\s+/', ' ', $this->full));
        }
    }

    public function submitReference()
    {
        $data = $this->validate();

        try {
            $reference = $this->project->references()->create($data);
            $this->production->references()->attach($reference, ['suffix' => $this->suffix]);
            $this->toast()->success('Referência adicionada com sucesso.')->send();
            $this->resetFields();
            $this->createModal = false;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function submitSync()
    {
        $this->validate([
            'or' => 'required|integer',
        ]);
        $this->production->references()->attach($this->or, ['suffix' => $this->suffix]);
        $this->toast()->success('Referência vinculada com sucesso.')->send();
        $this->resetFields();
        $this->syncModal = false;
    }

    public function remove($id)
    {
        $this->dialog()
            ->question('Remover esta referência da produção?')
            ->confirm('Confirmar', 'doRemove', $id)
            ->cancel('Cancelar')
            ->send();
    }

    public function doRemove($id)
    {
        $this->production->references()->detach($id);
        $this->toast()->success('Referência removida.')->send();
    }

    public function resetFields()
    {
        $this->reset(['type', 'short_author', 'long_author', 'year', 'title', 'full', 'or', 'suffix', 'search_other', 'other_references']);
    }
}
