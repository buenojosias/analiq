<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\Student;
use Illuminate\Validation\Rule;
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

    public $student_id;

    public $theme;

    public $requested_at;

    public $students = [];

    public function mount(?Project $project = null): void
    {
        $ownerId = $project?->user_id ?? auth()->id();
        $this->students = Student::query()
            ->where('user_id', $ownerId)
            ->select(['id', 'name'])
            ->get()
            ->toArray();

        if ($project) {
            $this->selectedProject = Project::query()->findOrFail($project->getKey());
            $this->theme = $project->theme;
            $this->requested_at = $project->requested_at;
            $this->student_id = $project->student_id;
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

        $ownerId = $project?->user_id ?? auth()->id();
        $data = $this->validate([
            'student_id' => ['nullable', 'integer', Rule::exists('students', 'id')->where('user_id', $ownerId)->whereNull('deleted_at')],
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
