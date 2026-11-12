<?php

namespace App\Livewire\Reference;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class ReferenceIndex extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $q = '';

    public $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        $headers = [
            ['index' => 'short_author', 'label' => 'Autor(es)', 'sortable' => false],
            ['index' => 'year', 'label' => 'Ano', 'sortable' => false],
            ['index' => 'title', 'label' => 'Título', 'sortable' => false],
            ['index' => 'type', 'label' => 'Tipo', 'sortable' => false],
            ['index' => 'productions_count', 'label' => 'Produções', 'sortable' => false, 'align' => 'center'],
            ['index' => 'citations_count', 'label' => 'Citações', 'sortable' => false, 'align' => 'center'],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        $references = $this->project->references()
            ->whereAny(['title', 'short_author'], 'like', "%$this->q%")
            ->withCount(['productions', 'citations'])
            ->orderBy('short_author')
            ->orderBy('year')
            ->paginate();

        return view('livewire.reference.reference-index', compact('references', 'headers'))
            ->title('Referências');
    }
}
