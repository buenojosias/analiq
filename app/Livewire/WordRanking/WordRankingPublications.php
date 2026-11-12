<?php

namespace App\Livewire\WordRanking;

use App\Models\Internal;
use App\Models\Publication;
use Livewire\Attributes\On;
use Livewire\Component;

class WordRankingPublications extends Component
{
    public $data;

    public $internalIds = [];

    public $publications = [];

    public $internals;

    public function mount($selectedResult)
    {
        $this->data = $selectedResult;
        dump($this->data);
    }

    #[On('load-publications')]
    public function loadPublications($data)
    {
        // $data['internals'] = array_map(function ($internalId) {
        //     return $internalId + 1;
        // }, $data['internals']);

        dump($data);

        $this->data = $data;

        $this->internalIds = $this->data['internals'];

        $this->internals = Internal::query()
            ->select('id', 'publication_id')
            ->with('publication')
            ->whereIn('id', $this->internalIds)
            ->get()->toArray();

        $this->publications = Publication::query()
            ->whereHas('internals', fn ($query) => $query->whereIn('id', $this->internalIds))
            ->get();
        dump($this->internals);
    }

    public function render()
    {
        $headers = [
            ['index' => 'title', 'label' => 'Título', 'sortable' => false],
            ['index' => 'section', 'label' => 'Seção', 'sortable' => false],
            ['index' => 'count', 'label' => 'Contagem', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        return view('livewire.word-ranking.word-ranking-publications', compact('headers'));
    }
}
