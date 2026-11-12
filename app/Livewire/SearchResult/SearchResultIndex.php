<?php

namespace App\Livewire\SearchResult;

use App\Enums\SearchSectionEnum;
use App\Models\Project;
use Livewire\Attributes\Validate;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class SearchResultIndex extends Component
{
    use Interactions;

    public $modal = false;

    public $project;

    public $bibliometric;

    public $bibliometricTypes = [];

    public $bibliometricRepositories = [];

    public $bibliometricLanguages = [];

    public $bibliometricTerms = [];

    public $bibliometricYears = [];

    public $avaliableSections = [];

    #[Validate('required|string|in_array:bibliometric.repositories.*')]
    public $repository;

    #[Validate('nullable|integer|digits:4|in_array:bibliometricYears.*')]
    public $year;

    #[Validate('required|array')]
    public $types;

    #[Validate('required|string|in_array:bibliometric.languages.*')]
    public $language;

    #[Validate('required|array')]
    public $terms;

    #[Validate('required|array')]
    public $sections;

    #[Validate('required|integer|min:0')]
    public $quantity;

    public function mount(Project $project)
    {
        $this->project = $project;

        $this->bibliometric = $project->bibliometric;

        for ($i = intval($this->bibliometric->start_year); $i <= $this->bibliometric->end_year; $i++) {
            array_push($this->bibliometricYears, $i);
        }

        $this->bibliometricTypes = $this->bibliometric->types;
        $this->bibliometricLanguages = $this->bibliometric->languages;
        $this->bibliometricRepositories = $this->bibliometric->repositories;
        $this->bibliometricTerms = $this->bibliometric->terms;

        $this->avaliableSections = SearchSectionEnum::cases();
    }

    public function render()
    {
        $results = collect($this->project->results);

        // RESULTS BY DESCRIPTOR
        $groupedByWords = $results->groupBy(function ($result) {
            $words = $result->terms;
            sort($words);

            return json_encode($words);
        });

        $tableByYear = [];
        $tableByRepository = [];

        $yearTotals = [];
        $repositoryTotals = [];

        $years = $this->bibliometricYears;
        $repositories = $this->bibliometricRepositories;

        foreach ($years as $year) {
            $yearTotals[$year] = 0;
        }
        $yearTotals['total'] = 0;

        foreach ($repositories as $repository) {
            $repositoryTotals[$repository] = 0;
        }
        $repositoryTotals['total'] = 0;

        foreach ($groupedByWords as $words => $group) {
            $wordsArray = json_decode($words, true);
            $wordsKey = implode(' AND ', $wordsArray);

            $yearTotal = 0;
            $repositoryTotal = 0;

            foreach ($years as $year) {
                $sum = $group->filter(function ($result) use ($year) {
                    return $result->year == $year;
                })->sum('quantity');

                $tableByYear[$wordsKey][$year] = $sum;
                $yearTotals[$year] += $sum;
                $yearTotal += $sum;
            }

            foreach ($repositories as $repository) {
                $sum = $group->filter(function ($result) use ($repository) {
                    return $result->repository === $repository;
                })->sum('quantity');

                $tableByRepository[$wordsKey][$repository] = $sum;
                $repositoryTotals[$repository] += $sum;
                $repositoryTotal += $sum;
            }

            $tableByYear[$wordsKey]['total'] = $yearTotal;
            $tableByRepository[$wordsKey]['total'] = $repositoryTotal;

            $yearTotals['total'] += $yearTotal;
            $repositoryTotals['total'] += $repositoryTotal;
        }
        // END RESULTS BY DESCRIPTOR

        $detailHeaders = [
            ['index' => 'repository', 'label' => 'Repositório', 'sortable' => false],
            ['index' => 'terms', 'label' => 'Palavras buscadas', 'sortable' => false],
            ['index' => 'sections', 'label' => 'Seções buscadas', 'sortable' => false],
            ['index' => 'types', 'label' => 'Tipos', 'sortable' => false],
            ['index' => 'language', 'label' => 'Idioma', 'sortable' => false],
            ['index' => 'year', 'label' => 'Ano', 'sortable' => false],
            ['index' => 'quantity', 'label' => 'Resultados', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];
        $repositoryDetailHeaders = array_values(array_filter($detailHeaders, fn (array $header): bool => in_array($header['index'], ['terms', 'sections', 'types', 'year', 'quantity'])));
        $buildMatrix = function (array $labels, array $data, array $totals): array {
            $headers = [['index' => 'label', 'label' => 'Descritores', 'sortable' => false]];

            foreach ($labels as $index => $label) {
                $headers[] = ['index' => 'value_'.$index, 'label' => (string) $label, 'sortable' => false, 'align' => 'center'];
            }

            $headers[] = ['index' => 'total', 'label' => 'Total', 'sortable' => false, 'align' => 'center'];
            $rows = [];

            foreach ($data as $name => $values) {
                $row = ['label' => $name, 'total' => $values['total'] ?? 0];

                foreach ($labels as $index => $label) {
                    $row['value_'.$index] = $values[$label] ?? 0;
                }

                $rows[] = $row;
            }

            if ($rows !== []) {
                $totalRow = ['label' => 'Total', 'total' => $totals['total']];

                foreach ($labels as $index => $label) {
                    $totalRow['value_'.$index] = $totals[$label] ?? 0;
                }

                $rows[] = $totalRow;
            }

            return [$headers, $rows];
        };
        [$repositoryMatrixHeaders, $repositoryMatrixRows] = $buildMatrix($repositories, $tableByRepository, $repositoryTotals);
        [$yearMatrixHeaders, $yearMatrixRows] = $buildMatrix($years, $tableByYear, $yearTotals);

        return view('livewire.search-result.search-result-index', compact(
            'results',
            'detailHeaders',
            'repositoryDetailHeaders',
            'repositoryMatrixHeaders',
            'repositoryMatrixRows',
            'yearMatrixHeaders',
            'yearMatrixRows',
        ))
            ->title('Resultado preliminar');
    }

    public function submit()
    {
        $data = $this->validate();

        if ($this->project->results()->create($data)) {
            $this->reset(['year', 'quantity']);
            $this->modal = false;
            $this->toast()->success('Resultado salvo com sucesso.')->send();
        }
    }

    public function delete($id)
    {
        $this->dialog()
            ->question('Excluir item', 'Tem certeza que deseja excluir este item?')
            ->confirm('Confirmar', 'doDelete', $id)
            ->cancel('Cancelar')
            ->send();
    }

    public function doDelete($id)
    {
        $this->project->results()->whereId($id)->delete();
        $this->toast()->success('Item excluído com sucesso.')->send();
    }
}
