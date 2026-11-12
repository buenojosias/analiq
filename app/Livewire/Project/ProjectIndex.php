<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Livewire\Component;

class ProjectIndex extends Component
{
    public $projects = [];

    public function mount()
    {
        $this->projects = Project::query()
            ->with('student')
            ->when(auth()->user()->admin, fn ($q) => $q->with('user'))
            ->withCount('bibliometric')
            ->get();
    }

    public function render()
    {
        $headers = [
            ['index' => 'requested_at', 'label' => 'Data', 'sortable' => false],
            ['index' => 'theme', 'label' => 'Tema', 'sortable' => false],
            ['index' => 'student', 'label' => 'Estudante', 'sortable' => false],
        ];

        if (auth()->user()->admin) {
            $headers[] = ['index' => 'user', 'label' => 'Usuário', 'sortable' => false];
        }

        $headers[] = ['index' => 'action', 'label' => '', 'sortable' => false];

        return view('livewire.project.project-index', compact('headers'))
            ->title('Projetos');
    }
}
