<?php

namespace App\Livewire\File;

use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class FileShow extends Component
{
    use Interactions;

    public $project;

    public $production;

    public $file;

    public $path;

    #[On('file-uploaded')]
    public function fileUploaded($file)
    {
        $this->file = $this->production->file()->find($file['id']);
        $this->path = str_replace('files/', '', $this->file->path);
        $this->toast()->success('Arquivo enviado com sucesso.')->send();
    }

    public function mount(Project $project, $production)
    {
        $this->project = $project;

        $this->production = $project->productions()
            ->select(['id', 'title', 'year'])
            ->with('file')
            ->findOrFail($production);

        $this->file = $this->production->file;

        // $this->path = storage_path($this->file->path);
        if ($this->file) {
            $this->path = str_replace('files/', '', $this->file->path);
        }
    }

    public function render()
    {
        return view('livewire.file.file-show')
            ->layout('layouts.production')
            ->layoutData([
                'title' => 'Arquivo da produção',
                'project' => $this->project,
                'production' => $this->production,
            ]);
    }
}
