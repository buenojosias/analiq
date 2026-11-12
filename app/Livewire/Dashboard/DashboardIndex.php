<?php

namespace App\Livewire\Dashboard;

use App\Models\Project;
use App\Models\Scopes\ProjectScope;
use App\Models\Student;
use Livewire\Component;

class DashboardIndex extends Component
{
    public $projects = [];

    public $students = [];

    public function mount()
    {
        $this->projects = Project::withCount(['productions' => function ($q) {
            $q->withoutGlobalScope(ProjectScope::class);
        }])->get();
        $this->students = Student::all();
    }

    public function render()
    {
        $projectHeaders = [['index' => 'theme', 'label' => 'Projeto', 'sortable' => false]];
        $studentHeaders = [['index' => 'name', 'label' => 'Estudante', 'sortable' => false]];

        return view('livewire.dashboard.dashboard-index', compact('projectHeaders', 'studentHeaders'));
    }
}
