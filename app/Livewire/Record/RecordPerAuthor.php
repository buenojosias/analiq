<?php

namespace App\Livewire\Record;

use App\Models\Production;
use App\Models\Project;
use Livewire\Component;

class RecordPerAuthor extends Component
{
    public $project;

    public $authors;

    public $selectedAuthor;

    public $selectedAuthorName;

    public $authorProductions = [];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function ksort()
    {
        ksort($this->authors);
    }

    public function arsort()
    {
        arsort($this->authors);
    }

    public function selectAuthor($name, $ids)
    {
        $this->selectedAuthor = $ids;
        $this->selectedAuthorName = $name;

        if ($this->selectedAuthor) {
            $this->authorProductions = Production::whereHas('authors', function ($query) {
                $query->whereIn('id', $this->selectedAuthor);
            })->get();
        }
    }

    public function render()
    {
        $this->authors = $this->project->authors->sortBy('fullname')->groupBy(function ($author) {
            return $author->fullname;
        })
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'ids' => $group->pluck('id')->toArray(),
                ];
            });

        $authorRows = $this->authors->map(fn (array $details, string $name): array => [
            'name' => $name,
            'productions_count' => $details['count'],
            'ids' => $details['ids'],
        ])->values();
        $authorHeaders = [
            ['index' => 'name', 'label' => 'Autor', 'sortable' => false],
            ['index' => 'productions_count', 'label' => 'Produções', 'sortable' => false, 'align' => 'center'],
        ];
        $productionHeaders = [
            ['index' => 'title', 'label' => 'Título', 'sortable' => false],
            ['index' => 'type', 'label' => 'Tipo', 'sortable' => false],
            ['index' => 'year', 'label' => 'Ano', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        return view('livewire.record.record-per-author', compact('authorRows', 'authorHeaders', 'productionHeaders'))
            ->title('Relatório por autor');

    }
}
