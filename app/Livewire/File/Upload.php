<?php

namespace App\Livewire\File;

use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Smalot\PdfParser\Parser;

class Upload extends Component
{
    use WithFileUploads;

    public $production;

    public $production_id;

    public $filename;

    public $path;

    public $size;

    #[Validate('mimes:pdf')]
    public $file;

    public $pages;

    public function mount($production)
    {
        $this->production = $production;
        $this->production_id = $production->id;

        $this->filename =
            $this->production->title.' ('.
            $this->production->year.')';
    }

    public function updatedFile(): void
    {
        $this->validate();
        $this->size = number_format($this->file->getSize() / 1024 / 1024, 3, '.', ',');
        $this->store();
    }

    protected function store(): void
    {
        $saved = $this->file->store('pdfs');
        $this->path = str_replace('pdfs/', '', $saved);
        $file = Storage::get($saved);

        $parser = new Parser;
        $pdf = $parser->parseContent($file);
        $this->pages = count($pdf->getPages());

        $this->save();
    }

    protected function save(): void
    {
        $data = $this->validate([
            'production_id' => 'required|integer',
            'filename' => 'required|string',
            'path' => 'required|string',
            'size' => 'required|decimal:3',
            'pages' => 'required|integer|min:1',
        ]);

        $file = $this->production->file()->create($data);
        $this->dispatch('file-uploaded', file: $file);
    }

    public function render()
    {
        return view('livewire.file.upload');
    }
}
