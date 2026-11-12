<?php

namespace App\Livewire\WordCount;

use App\Models\Internal;
use App\Models\Project;
use App\Models\WordCount;
use Livewire\Attributes\On;
use Livewire\Component;

class WordCountShow extends Component
{
    public $project;

    public $wordcount;

    public $records = [];

    public $word = '';

    public $content = '';

    public function mount(Project $project, $wordcount)
    {
        $this->project = $project;

        $this->wordcount = WordCount::query()
            ->where('project_id', $project->id)
            ->findOrFail($wordcount);

        $this->records = $this->wordcount->records;
        $this->word = $this->wordcount->word;
    }

    public function loadContext($internal_id, $section)
    {
        $content = Internal::query()
            ->select('content')
            ->where('section', $section)
            ->findOrFail($internal_id);

        $this->content = $content->content;
    }

    #[On('close-slide')]
    public function closeSlide()
    {
        $this->content = null;
    }

    public function render()
    {
        $headers = [
            ['index' => 'production', 'label' => 'Produção', 'sortable' => false],
            ['index' => 'type', 'label' => 'Tipo', 'sortable' => false],
            ['index' => 'section', 'label' => 'Seção', 'sortable' => false],
            ['index' => 'count', 'label' => 'Contagem', 'sortable' => false],
            ['index' => 'percentage', 'label' => 'Percentual', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        return view('livewire.word-count.word-count-show', compact('headers'))
            ->title('Contagem de palavras: '.$this->wordcount->word);
    }
}
