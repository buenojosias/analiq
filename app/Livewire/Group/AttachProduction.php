<?php

namespace App\Livewire\Group;

use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class AttachProduction extends Component
{
    use Interactions;

    public $slide = false;

    public $project;

    public $group;

    public $groupProductions = [];

    public $productions;

    public $selectedProductions = [];

    public $note;

    public function mount($project, $group, $groupProductions)
    {
        $this->project = $project;
        $this->group = $group;
        $this->groupProductions = $groupProductions;
    }

    public function getProductions()
    {
        $this->productions = $this->project->productions()
            ->select(['id', 'title', 'type', 'year'])
            ->whereNotIn('id', $this->groupProductions->pluck('id'))
            // ->with('authors')
            ->get()
            ->sortBy('title');
    }

    #[On('open-slide')]
    public function openSlide()
    {
        $this->slide = true;
    }

    public function render()
    {
        $this->getProductions();

        return view('livewire.group.attach-production');
    }

    public function attachProductions()
    {
        $this->validate([
            'selectedProductions' => 'required|array',
            'note' => 'nullable|string|max:255',
        ]);
        foreach ($this->selectedProductions as $production) {
            $this->group->productions()->syncWithoutDetaching([$production => ['note' => $this->note]]);
        }
        $this->dispatch('productions-attached');
        $this->slide = false;
        $this->reset(['selectedProductions', 'note']);
        $this->toast()->success('Produções adicionadas ao grupo com sucesso.')->send();
    }
}
