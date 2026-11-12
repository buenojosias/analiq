<?php

namespace App\Livewire\Record;

use App\Models\Production;
use App\Models\Project;
use Livewire\Component;

class RecordPerDescriptor extends Component
{
    public $project;

    public $selectedWords = [];

    public $bibliometricTypes = [];

    public $bibliometricRepositories = [];

    public $bibliometricTerms = [];

    public $selectedTypes = [];

    public $bibliometricYears = [];

    public $withTrashed = false;

    public $descriptor = null;

    public function mount(Project $project)
    {
        $this->project = $project;

        $bibliometric = $project->bibliometric;

        $this->bibliometricTypes = $bibliometric->types;
        $this->bibliometricRepositories = $bibliometric->repositories;
        $this->bibliometricTerms = $bibliometric->terms;
        for ($i = intval($bibliometric->start_year); $i <= $bibliometric->end_year; $i++) {
            array_push($this->bibliometricYears, $i);
        }
    }

    public function render()
    {
        // VERSÃO 1
        sort($this->selectedWords);

        $selectedWordsJson = json_encode($this->selectedWords);

        $records = Production::query()
            ->select(['id', 'title', 'subtitle', 'type', 'year', 'searched_terms'])
            ->with('authors')
            ->where('project_id', $this->project->id)
            ->whereRaw('JSON_LENGTH(searched_terms) = ?', [count($this->selectedWords)])
            ->whereRaw('JSON_CONTAINS(searched_terms, ?)', [$selectedWordsJson])
            ->when($this->selectedTypes, function ($query) {
                $query->whereIn('type', $this->selectedTypes);
            })
            ->when($this->withTrashed, function ($query) {
                $query->withTrashed();
            })
            ->get();
        // ->groupBy('type');
        // FIM VERSÃO 1

        // VERSÃO 2
        $productions = collect($this->project->productions);

        $groupedByWords = $productions->groupBy(function ($production) {
            $words = $production->searched_terms;
            sort($words);

            return json_encode($words);
        });

        $tableByWord = [];
        $tableByType = [];
        $tableByRepository = [];
        $tableByYear = [];

        $typeTotals = [];
        $repositoryTotals = [];
        $yearTotals = [];

        $types = $this->bibliometricTypes;
        $repositories = $this->bibliometricRepositories;
        $years = $this->bibliometricYears;

        foreach ($groupedByWords as $words => $group) {
            $wordsArray = json_decode($words, true);
            $wordsKey = implode(' AND ', $wordsArray);

            $tableByWord[$wordsKey] = [
                'total' => $group->count(),
            ];
        }

        foreach ($types as $type) {
            $typeTotals[$type] = 0;
        }
        $typeTotals['total'] = 0;

        foreach ($repositories as $repository) {
            $repositoryTotals[$repository] = 0;
        }
        $repositoryTotals['total'] = 0;

        foreach ($years as $year) {
            $yearTotals[$year] = 0;
        }
        $yearTotals['total'] = 0;

        foreach ($groupedByWords as $words => $group) {
            $wordsArray = json_decode($words, true);
            $wordsKey = implode(' AND ', $wordsArray);

            $wordTotal = 0;
            $typeTotal = 0;
            $repositoryTotal = 0;
            $yearTotal = 0;

            foreach ($types as $type) {
                $count = $group->filter(function ($production) use ($type) {
                    return $production->type->value === $type;
                })->count();

                $tableByType[$wordsKey][$type] = $count;
                $typeTotals[$type] += $count;
                $typeTotal += $count;
            }

            foreach ($repositories as $repository) {
                $count = $group->filter(function ($production) use ($repository) {
                    return $production->repository === $repository;
                })->count();

                $tableByRepository[$wordsKey][$repository] = $count;
                $repositoryTotals[$repository] += $count;
                $repositoryTotal += $count;
            }

            foreach ($years as $year) {
                $count = $group->filter(function ($production) use ($year) {
                    return $production->year == $year;
                })->count();

                $tableByYear[$wordsKey][$year] = $count;
                $yearTotals[$year] += $count;
                $yearTotal += $count;
            }

            $tableByType[$wordsKey]['total'] = $typeTotal;
            $tableByRepository[$wordsKey]['total'] = $repositoryTotal;
            $tableByYear[$wordsKey]['total'] = $yearTotal;

            $typeTotals['total'] += $typeTotal;
            $repositoryTotals['total'] += $repositoryTotal;
            $yearTotals['total'] += $yearTotal;
        }
        // FIM VERSÃO 2

        $wordHeaders = [
            ['index' => 'word', 'label' => 'Combinações', 'sortable' => false],
            ['index' => 'total', 'label' => 'Produções', 'sortable' => false, 'align' => 'center'],
        ];
        $wordRows = collect($tableByWord)->map(fn (array $counts, string $word): array => [
            'word' => $word,
            'total' => $counts['total'],
        ])->values();

        $recordHeaders = [
            ['index' => 'selection', 'label' => '', 'sortable' => false],
            ['index' => 'full_title', 'label' => 'Título', 'sortable' => false],
            ['index' => 'type', 'label' => 'Tipo', 'sortable' => false],
            ['index' => 'authors', 'label' => 'Autor(es)', 'sortable' => false],
            ['index' => 'year', 'label' => 'Ano', 'sortable' => false, 'align' => 'center'],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        $typeMatrix = TableMatrix::build('Descritores', $types, $tableByType, columnTotals: $typeTotals);
        $repositoryMatrix = TableMatrix::build('Descritores', $repositories, $tableByRepository, columnTotals: $repositoryTotals);
        $yearMatrix = TableMatrix::build('Descritores', $years, $tableByYear, columnTotals: $yearTotals);

        return view('livewire.record.record-per-descriptor', compact('records', 'wordHeaders', 'wordRows', 'recordHeaders', 'typeMatrix', 'repositoryMatrix', 'yearMatrix'))
            ->title('Relatório por descritores');
    }

    public function selectWord($word)
    {
        $this->descriptor = $word;
        $words = explode(' AND ', $word);
        $this->selectedWords = array_map('trim', $words);
    }
}
