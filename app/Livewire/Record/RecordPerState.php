<?php

namespace App\Livewire\Record;

use App\Models\Project;
use App\Models\State;
use Livewire\Component;

class RecordPerState extends Component
{
    public $project;

    public $states = [];

    public $selectedState = '';

    public $stateProductions = [];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        $this->states = State::query()
            ->has('productions')
            ->withCount([
                'productions' => function ($query) {
                    $query->where('project_id', $this->project->id);
                },
            ])
            ->orderBy('name')
            ->get();

        $groupByRegion = $this->states->groupBy('region');
        $stateRows = $groupByRegion->flatMap(fn ($states, $region) => $states->map(fn ($state): array => [
            'id' => $state->id,
            'region' => $region,
            'name' => $state->name,
            'abbreviation' => $state->abbreviation,
            'productions_count' => $state->productions_count,
        ]))->values();
        $stateProductionRows = collect($this->stateProductions)->flatMap(fn ($productions) => $productions)->values();
        $stateHeaders = [
            ['index' => 'name', 'label' => 'Estado', 'sortable' => false],
            ['index' => 'productions_count', 'label' => 'Produções', 'sortable' => false, 'align' => 'center'],
        ];
        $productionHeaders = [
            ['index' => 'city', 'label' => 'Cidade', 'sortable' => false],
            ['index' => 'full_title', 'label' => 'Título', 'sortable' => false],
            ['index' => 'type', 'label' => 'Tipo', 'sortable' => false],
            ['index' => 'year', 'label' => 'Ano', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];

        return view('livewire.record.record-per-state', compact(
            'stateRows',
            'stateProductionRows',
            'stateHeaders',
            'productionHeaders',
        ))
            ->title('Relatório por estado');
    }

    public function selectState($id)
    {
        $this->selectedState = $id;
        $stateProductions = $this->project->productions()
            ->where('state_id', $this->selectedState)
            ->orderBy('city')
            ->get();

        $stateProductions = $stateProductions->map(function ($production) {
            $production->city = $production->city ?? 'Cidade não informada';

            return $production;
        });

        $this->stateProductions = collect($stateProductions->groupBy('city'));

        $this->stateProductions;
    }
}
