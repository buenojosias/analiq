<?php

namespace App\Livewire\WordRanking;

use App\Models\Project;
use App\Models\WordRanking;
use Livewire\Component;

class WordRankingShow extends Component
{
    public $project;

    public $wordranking;

    public $totalCount;

    public $records = [];

    public function mount(Project $project, $wordranking)
    {
        $this->project = $project;

        $this->wordranking = WordRanking::query()
            ->where('project_id', $project->id)
            ->findOrFail($wordranking);

        $this->records = $this->wordranking->records;

        $this->totalCount = 0;

        foreach ($this->records as $record) {
            $this->totalCount += $record['count'];
        }
    }

    public function render()
    {
        $headers = [
            ['index' => 'word', 'label' => 'Palavra', 'sortable' => false],
            ['index' => 'count', 'label' => 'Ocorrências', 'sortable' => false],
            ['index' => 'internal_count', 'label' => 'Produções', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        return view('livewire.word-ranking.word-ranking-show', compact('headers'))
            ->title('Ranking de palavras');
    }
}
