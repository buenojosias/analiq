<?php

namespace App\Livewire\Internal;

use App\Enums\ProductionSectionEnum;
use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;
use Smalot\PdfParser\Parser;
use TallStackUi\Traits\Interactions;

class InternalForm extends Component
{
    use Interactions;

    public $project;

    public $production;

    public $section;

    public $file;

    public $internal;

    public $sectionLabel;

    public $content;

    public $total_words;

    public int $first_page;

    public int $last_page;

    public $path;

    #[On('file-uploaded')]
    public function fileUploaded($file)
    {
        $this->file = $this->production->file()->find($file['id']);
        $this->path = str_replace('files/', '', $this->file->path);
        $this->toast()->success('Arquivo enviado com sucesso.')->send();
    }

    public function mount(Project $project, $production, $section)
    {
        $this->project = $project;

        $this->production = $this->project->productions()
            ->select(['id', 'title', 'year'])
            ->findOrFail($production);

        $this->file = $this->production->file;

        if ($this->file) {
            $this->path = str_replace('files/', '', $this->file->path);
        }

        $this->internal = $this->production->internals()
            ->where('section', $this->section)
            ->first();

        if ($this->internal) {
            $this->content = $this->internal->content;
        }

        $this->sectionLabel = strtolower(ProductionSectionEnum::from($this->section)->label());
    }

    public function extractText()
    {
        $this->validate([
            'first_page' => 'required|integer|min:1|lte:file.pages',
        ]);
        $this->validate([
            'last_page' => 'required|integer|gte:first_page|lte:file.pages',
        ]);

        $path = storage_path('app/'.$this->file->path);
        $parser = new Parser;
        $pdf = $parser->parseFile($path);

        $text = '';

        for ($i = $this->first_page - 1; $i <= $this->last_page - 1; $i++) {
            $pages = $pdf->getPages()[$i];
            $text .= $pages->getText();
        }

        $text = preg_replace('/\n|\r\n|\r|<br \/>/', '', $text);
        $extracted = preg_replace('/<br \/>/', '', $text);
        $extracted = trim($extracted);
        $this->content = $extracted;
    }

    public function save()
    {
        $this->validate([
            'content' => 'required|string',
        ]);

        $words = preg_split('/\s+|(?<!\pL)(?<!\d)(?!\d*\pL)/u', $this->content, -1, PREG_SPLIT_NO_EMPTY);
        $this->total_words = count($words);

        if (
            $this->production->internals()->updateOrCreate(
                [
                    'section' => $this->section,
                ],
                [
                    'content' => $this->content,
                    'total_words' => $this->total_words,
                ]
            )
        ) {
            $this->toast()->success('Conteúdo salvo com sucesso.')->send();
        }

    }

    public function render()
    {
        return view('livewire.internal.internal-form')
            ->title('Editar '.$this->sectionLabel);
    }
}
