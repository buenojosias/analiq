<?php

namespace App\Livewire\WordRanking;

use App\Models\Internal;
use App\Models\Project;
use App\Models\WordRanking;
use Livewire\Attributes\Validate;
use Livewire\Component;

class WordRankingCreate extends Component
{
    public $project;

    public $bibliometric;

    public $productions = [];

    #[Validate('required|array')]
    public $sections = [];

    #[Validate('required|array')]
    public $production_types = [];

    #[Validate('nullable|array')]
    public $years;

    #[Validate('required|integer')]
    public $minLenght = 4;

    #[Validate('required|integer')]
    public $wordsLimit = 50;

    public $combinedWords = ['educação ambiental', 'cenário internacional'];

    public $excludedWords = [
        'como',
        'foram',
        'entre',
        'não',
        'para',
        'partir',
        'pela', 'pelo', 'pelas', 'pelos',
        'são',
        'sobre',
        'também', 'além',
        'mais',
        'acerca',
        'seu', 'sua', 'seus', 'suas',
        'teve', 'qual',
    ];

    public $results = [];

    public $selectedResult;

    #[Validate('nullable|string')]
    public $title;

    #[Validate('nullable|string')]
    public $description;

    public $data = [];

    public $savingModal = false;

    public $savedModal = false;

    public $savedWordRanking;

    public function mount(Project $project)
    {
        $this->project = $project;

        $this->bibliometric = $project->bibliometric;
    }

    public function generate()
    {
        $internalsContent = Internal::query()
            ->whereRelation('production', fn ($q) => $q->whereIn('type', $this->production_types))
            ->whereHas('production', function ($query) {
                $query->whereIn('type', $this->production_types)
                    ->where('project_id', $this->project->id);
            })
            ->whereIn('section', $this->sections)
            // ->when($this->productions, function ($q) {
            //     $q->whereIn('production_id', $this->productions);
            // })
            ->pluck('content')
            ->toArray();

        $allContent = implode(' ', $internalsContent);

        $allContent = preg_replace('/[^\p{L}\p{N}\s]/u', '', $allContent);

        $words = preg_split('/\s+/', mb_strtolower($allContent, 'UTF-8'), -1, PREG_SPLIT_NO_EMPTY);

        $wordCount = [];
        foreach ($words as $word) {
            if (strlen($word) >= $this->minLenght && ! in_array($word, $this->excludedWords)) {
                $wordCount[$word]['id'] = rand(00000000, 99999000);
                $wordCount[$word]['word'] = $word;
                $wordCount[$word]['count'] = isset($wordCount[$word]['count']) ? $wordCount[$word]['count'] + 1 : 1;
                $wordCount[$word]['internals'] = array_unique(
                    array_merge(
                        isset($wordCount[$word]['internals']) ? $wordCount[$word]['internals'] : [],
                        array_keys(array_filter($internalsContent, function ($content) use ($word) {
                            return stripos($content, $word) !== false;
                        }))
                    )
                );
            }
        }

        // Contar as palavras combinadas
        foreach ($this->combinedWords as $combinedWord) {
            $wordParts = explode(' ', $combinedWord);
            $combinedCount = substr_count($allContent, $combinedWord);
            foreach ($wordParts as $part) {
                if (isset($wordCount[$part])) {
                    $wordCount[$part]['count'] -= $combinedCount;
                    $wordCount[$combinedWord] = [
                        'id' => rand(00000000, 99999000),
                        'word' => $combinedWord,
                        'count' => $combinedCount,
                        'posts' => array_keys(array_filter($internalsContent, function ($content) use ($combinedWord) {
                            return stripos($content, $combinedWord) !== false;
                        })),
                    ];
                }
            }
        }

        uasort($wordCount, function ($a, $b) {
            return $b['count'] - $a['count'];
        });

        $this->results = collect(array_slice($wordCount, 0, $this->wordsLimit));
        // dump($this->results);
    }

    public function selectResult($id)
    {

        // dump('hi');
        // $this->reset('selectedResult');
        // sleep(2);
        // $this->selectedResult = $this->results->where('id', $id)->first();
        // $this->dispatch('load-productions', $data = $this->selectedResult);
    }

    public function save()
    {
        $filters = [
            'min_lenght' => $this->minLenght,
            'production_types' => $this->production_types,
            'internal_sections' => $this->sections,
            'years' => $this->years,
        ];
        foreach ($this->results as $result) {
            $record = [
                'id' => $result['id'],
                'word' => $result['word'],
                'count' => $result['count'],
                'internal_count' => count($result['internals']),
                'internal_ids' => $result['internals'],
            ];
            $this->data[] = $record;
        }

        $wordRanking = [
            'project_id' => $this->project->id,
            'title' => $this->title,
            'description' => $this->description,
            'words_limit' => $this->wordsLimit,
            'filters' => $filters,
            'records' => $this->data,
        ];

        // dump($wordRanking);

        try {
            $this->savedWordRanking = WordRanking::create($wordRanking);
            $this->savingModal = false;
            $this->savedModal = true;
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function clear()
    {
        $this->savedModal = false;
        $this->reset(['title', 'description', 'data', 'results']);
    }

    public function render()
    {
        $headers = [
            ['index' => 'word', 'label' => 'Palavra', 'sortable' => false],
            ['index' => 'count', 'label' => 'Ocorrências', 'sortable' => false],
            ['index' => 'internal_count', 'label' => 'Produções', 'sortable' => false],
            ['index' => 'action', 'label' => '', 'sortable' => false],
        ];
        $resultRows = collect($this->results)
            ->map(fn (array $result): array => [
                ...$result,
                'internal_count' => count($result['internals'] ?? []),
            ])
            ->values();

        return view('livewire.word-ranking.word-ranking-create', compact('headers', 'resultRows'))
            ->title('Gerar ranking de palavras');
    }
}
