<?php

namespace App\Livewire\Record;

use App\Models\Production;
use App\Models\Project;
use Livewire\Component;

class RecordPerYear extends Component
{
    public $project;

    public $productions = [];

    public $selectedYear = '';

    public $yearProductions = [];

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->productions = Production::query()
            ->select('id', 'type', 'year', 'state_id')
            ->where('project_id', $project->id)
            ->with('state')
            ->get()
            ->sortBy('year');
    }

    public function render()
    {
        $productionsByYear = $this->productions->groupBy('year');

        $states = $this->productions->whereNotNull('state_id')->map(function ($production) {
            return $production->state->name ?? null;
        })->unique();
        $states = $states->sort()->values()->push($states->pull($states->search(null)));

        $types = $this->productions->map(function ($production) {
            return $production->type->value;
        })->unique();

        $years = $this->productions->pluck('year')->unique()->sort()->values();

        $groupedByYears = $this->productions->groupBy(function ($production) {
            return $production->year;
        });

        $tableByState = [];
        $tableByType = [];

        $stateTotals = [];
        $typeTotals = [];

        foreach ($states as $state) {
            $stateTotals[$state] = 0;
        }
        $stateTotals['total'] = 0;

        foreach ($types as $type) {
            $typeTotals[$type] = 0;
        }
        $typeTotals['total'] = 0;

        foreach ($groupedByYears as $year => $group) {
            $yearTotal = 0;
            $yearTotals[$year] = $group->count();

            foreach ($types as $type) {
                $count = $group->filter(function ($production) use ($type) {
                    return $production->type->value === $type;
                })->count();

                $tableByType[$type][$year] = $count;
                $typeTotals[$type] += $count;
                // $yearTotal += $count;
            }

            foreach ($states as $state) {
                if ($state === null) {
                    $count = $group->whereNull('state_id')->filter(function ($production) {
                        return true;
                    })->count();
                } else {
                    $count = $group->whereNotNull('state_id')->filter(function ($production) use ($state) {
                        return $production->state->name === $state;
                    })->count();
                }

                $tableByState[$state][$year] = $count;
                $stateTotals[$state] += $count;
            }
        }

        $yearRows = $productionsByYear->map(fn ($productions, $year): array => [
            'year' => $year,
            'productions_count' => $productions->count(),
        ])->values();
        $yearHeaders = [
            ['index' => 'year', 'label' => 'Ano', 'sortable' => false],
            ['index' => 'productions_count', 'label' => 'Produções', 'sortable' => false],
        ];
        $productionHeaders = [
            ['index' => 'title', 'label' => 'Título', 'sortable' => false],
            ['index' => 'type', 'label' => 'Tipo', 'sortable' => false],
            ['index' => 'year', 'label' => 'Ano', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];
        $typeMatrix = TableMatrix::build('Tipos de produção', $years, $tableByType, $typeTotals);
        $stateMatrix = TableMatrix::build('Estado', $years, $tableByState, $stateTotals);

        return view('livewire.record.record-per-year', compact(
            'yearRows',
            'yearHeaders',
            'productionHeaders',
            'typeMatrix',
            'stateMatrix',
        ))
            ->title('Relatório por ano');
    }

    public function selectYear($year)
    {
        $this->selectedYear = $year;
        $this->yearProductions = $this->productions->where('year', $year);
    }
}
