<?php

namespace App\Livewire\WordRanking;

use App\Models\Project;
use Livewire\Component;

class WordRankingIndex extends Component
{
    public $project;

    public $wordrankings = [];

    public function mount(Project $project)
    {
        $this->project = $project;

        $wordrankings = $project->wordRankings;

        foreach ($wordrankings as $wr) {
            $wr->productions_count = count($wr->records);
            $wr->total_count = 0;
            foreach ($wr->records as $record) {
                $wr->total_count += $record['count'];
            }
        }

        $this->wordrankings = $wordrankings;
    }

    public function render()
    {
        $headers = [
            ['index' => 'title', 'label' => 'Título ou data', 'sortable' => false],
            ['index' => 'sections', 'label' => 'Seções buscadas', 'sortable' => false],
            ['index' => 'types', 'label' => 'Tipos de produção', 'sortable' => false],
            ['index' => 'productions_count', 'label' => 'Produções encontradas', 'sortable' => false],
            ['index' => 'total_count', 'label' => 'Contagem total', 'sortable' => false],
        ];

        return view('livewire.word-ranking.word-ranking-index', compact('headers'))
            ->title('Ranking de palavras');
    }
}
