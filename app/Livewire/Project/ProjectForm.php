<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;

class ProjectForm extends Component
{
    #[On('open-form-modal')]
    public function openFormModal()
    {
        $this->modal = true;
    }

    public $modal = false;

    public ?Project $selectedProject = null;

    public $theme;

    public $requested_at;

    public function mount(?Project $project = null): void
    {
        if ($project) {
            $this->selectedProject = Project::query()->findOrFail($project->getKey());
            $this->theme = $project->theme;
            $this->requested_at = $project->requested_at;
        } else {
            $this->selectedProject = null;
            $this->requested_at = date('Y-m-d');
        }
    }

    public function render()
    {
        return view('livewire.project.project-form');
    }

    public function save(): void
    {
        $project = $this->selectedProject
            ? Project::query()->findOrFail($this->selectedProject->getKey())
            : null;

        $data = $this->validate([
            'theme' => ['required', 'string', 'min:5', 'max:255'],
            'requested_at' => ['required', 'date', 'before_or_equal:now'],
        ]);

        if ($project) {
            $project->update($data);
            $this->dispatch('project-updated');
            $this->modal = false;
        } else {
            $project = auth()->user()->projects()->create($data);
            auth()->user()->update([
                'selected_project_id' => $project->id,
                'selected_project_theme' => $project->theme,
                'selected_project_role' => 'owner',
            ]);
            session()->flash('status', 'Projeto criado com sucesso.');
            $this->redirectRoute('project.show', $project, navigate: true);
        }
    }
}
