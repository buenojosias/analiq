<?php

namespace App\Livewire\Production;

use App\Models\Project;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

class ProductionCitation extends Component
{
    use Interactions;
    use WithPagination;

    public $project;

    public $production;

    #[Url('ref', except: '')]
    public $ref = '';

    public $q;

    #[Validate('required')]
    public $content;

    #[Validate('required|string')]
    public $type;

    #[Validate('required|integer')]
    public $reference_id;

    public $references = [];

    public $searchReference = '';

    public function mount(Project $project, $production)
    {
        $this->project = $project;

        $this->production = $project->productions()->findOrFail($production);
    }

    public function render()
    {
        $headers = [
            ['index' => 'content', 'label' => 'Citação', 'sortable' => false],
            ['index' => 'type', 'label' => 'Tipo', 'sortable' => false],
            ['index' => 'reference', 'label' => 'Referência', 'sortable' => false],
        ];

        $citations = $this->production->citations()
            ->where('content', 'like', '%'.$this->q.'%')
            ->with('reference')
            ->paginate();

        return view('livewire.production.production-citation', compact('citations', 'headers'))
            ->layout('layouts.production')
            ->layoutData([
                'title' => 'Citações da produção',
                'project' => $this->project,
                'production' => $this->production,
            ]);
    }

    public function updated($name)
    {
        if ($name === 'searchReference') {
            $this->loadReferences();
        } elseif ($name === 'content') {
            $this->content = trim(preg_replace('/\n|\s\s+/', ' ', $this->content));
        }
    }

    public function submit()
    {
        $data = $this->validate();

        $this->production->citations()->create($data);

        $this->toast()->success('Citação adicionada com sucesso.')->send();

        $this->reset(['content', 'type', 'reference_id']);
    }

    public function loadReferences()
    {
        $this->references = $this->production->references()
            ->whereAny(['title', 'short_author', 'long_author'], 'like', '%'.$this->searchReference.'%')
            ->orderBy('short_author')
            ->orderBy('year')
            ->limit(10)
            ->get();
    }
}
