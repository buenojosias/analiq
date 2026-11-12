<?php

namespace App\Livewire\Group;

use App\Models\Project;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class GroupIndex extends Component
{
    public $project;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    #[Computed()]
    public function groups()
    {
        return $this->project->groups()->withCount('productions')->get();
    }

    #[On('group-created')]
    public function groupCreated()
    {
        $this->groups();
    }

    public function render()
    {
        $headers = [
            ['index' => 'name', 'label' => 'Nome/descrição', 'sortable' => false],
            ['index' => 'productions_count', 'label' => 'Produções', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        return view('livewire.group.group-index', compact('headers'))
            ->title('Grupos de produções');
    }
}
