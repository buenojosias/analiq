<?php

namespace App\Livewire\Production;

use App\Models\Keyword;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class ProductionKeywords extends Component
{
    use Interactions;

    public $project;

    public $production;

    public $keywords = [];

    public function mount(Project $project, $production)
    {
        $this->project = $project;
        $this->production = $project
            ->productions()
            ->findOrFail($production);
    }

    public function render()
    {
        $headers = [
            ['index' => 'value', 'label' => 'Palavra-chave', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        $this->keywords = $this->production->keywords;

        return view('livewire.production.production-keywords', compact('headers'))
            ->layout('layouts.production')
            ->layoutData([
                'title' => 'Palavras-chave da produção',
                'project' => $this->project,
                'production' => $this->production,
            ]);
    }

    #[On('keyword-added')]
    public function reloadKeyWords()
    {
        $this->keywords = $this->production->keywords;
        $this->toast()->success('Palavra-chave adicionada.')->send();
    }

    public function deleteKeyword(Keyword $keyword)
    {
        if ($keyword->delete());
        $this->toast()->success('Palavra-chave removida.')->send();
    }
}
