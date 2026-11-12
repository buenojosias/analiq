<?php

namespace App\Livewire\Production;

use App\Enums\ProductionSectionEnum;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class ProductionContent extends Component
{
    use Interactions;

    public $project;

    public $production;

    public $section;

    public $sectionLabel;

    public $content;

    public $editing = false;

    public function mount(Project $project, $production, $section)
    {
        $this->project = $project;

        $this->production = $project->productions()
            ->select(['id', 'title', 'year'])
            ->findOrFail($production);

        $this->section = $section;

        $this->content = $this->production
            ->internals()
            ->where('section', $this->section)
            ->first();

        $this->sectionLabel = ProductionSectionEnum::from($this->section)->label();
    }

    public function render()
    {
        return view('livewire.production.production-content')
            ->layout('layouts.production')
            ->layoutData([
                'title' => $this->sectionLabel,
                'project' => $this->project,
                'production' => $this->production,
            ]);
    }

    #[On('tag-attached')]
    public function tagAttached()
    {
        $this->toast()->success('Tag vinculada com sucesso!')->send();
    }
}
