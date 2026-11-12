<?php

namespace App\Livewire\Reference;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class ReferenceCitation extends Component
{
    use WithPagination;

    public $project;

    public $reference;

    public $q;

    public function mount(Project $project, $reference)
    {
        $this->project = $project;

        $this->reference = $project->references()->findOrFail($reference);
    }

    public function render()
    {
        $headers = [
            ['index' => 'type', 'label' => 'Tipo', 'sortable' => false],
            ['index' => 'content', 'label' => 'Citação', 'sortable' => false],
            ['index' => 'production', 'label' => 'Produção', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        $citations = $this->reference->citations()
            ->where('content', 'like', '%'.$this->q.'%')
            ->with('production')
            ->paginate();

        return view('livewire.reference.reference-citation', compact('citations', 'headers'))
            ->title('Citações da referência');
    }
}
