<?php

namespace App\Livewire\Dashboard;

use App\Models\Project;
use App\Models\Scopes\ProjectScope;
use Livewire\Component;

class DashboardIndex extends Component
{
    public $projects = [];

    public function mount()
    {
        $this->projects = Project::withCount(['productions' => function ($q) {
            $q->withoutGlobalScope(ProjectScope::class);
        }])->get();
    }

    public function render()
    {
        $projectHeaders = [['index' => 'theme', 'label' => 'Projeto', 'sortable' => false]];

        return view('livewire.dashboard.dashboard-index', compact('projectHeaders'));
    }
}
