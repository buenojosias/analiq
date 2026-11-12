<?php

namespace App\Livewire\Production;

use App\Models\Project;
use App\Models\State;
use Livewire\Attributes\Validate;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class ProductionEdit extends Component
{
    use Interactions;

    public $project;

    public $bibliometric;

    public $production;

    public $years = [];

    public $states = [];

    public $terms = [];

    public $author = [];

    public $authors_display = [];

    #[Validate('nullable|url')]
    public $url;

    #[Validate('required|string|in_array:bibliometric.repositories.*')]
    public $repository;

    #[Validate('required|string')]
    public $title;

    #[Validate('required|integer|digits:4|in_array:years.*')]
    public $year;

    #[Validate('required|array')]
    public $authors = [];

    #[Validate('required|string|in_array:bibliometric.types.*')]
    public $type;

    #[Validate('required|string|in_array:bibliometric.languages.*')]
    public $language;

    #[Validate('required|array')]
    public $searched_terms = [];

    #[Validate('nullable|string')]
    public $institution;

    #[Validate('nullable|string')]
    public $program;

    #[Validate('nullable|string')]
    public $city;

    #[Validate('nullable|integer|in_array:states.*.id')]
    public $state_id;

    #[Validate('nullable|string|max:60')]
    public $country;

    #[Validate('nullable|string')]
    public $periodical;

    #[Validate('nullable|string')]
    public $doi;

    public $customFields = [];

    public $customValues = [];

    public function mount(Project $project, $production)
    {
        $this->project = $project;
        $this->bibliometric = $project->bibliometric;
        $this->production = $project->productions()->findOrFail($production);

        for ($i = intval($this->bibliometric->start_year); $i <= $this->bibliometric->end_year; $i++) {
            array_push($this->years, $i);
        }

        $this->states = State::select('id', 'abbreviation')->orderBy('abbreviation')->get()->toArray();
        $this->terms = $this->bibliometric->terms;
        $this->url = $this->production->url;
        $this->repository = $this->production->repository;
        $this->title = $this->production->title;
        $this->year = $this->production->year;
        $this->type = $this->production->type->value;
        $this->language = $this->production->language;
        $this->searched_terms = $this->production->searched_terms;
        $this->institution = $this->production->institution;
        $this->program = $this->production->program;
        $this->city = $this->production->city;
        $this->state_id = $this->production->state_id;
        $this->country = $this->production->country;
        $this->periodical = $this->production->periodical;
        $this->doi = $this->production->doi;

        $this->authors = $this->production->authors->toArray();

        // $this->customFields = $this->bibliometric->customFields()->with('productions', function($query) {
        //     $query->where('productions.id', $this->production->id)->select('productions.id');
        // })->get();

        foreach ($this->authors as $author) {
            array_push($this->authors_display, ' '.$author['forename'].' '.$author['lastname']);
        }
    }

    public function save()
    {
        $data = $this->validate();

        try {
            $this->production->update($data);
        } catch (\Throwable $th) {
            dump($th);
        }
        $this->toast()->success('Salvo', 'Produção atualizada com sucesso.')->send();
    }

    public function render()
    {
        return view('livewire.production.production-edit')
            ->title('Editar produção');
    }

    public function addAuthor()
    {
        $this->validate([
            'author.forename' => 'required|string',
            'author.lastname' => 'required|string',
        ]);
        $this->production->authors()->create($this->author);
        array_push($this->authors, ['forename' => $this->author['forename'], 'lastname' => $this->author['lastname']]);
        array_push($this->authors_display, ' '.$this->author['forename'].' '.$this->author['lastname']);
        $this->reset('author');
    }

    public function removeAuthor($key)
    {
        $this->production->authors()->where('forename', $this->authors[$key]['forename'])->where('lastname', $this->authors[$key]['lastname'])->delete();
        array_splice($this->authors, $key, 1);
        array_splice($this->authors_display, $key, 1);
    }

    public function titleToLower()
    {
        $title = \Str::lower($this->title);
        $this->title = \Str::ucfirst($title);
    }
}
