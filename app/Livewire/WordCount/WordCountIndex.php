<?php

namespace App\Livewire\WordCount;

use App\Models\Project;
use Livewire\Component;

class WordCountIndex extends Component
{
    public $project;

    public $wordcounts = [];

    public function mount(Project $project)
    {
        $this->project = $project;

        $wordcounts = $project->wordCounts;

        foreach ($wordcounts as $wc) {
            $wc->productions_count = count($wc->records);
            $wc->total_count = 0;
            foreach ($wc->records as $record) {
                $wc->total_count += $record['count'];
            }
        }

        $this->wordcounts = $wordcounts;
    }

    public function render()
    {
        $headers = [
            ['index' => 'word', 'label' => 'Palavra consultada', 'sortable' => false],
            ['index' => 'sections', 'label' => 'Seções', 'sortable' => false],
            ['index' => 'productions_count', 'label' => 'Resultados', 'sortable' => false],
            ['index' => 'total_count', 'label' => 'Contagem', 'sortable' => false],
        ];

        return view('livewire.word-count.word-count-index', compact('headers'))
            ->title('Contagem de palavras');
    }
}
